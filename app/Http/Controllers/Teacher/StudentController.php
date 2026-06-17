<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentClassHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StudentController extends Controller
{
    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('guru.students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'nisn'             => 'required|string|max:50|unique:users,nisn',
            'email'            => 'required|email|unique:users,email',
            'phone'            => 'required|string|max:20',
            'date_of_birth'    => 'required|date',
            'class'            => 'required|string|max:50',
            'academic_year'    => 'required|string|max:50',
            'homeroom_teacher' => 'required|string|max:255',
            'password'         => 'required|string|min:8|regex:/[0-9]/|regex:/[a-zA-Z]/',
        ], [
            'password.regex'   => 'Password harus mengandung minimal satu angka dan satu huruf.',
            'password.min'     => 'Password minimal 8 karakter.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'nisn' => $validated['nisn'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'class' => $validated['class'],
            'academic_year' => $validated['academic_year'],
            'homeroom_teacher' => $validated['homeroom_teacher'],
            'password' => Hash::make($validated['password']),
            'role' => 0, // Siswa
        ]);

        $this->saveStudentClassHistory($user);

        return redirect()
            ->route('guru.dashboard')
            ->with('success', "Akun siswa '{$user->name}' berhasil dibuat!");
    }

    /**
     * Import multiple student accounts from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'selected_class' => 'nullable|string|max:50',
            'selected_academic_year' => 'nullable|string|max:50',
            'tab' => 'nullable|string',
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
            return back()->withInput()->with('error', 'File harus berupa Excel/CSV (.xlsx, .xls, .csv, .xlsm, .ods, .xlsb).');
        }

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (count($rows) < 1) {
            return back()->withInput()->with('error', 'File tidak berisi data.');
        }

        $headerRowIndex = null;
        foreach ($rows as $index => $row) {
            $hasValue = false;
            foreach ($row as $cell) {
                if (trim((string)$cell) !== '') {
                    $hasValue = true;
                    break;
                }
            }
            if ($hasValue) {
                $headerRowIndex = $index;
                break;
            }
        }

        if ($headerRowIndex === null) {
            return back()->withInput()->with('error', 'Baris header tidak ditemukan. Pastikan file memiliki baris header.');
        }

        $headerRow = $rows[$headerRowIndex];
        $columns = array_keys($headerRow);
        $headers = [];
        foreach ($columns as $column) {
            $headers[$column] = $this->normalizeStudentHeader(trim((string)$headerRow[$column]));
        }

        if (collect($headers)->filter()->isEmpty()) {
            return back()->withInput()->with('error', 'Header file tidak dikenali. Pastikan Anda menggunakan template_akun.xlsx dengan header NISN, NAMA LENGKAP, KELAS, EMAIL, TANGGAL LAHIR, NO_HANDPHONE.');
        }

        $parsedRows = [];
        foreach ($rows as $index => $row) {
            if ($index === $headerRowIndex) {
                continue;
            }

            $parsed = ['rowNumber' => $index];
            $hasAny = false;

            foreach ($columns as $column) {
                $field = $headers[$column] ?? null;
                if (!$field) {
                    continue;
                }

                $value = trim((string)($row[$column] ?? ''));
                if ($value !== '') {
                    $hasAny = true;
                }
                $parsed[$field] = $value;
            }

            if (!$hasAny) {
                continue;
            }

            $parsedRows[] = $parsed;
        }

        if (empty($parsedRows)) {
            return back()->withInput()->with('error', 'Tidak ada baris data siswa yang valid untuk diproses.');
        }

        $selectedClass = trim($request->input('selected_class', ''));
        $selectedAcademicYear = trim($request->input('selected_academic_year', ''));
        $existingUsers = User::where('role', 0)->get(['id', 'nisn', 'email']);
        $existingUsersByNisn = $existingUsers
            ->filter(fn($user) => !empty($user->nisn))
            ->keyBy(fn($user) => strtolower(trim($user->nisn)));
        $existingUsersByEmail = $existingUsers
            ->filter(fn($user) => !empty($user->email))
            ->keyBy(fn($user) => strtolower(trim($user->email)));

        $seenNisns = [];
        $seenEmails = [];
        $rowsToProcess = [];
        $errors = [];

        foreach ($parsedRows as $row) {
            $rowNumber = $row['rowNumber'] ?? 'unknown';
            $rowErrors = [];

            $nisn = trim($row['nisn'] ?? '');
            $name = trim($row['name'] ?? '');
            $email = trim($row['email'] ?? '');
            $phone = trim($row['phone'] ?? '');
            $dateOfBirth = trim($row['date_of_birth'] ?? '');
            $class = trim($row['class'] ?? '') ?: $selectedClass;
            $academicYear = trim($row['academic_year'] ?? '') ?: $selectedAcademicYear;
            $homeroomTeacher = trim($row['homeroom_teacher'] ?? '');
            $password = trim($row['password'] ?? '');

            if ($nisn === '') {
                $rowErrors[] = 'NISN wajib diisi.';
            }
            if ($name === '') {
                $rowErrors[] = 'Nama wajib diisi.';
            }
            if ($email === '') {
                $rowErrors[] = 'Email wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = 'Email tidak valid.';
            }
            if ($phone === '') {
                $rowErrors[] = 'Nomor HP wajib diisi.';
            }
            if ($dateOfBirth === '') {
                $rowErrors[] = 'Tanggal lahir wajib diisi.';
            } elseif (strtotime($dateOfBirth) === false) {
                $rowErrors[] = 'Format tanggal lahir tidak valid.';
            }
            if ($class === '') {
                $rowErrors[] = 'Kelas wajib diisi.';
            }
            if ($academicYear === '') {
                $rowErrors[] = 'Tahun ajaran wajib diisi.';
            }

            $nisnKey = strtolower($nisn);
            $emailKey = strtolower($email);
            $existingUserByNisn = $existingUsersByNisn->get($nisnKey);
            $existingUserByEmail = $existingUsersByEmail->get($emailKey);

            if ($password === '' && !$existingUserByNisn) {
                $password = 'Password123';
            }

            if (!empty($password) && (strlen($password) < 8 || !preg_match('/[0-9]/', $password) || !preg_match('/[a-zA-Z]/', $password))) {
                $rowErrors[] = 'Password harus minimal 8 karakter dan berisi angka serta huruf.';
            }

            if ($nisn !== '') {
                if (in_array($nisnKey, $seenNisns, true)) {
                    $rowErrors[] = 'Duplikat NISN dalam file.';
                }
            }

            if ($email !== '') {
                if (in_array($emailKey, $seenEmails, true)) {
                    $rowErrors[] = 'Duplikat email dalam file.';
                }
                if ($existingUserByEmail && (!$existingUserByNisn || $existingUserByEmail->id !== $existingUserByNisn->id)) {
                    $rowErrors[] = 'Email sudah ada di sistem untuk siswa lain.';
                }
            }

            if (!empty($rowErrors)) {
                foreach ($rowErrors as $message) {
                    $errors[] = "Baris {$rowNumber}: {$message}";
                }
                continue;
            }

            $seenNisns[] = $nisnKey;
            $seenEmails[] = $emailKey;

            $rowsToProcess[] = [
                'student_id' => $existingUserByNisn?->id,
                'name' => $name,
                'nisn' => $nisn,
                'email' => $email,
                'phone' => $phone,
                'date_of_birth' => date('Y-m-d', strtotime($dateOfBirth)),
                'class' => $class,
                'academic_year' => $academicYear,
                'homeroom_teacher' => $homeroomTeacher ?: null,
                'password' => $password,
            ];
        }

        if (!empty($errors)) {
            return back()->withInput()->with('error', 'Import gagal. ' . implode(' ', $errors));
        }

        DB::beginTransaction();
        try {
            foreach ($rowsToProcess as $row) {
                if ($row['student_id']) {
                    $student = User::find($row['student_id']);
                    if (!$student) {
                        continue;
                    }
                    $updateData = [
                        'name' => $row['name'],
                        'nisn' => $row['nisn'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'date_of_birth' => $row['date_of_birth'],
                        'class' => $row['class'],
                        'academic_year' => $row['academic_year'],
                        'homeroom_teacher' => $row['homeroom_teacher'],
                    ];

                    if (!empty($row['password'])) {
                        $updateData['password'] = Hash::make($row['password']);
                    }

                    $student->update($updateData);
                    if ($student->wasChanged(['class', 'academic_year', 'homeroom_teacher', 'name', 'email', 'phone', 'date_of_birth'])) {
                        $this->saveStudentClassHistory($student);
                    }
                } else {
                    $newStudent = [
                        'name' => $row['name'],
                        'nisn' => $row['nisn'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'date_of_birth' => $row['date_of_birth'],
                        'class' => $row['class'],
                        'academic_year' => $row['academic_year'],
                        'homeroom_teacher' => $row['homeroom_teacher'],
                        'password' => Hash::make($row['password'] ?: 'Password123'),
                        'role' => 0,
                    ];
                    $student = User::create($newStudent);
                    $this->saveStudentClassHistory($student);
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }

        return redirect()->route('guru.dashboard', ['tab' => 'students'])
            ->with('success', 'Import berhasil. ' . count($rowsToProcess) . ' siswa berhasil diproses. Akun lama akan diperbarui dan akun baru akan dibuat.');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NISN');
        $sheet->setCellValue('B1', 'NAMA LENGKAP');
        $sheet->setCellValue('C1', 'KELAS');
        $sheet->setCellValue('D1', 'TAHUN AJARAN');
        $sheet->setCellValue('E1', 'EMAIL');
        $sheet->setCellValue('F1', 'TANGGAL LAHIR');
        $sheet->setCellValue('G1', 'NO_HANDPHONE');

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(28);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(26);
        $sheet->getColumnDimension('F')->setWidth(16);
        $sheet->getColumnDimension('G')->setWidth(18);

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_akun.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    protected function normalizeStudentHeader(string $header): ?string
    {
        $normalized = $header;
        $normalized = preg_replace('/[\x{feff}\x{200b}\x{00a0}]/u', ' ', $normalized);
        $normalized = str_replace(["\r", "\n", "\t"], ' ', $normalized);
        $normalized = preg_replace('/[^a-z0-9]+/i', '_', $normalized);
        $normalized = preg_replace('/_+/', '_', $normalized);
        $normalized = trim($normalized, '_');
        $normalized = strtolower($normalized);

        return match ($normalized) {
            'nisn', 'nis', 'nisn_number', 'nomor_nisn', 'nomor_nis' => 'nisn',
            'nama', 'nama_lengkap', 'name', 'full_name', 'nama_lengkap_siswa' => 'name',
            'email', 'e_mail' => 'email',
            'telepon', 'phone', 'no_hp', 'hp', 'phone_number', 'nomor_hp', 'no_handphone', 'handphone' => 'phone',
            'tanggal_lahir', 'birth_date', 'date_of_birth', 'dob' => 'date_of_birth',
            'kelas', 'class', 'grade' => 'class',
            'wali_kelas', 'homeroom_teacher', 'teacher' => 'homeroom_teacher',
            'tahun_ajaran', 'tahun ajaran', 'academic_year', 'academic year' => 'academic_year',
            'password', 'pass' => 'password',
            default => null,
        };
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(User $student)
    {
        if ($student->role !== 0) {
            return redirect()->route('guru.dashboard')->with('error', 'User ini bukan siswa!');
        }

        return view('guru.students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, User $student)
    {
        if ($student->role !== 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'User ini bukan siswa!'], 403);
            }
            return redirect()->route('guru.dashboard')->with('error', 'User ini bukan siswa!');
        }

        // Check if this is an AJAX request for class update only
        if (($request->ajax() || $request->wantsJson()) && $request->has('class') && count($request->all()) === 1) {
            // AJAX class-only update
            $validated = $request->validate([
                'class' => 'required|string|max:50',
            ]);

            $student->update($validated);
            if ($student->wasChanged('class')) {
                $this->saveStudentClassHistory($student);
            }

            return response()->json(['message' => "Kelas siswa '{$student->name}' berhasil diperbarui!"]);
        }

        // Full form update
        // Only validate the fields present in the edit form. Email and password are not editable here.
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'nisn'             => 'required|string|max:50|unique:users,nisn,' . $student->id,
            'phone'            => 'required|string|max:20',
            'date_of_birth'    => 'required|date',
            'class'            => 'required|string|max:50',
            'academic_year'    => 'required|string|max:50',
            'homeroom_teacher' => 'required|string|max:255',
            'password'         => 'nullable|string|min:8|regex:/[0-9]/|regex:/[a-zA-Z]/',
        ], [
            'password.regex'   => 'Password harus mengandung minimal satu angka dan satu huruf.',
            'password.min'     => 'Password minimal 8 karakter.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $student->update($validated);
        if ($student->wasChanged(['class', 'academic_year', 'homeroom_teacher'])) {
            $this->saveStudentClassHistory($student);
        }

        return redirect()
            ->route('guru.dashboard')
            ->with('success', "Data siswa '{$student->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Request $request, User $student)
    {
        if ($student->role !== 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'User ini bukan siswa!'], 403);
            }
            return redirect()->route('guru.dashboard')->with('error', 'User ini bukan siswa!');
        }

        $studentName = $student->name;
        $student->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => "Akun siswa '{$studentName}' berhasil dihapus."]);
        }

        return redirect()
            ->route('guru.dashboard')
            ->with('success', "Akun siswa '{$studentName}' berhasil dihapus!");
    }

    /**
     * Show list of students created by this teacher.
     */
    public function index()
    {
        $students = User::where('role', 0)
            ->orderBy('class')
            ->orderBy('name')
            ->paginate(15);

        return view('guru.students.index', compact('students'));
    }

    /**
     * Display the specified student.
     */
    public function show(User $student)
    {
        if ($student->role !== 0) {
            return redirect()->route('guru.dashboard')->with('error', 'User ini bukan siswa!');
        }

        return view('guru.students.show', compact('student'));
    }

    protected function saveStudentClassHistory(User $student)
    {
        StudentClassHistory::create([
            'user_id' => $student->id,
            'academic_year' => $student->academic_year,
            'student_class' => $student->class,
            'homeroom_teacher' => $student->homeroom_teacher,
        ]);
    }
}
