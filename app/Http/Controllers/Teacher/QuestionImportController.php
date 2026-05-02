<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;

class QuestionImportController extends Controller
{
    public function create()
    {
        $modules = Module::all(['id', 'name', 'subject_id']);
        return view('guru.questions.import', [
            'modules' => $modules,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        $file = $request->file('file');

        // use temporary path
        $path = $file->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (count($rows) < 1) {
            return back()->with('error', 'File tidak berisi data. Pastikan ada baris header dan minimal 1 baris data.');
        }

        // Find the first non-empty row to use as header (skip leading blank rows)
        $firstKey = null;
        foreach ($rows as $k => $r) {
            $hasNonEmpty = false;
            foreach ($r as $v) {
                if (trim((string)$v) !== '') {
                    $hasNonEmpty = true;
                    break;
                }
            }
            if ($hasNonEmpty) {
                $firstKey = $k;
                break;
            }
        }

        if ($firstKey === null) {
            return back()->with('error', 'File tidak berisi baris header. Pastikan format template diikuti.');
        }

        // Build header map: column letter -> normalized header name
        $headerRow = $rows[$firstKey];
        $colKeys = array_keys($headerRow);
        $headers = [];
        foreach ($colKeys as $col) {
            $raw = trim((string)($headerRow[$col] ?? ''));
            // remove common non-breaking spaces
            $raw = str_replace("\xc2\xa0", ' ', $raw);
            $headers[$col] = strtolower($raw);
        }

        // Build parsed rows for preview (do not save yet)
        $parsed = [];
        foreach ($rows as $idx => $row) {
            if ($idx === $firstKey) continue; // header

            $assoc = [];
            foreach ($colKeys as $col) {
                $h = $headers[$col] ?? null;
                $val = isset($row[$col]) ? trim((string)$row[$col]) : '';
                if ($h) {
                    $assoc[$h] = $val;
                }
            }

            $rowErrors = [];
            $questionText = $assoc['question'] ?? $assoc['pertanyaan'] ?? $assoc['soal'] ?? null;
            if (!$questionText) {
                $rowErrors[] = "kolom 'Pertanyaan' tidak ditemukan atau kosong";
            }

            $type = $assoc['type'] ?? ($assoc['tipe'] ?? 'multiple_choice');
            $points = isset($assoc['points']) && is_numeric($assoc['points']) ? (int)$assoc['points'] : (isset($assoc['poin']) && is_numeric($assoc['poin']) ? (int)$assoc['poin'] : 0);
            $class = $assoc['class'] ?? ($assoc['kelas'] ?? null);

            $rowModuleId = $assoc['module_id'] ?? ($assoc['module'] ?? null);
            $moduleId = $request->input('module_id') ?: ($rowModuleId ?: null);
            if (!$moduleId) {
                $rowErrors[] = 'module_id tidak ditentukan; pilih module saat upload atau sertakan kolom module_id';
            } elseif (!Module::where('id', $moduleId)->exists()) {
                $rowErrors[] = "module_id {$moduleId} tidak ditemukan";
            }

            // options detection
            $options = null;
            if (isset($assoc['options']) && trim($assoc['options']) !== '') {
                $opts = array_values(array_filter(array_map('trim', explode('||', $assoc['options']))));
                $options = $opts ?: null;
            } else {
                $letterOptions = [];
                foreach ($headers as $col => $h) {
                    if (preg_match('/^[a-z]$/', $h)) {
                        $letterOptions[] = isset($row[$col]) ? trim((string)$row[$col]) : '';
                    }
                }
                if (empty($letterOptions)) {
                    foreach ($headers as $col => $h) {
                        if (strlen($h) === 1 && ctype_alpha($h)) {
                            $letterOptions[] = isset($row[$col]) ? trim((string)$row[$col]) : '';
                        }
                    }
                }
                if (!empty($letterOptions)) {
                    $opts = array_values(array_filter($letterOptions, function ($v) {
                        return $v !== '';
                    }));
                    $options = $opts ?: null;
                }
            }

            // correct answer
            $correctRaw = $assoc['correct_answer'] ?? ($assoc['jawaban benar'] ?? ($assoc['jawaban'] ?? ($assoc['jawaban_benar'] ?? '')));
            $correctAnswer = '';
            if ($correctRaw !== '') {
                $cr = strtoupper(trim($correctRaw));
                if (preg_match('/^[A-Z]$/', $cr) && is_array($options) && !empty($options)) {
                    $index = ord($cr) - ord('A');
                    if (isset($options[$index])) {
                        $correctAnswer = $options[$index];
                    } else {
                        $rowErrors[] = 'Jawaban benar berupa huruf namun tidak ada opsi yang sesuai';
                        $correctAnswer = $correctRaw;
                    }
                } else {
                    $correctAnswer = $correctRaw;
                }
            }

            // basic validation for MC questions
            if (in_array($type, ['multiple_choice', 'mixed']) && (!is_array($options) || count($options) < 2)) {
                $rowErrors[] = 'Pilihan ganda membutuhkan minimal 2 opsi (kolom A,B atau kolom options)';
            }

            $parsed[] = [
                'row' => $idx,
                'question' => $questionText,
                'type' => $type,
                'points' => $points,
                'class' => $class,
                'module_id' => $moduleId,
                'options' => $options,
                'correct_answer' => $correctAnswer,
                'errors' => $rowErrors,
            ];
        }

        // store parsed rows in session for confirmation step
        session(['import_parsed' => $parsed]);

        return view('guru.questions.import_preview', [
            'parsed' => $parsed,
        ]);
    }

    /**
     * Confirm and persist parsed import rows stored in session
     */
    public function confirm(Request $request)
    {
        $parsed = session('import_parsed');
        if (!is_array($parsed) || empty($parsed)) {
            return redirect()->route('guru.questions.import')->with('error', 'Tidak ada data impor untuk dikonfirmasi. Unggah file terlebih dahulu.');
        }

        $imported = 0;
        $failed = [];

        DB::beginTransaction();
        try {
            foreach ($parsed as $row) {
                if (!empty($row['errors'])) {
                    $failed[] = "Baris {$row['row']}: " . implode('; ', $row['errors']);
                    continue;
                }

                $q = new Question();
                $q->module_id = $row['module_id'];
                $q->type = $row['type'] === 'mixed' ? 'multiple_choice' : $row['type'];
                $q->question = $row['question'];
                $q->points = $row['points'] ?? 0;
                $q->class = $row['class'] ?? null;
                $q->options = $row['options'] ?? null;
                $q->correct_answer = $row['correct_answer'] ?? '';
                $q->created_by = Auth::id() ?? null;
                $q->published = true;
                $q->save();

                $imported++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('guru.questions.import')->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }

        // clear session parsed data
        session()->forget('import_parsed');

        $message = "Import selesai. Berhasil: {$imported}.";
        if (!empty($failed)) {
            $message .= ' Gagal: ' . count($failed) . ' baris.';
            session()->flash('import_failures', $failed);
        }

        return redirect()->route('guru.questions.import')->with('success', $message);
    }
}
