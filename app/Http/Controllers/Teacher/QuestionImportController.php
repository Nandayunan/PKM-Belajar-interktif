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
            'file' => 'required|file',
            'module_id' => 'nullable|exists:modules,id',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = strtolower($file->getClientMimeType());
        $allowedExtensions = ['xlsx', 'xls', 'csv', 'xlsm', 'ods', 'xlsb'];
        $allowedMimeTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'text/csv',
            'application/csv',
            'text/plain',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.ms-excel.sheet.macroenabled.12',
            'application/vnd.ms-excel.sheet.binary.macroenabled.12',
        ];

        if (!in_array($extension, $allowedExtensions, true) && !in_array($mime, $allowedMimeTypes, true)) {
            return back()->withErrors(['file' => 'The file field must be a spreadsheet file of type: xlsx, xls, xlsm, ods, xlsb, or csv'])->withInput();
        }

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
            $headers[$col] = $this->normalizeHeader($raw);
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

            // Skip rows that are entirely empty or only contain a 'no' column
            $allEmpty = true;
            foreach ($assoc as $k => $v) {
                if ($k === 'no') continue; // ignore numbering column
                if (trim((string)$v) !== '') {
                    $allEmpty = false;
                    break;
                }
            }
            if ($allEmpty) {
                continue;
            }

            $rowErrors = [];
            $questionText = $this->valueByAliases($assoc, ['question', 'questions', 'pertanyaan', 'soal']);
            if (!$questionText) {
                $rowErrors[] = "kolom 'Pertanyaan' tidak ditemukan atau kosong";
            }

            $rawType = trim(strtolower($this->valueByAliases($assoc, ['type', 'tipe'])));
            $rawType = str_replace([' ', '-', '\\'], '_', $rawType);
            $typeMap = [
                'multiple_choice' => 'multiple_choice',
                'multiplechoice' => 'multiple_choice',
                'pilihan_ganda' => 'multiple_choice',
                'multiple' => 'multiple_choice',
                'pilihan' => 'multiple_choice',
                'essay' => 'essay',
                'esai' => 'essay',
                'true_false' => 'true_false',
                'truefalse' => 'true_false',
                'true' => 'true_false',
                'false' => 'true_false',
                'tf' => 'true_false',
                'mixed' => 'mixed',
            ];
            $type = $typeMap[$rawType] ?? '';

            $pointsRaw = $this->valueByAliases($assoc, ['points', 'poin']);
            $points = is_numeric($pointsRaw) ? (int) $pointsRaw : 0;
            $class = $this->valueByAliases($assoc, ['class', 'kelas']) ?: null;

            $rowModuleId = $this->valueByAliases($assoc, ['module_id', 'module']);
            $moduleId = $request->input('module_id') ?: ($rowModuleId ?: null);
            if (!$moduleId) {
                $rowErrors[] = 'module_id tidak ditentukan; pilih module saat upload atau sertakan kolom module_id';
            } elseif (!Module::where('id', $moduleId)->exists()) {
                $rowErrors[] = "module_id {$moduleId} tidak ditemukan";
            }

            // options detection
            $options = $this->extractOptions($assoc, $row);

            // correct answer
            $correctRaw = $this->valueByAliases($assoc, ['correct_answer', 'jawaban_benar', 'jawaban benar', 'jawaban', 'kunci_jawaban', 'kunci', 'answer_key', 'option_a']);
            if ($correctRaw === '' && is_array($options) && !empty($options)) {
                $correctRaw = $options[0];
            }
            $correctAnswer = $this->resolveCorrectAnswer($correctRaw, $options, $rowErrors);

            if ($type === '') {
                if (is_array($options) && count($options) >= 2) {
                    $type = 'multiple_choice';
                } elseif (in_array(strtolower(trim($correctRaw)), ['true', 'false', 'benar', 'salah', '0', '1'], true)) {
                    $type = 'true_false';
                } else {
                    $type = 'essay';
                }
            }

            if (!in_array($type, ['multiple_choice', 'essay', 'true_false', 'mixed'], true)) {
                $rowErrors[] = "Tipe soal tidak valid: {$type}. Gunakan multiple_choice, essay, true_false, atau mixed.";
            }

            // basic validation for MC questions
            if (in_array($type, ['multiple_choice', 'mixed'], true) && (!is_array($options) || count($options) < 2)) {
                $rowErrors[] = 'Pilihan ganda membutuhkan minimal 2 opsi (kolom A/B/C/D atau kolom options)';
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
        $skipped = 0;
        $skippedRows = [];

        DB::beginTransaction();
        try {
            foreach ($parsed as $row) {
                if (!empty($row['errors'])) {
                    $failed[] = "Baris {$row['row']}: " . implode('; ', $row['errors']);
                    continue;
                }

                // Prevent duplicate: skip if same question text already exists for this module
                $questionTextTrim = trim((string)$row['question']);
                $exists = Question::where('module_id', $row['module_id'])
                    ->whereRaw('LOWER(TRIM(question)) = ?', [mb_strtolower($questionTextTrim)])
                    ->exists();
                if ($exists) {
                    $skipped++;
                    $skippedRows[] = "Baris {$row['row']}: duplikat pertanyaan (sudah ada)";
                    continue;
                }

                $q = new Question();
                $q->module_id = $row['module_id'];
                $type = $row['type'] ?? '';
                if (!in_array($type, ['multiple_choice', 'essay', 'true_false', 'mixed'], true)) {
                    $type = 'multiple_choice';
                }
                $q->type = $type === 'mixed' ? 'multiple_choice' : $type;
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
        if ($skipped > 0) {
            $message .= " Dilewati (duplikat): {$skipped}.";
            session()->flash('import_skipped', $skippedRows);
        }
        if (!empty($failed)) {
            $message .= ' Gagal: ' . count($failed) . ' baris.';
            session()->flash('import_failures', $failed);
        }

        // If there are no failures and no skipped duplicates, redirect to dashboard
        if (empty($failed) && $skipped === 0) {
            return redirect()->route('guru.dashboard')->with('success', $message);
        }

        // Otherwise, return to import preview/import page with details
        return redirect()->route('guru.questions.import')->with('success', $message);
    }

    private function normalizeHeader(string $value): string
    {
        $value = trim(mb_strtolower($value));
        $value = preg_replace('/[^a-z0-9]+/u', '_', $value) ?? $value;

        return trim($value, '_');
    }

    private function valueByAliases(array $row, array $aliases): string
    {
        foreach ($aliases as $alias) {
            $normalized = $this->normalizeHeader($alias);
            if (array_key_exists($normalized, $row)) {
                return trim((string) $row[$normalized]);
            }
        }

        return '';
    }

    private function extractOptions(array $row, array $rawRow): ?array
    {
        $optionsRaw = $this->valueByAliases($row, ['options', 'opsi', 'pilihan']);
        if ($optionsRaw !== '') {
            $options = array_values(array_filter(array_map('trim', explode('||', $optionsRaw)), static function ($value) {
                return $value !== '';
            }));

            return !empty($options) ? $options : null;
        }

        $optionAliases = [];
        foreach (['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'] as $letter) {
            $optionAliases[$letter] = [
                $letter,
                'option_' . $letter,
                'opsi_' . $letter,
                'pilihan_' . $letter,
                'choice_' . $letter,
                'jawaban_' . $letter,
            ];
        }

        $options = [];
        foreach ($optionAliases as $aliases) {
            $value = $this->valueByAliases($row, $aliases);
            if ($value !== '') {
                $options[] = $value;
            }
        }

        if (empty($options)) {
            foreach ($rawRow as $column => $cellValue) {
                $normalizedColumn = $this->normalizeHeader((string) $column);
                if (in_array($normalizedColumn, ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'], true)) {
                    $value = trim((string) $cellValue);
                    if ($value !== '') {
                        $options[] = $value;
                    }
                }
            }
        }

        return !empty($options) ? $options : null;
    }

    private function resolveCorrectAnswer(string $correctRaw, ?array $options, array &$rowErrors): string
    {
        if ($correctRaw === '') {
            return '';
        }

        $normalized = trim(mb_strtoupper($correctRaw));

        if (is_array($options) && !empty($options)) {
            // 1) Prefer exact match of the provided key/value to avoid
            // interpreting numeric option texts (e.g. "2") as indexes.
            foreach ($options as $opt) {
                if (trim((string) $opt) === trim((string) $correctRaw)) {
                    return $opt;
                }
            }

            // 2) If provided as a letter (A..H), map to index
            if (preg_match('/^[A-H]$/', $normalized)) {
                $index = ord($normalized) - ord('A');
                if (isset($options[$index])) {
                    return $options[$index];
                }

                $rowErrors[] = 'Jawaban benar berupa huruf, tetapi opsi pada kolom yang sesuai tidak ditemukan';
                return $correctRaw;
            }

            // 3) If provided as a digit and it didn't match an option text,
            // treat it as a 1-based index (fallback).
            if (preg_match('/^\d+$/', $normalized)) {
                $index = (int) $normalized - 1;
                if (isset($options[$index])) {
                    return $options[$index];
                }
            }
        }

        return $correctRaw;
    }
}
