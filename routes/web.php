<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ModuleController;
use App\Http\Controllers\Student\FeedbackController;
use App\Http\Controllers\Student\EnrollmentController as StudentEnrollmentController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Teacher\StudentController;
use App\Http\Controllers\Teacher\GradingController;
use App\Http\Controllers\Teacher\TaskController;
use App\Models\Question;
use App\Models\Module;
use App\Models\Subject;
use App\Models\SubjectEnrollment;
use App\Models\User;

Route::get('/', function () {
    if (Auth::check()) {
        /** @var User $user */
        $user = Auth::user();
        if ($user->isTeacher()) {
            return redirect()->route('guru.dashboard');
        }
        return redirect()->route('siswa.dashboard');
    }
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    // Registration routes (simple local implementation)
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Profile Route (untuk semua user yang authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        /** @var User $user */
        $user = Auth::user();
        if ($user->isTeacher()) {
            return view('guru.profile');
        }
        return view('siswa.profile');
    })->name('profile.show');

    Route::post('/profile', function (Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'password' => ['nullable', 'string', 'min:8', 'regex:/[0-9]/', 'regex:/[a-zA-Z]/', 'confirmed'],
        ], [
            'password.regex' => 'Password harus berisi huruf dan angka.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = $validated['password'];
        }

        $user->update($updateData);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    })->name('profile.update');
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/subject/{subject}')->group(function () {
        Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');
        Route::post('/enroll', [StudentEnrollmentController::class, 'store'])->name('subjects.enroll');

        // keep enroll per-subject route

        Route::prefix('/module/{module}')->group(function () {
            Route::get('/', [ModuleController::class, 'show'])->name('modules.show');
            Route::post('/submit-answer', [ModuleController::class, 'submitAnswer'])->name('modules.submit-answer');
            // Upload student submission (file)
            Route::post('/upload', [ModuleController::class, 'upload'])->name('modules.upload');
            Route::get('/review', [ModuleController::class, 'review'])->name('modules.review');
        });
    });

    // List all subjects for students to browse and enroll
    Route::get('/subjects', function () {
        $user = auth()->user();

        // For students: show subjects that are global (no class) or match student's class
        if ($user && method_exists($user, 'isStudent') && $user->isStudent()) {
            $subjects = App\Models\Subject::with('modules')
                ->where(function ($q) use ($user) {
                    $q->whereNull('class')
                      ->orWhere('class', $user->class);
                })
                ->get();
        } else {
            // For teachers/admins show all subjects
            $subjects = App\Models\Subject::with('modules')->get();
        }

        $enrolledSubjectIds = App\Models\SubjectEnrollment::where('user_id', $user->id)->pluck('subject_id')->toArray();
        return view('siswa.subjects.list', compact('subjects', 'enrolledSubjectIds'));
    })->name('subjects.list');

    // Feedback Routes
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/{progress}', [FeedbackController::class, 'create'])->name('create');
        Route::post('/{progress}', [FeedbackController::class, 'store'])->name('store');
    });
});

// Teacher Routes
Route::middleware(['auth', 'teacher'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    // Student Management Routes
    Route::get('/students/import/template', [StudentController::class, 'downloadTemplate'])->name('students.import.template');
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/ajax-list', [TeacherDashboardController::class, 'studentsAjaxList'])->name('students.ajax-list');
    Route::resource('students', StudentController::class)->only(['create', 'store', 'edit', 'update', 'destroy', 'index', 'show']);

    // Class Management Routes
    Route::post('/classes', [TeacherDashboardController::class, 'storeClass'])->name('classes.store');
    Route::put('/classes/{academicYear}', [TeacherDashboardController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{academicYear}', [TeacherDashboardController::class, 'destroyClass'])->name('classes.destroy');
    Route::post('/classes/{academicYear}/assign-students', [TeacherDashboardController::class, 'assignStudents'])->name('classes.assign-students');

    // Teacher notes (add comment for a student)
    Route::post('/student-note', [TeacherDashboardController::class, 'storeNote'])->name('students.note');

    // Grading Routes
    Route::prefix('grading')->name('grading.')->group(function () {
        Route::get('/', [GradingController::class, 'index'])->name('index');
        Route::get('/{answer}', [GradingController::class, 'show'])->name('show');
        Route::post('/{answer}', [GradingController::class, 'store'])->name('store');
        Route::get('/graded/list', [GradingController::class, 'graded'])->name('graded');
    });

    // Dummy routes for CRUD operations
    Route::get('/subjects/create', function () {
        return view('guru.subjects.create');
    })->name('subjects.create');

    Route::get('/subjects/{subject}', function (App\Models\Subject $subject) {
        // Load related modules and their questions to compute totals
        $subject->load(['modules.questions']);
        $totalModules = $subject->modules->count();
        $totalQuestions = $subject->modules->sum(function ($m) {
            return $m->questions->count();
        });

        return view('guru.subjects.show', compact('subject', 'totalModules', 'totalQuestions'));
    })->name('subjects.show');

    Route::get('/subjects/{subject}/edit', function (\App\Models\Subject $subject) {
        return view('guru.subjects.edit', compact('subject'));
    })->name('subjects.edit');

    Route::post('/subjects', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'grade' => 'required|in:VII,VIII,IX',
            'sections' => 'nullable|array',
            'sections.*' => 'in:A,B,C,D',
            'all_sections' => 'nullable|boolean',
            'access_code' => 'nullable|string|max:100',
            // 'icon' and 'color' are optional now — UI no longer provides them
        ]);

        $grade = $request->input('grade');
        $allSections = $request->boolean('all_sections');
        $sections = $request->input('sections', []);

        $classValue = $grade;
        if ($allSections) {
            $classValue .= '-ALL';
        } elseif (!empty($sections)) {
            $classValue .= '-' . implode(',', array_map('strtoupper', $sections));
        }

        $user = Auth::user();

        $data = [
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'class' => $classValue,
            'created_by' => $user->id,
        ];

        if ($request->filled('icon')) {
            $data['icon'] = $request->input('icon');
        }

        if ($request->filled('color')) {
            $data['color'] = $request->input('color');
        }

        if ($request->filled('access_code')) {
            $data['access_code'] = $request->input('access_code');
        }

        $subject = \App\Models\Subject::create($data);

        return redirect()->route('guru.dashboard')->with('success', 'Mata pelajaran berhasil dibuat');
    })->name('subjects.store');

    Route::put('/subjects/{subject}', function (\Illuminate\Http\Request $request, \App\Models\Subject $subject) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'grade' => 'required|in:VII,VIII,IX',
            'sections' => 'nullable|array',
            'sections.*' => 'in:A,B,C,D',
            'all_sections' => 'nullable|boolean',
            'access_code' => 'nullable|string|max:100',
        ]);

        $grade = $request->input('grade');
        $allSections = $request->boolean('all_sections');
        $sections = $request->input('sections', []);

        $classValue = $grade;
        if ($allSections) {
            $classValue .= '-ALL';
        } elseif (!empty($sections)) {
            $classValue .= '-' . implode(',', array_map('strtoupper', $sections));
        }

        $subject->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'class' => $classValue,
            'access_code' => $request->input('access_code'),
        ]);

        return redirect()->route('guru.dashboard')->with('success', 'Mata pelajaran berhasil diperbarui');
    })->name('subjects.update');

    Route::delete('/subjects/{subject}', function (\Illuminate\Http\Request $request, \App\Models\Subject $subject) {
        $user = Auth::user();

        // Only the teacher who created the subject (or any teacher role) can delete
        if (! $user || ! method_exists($user, 'isTeacher') || ! $user->isTeacher() || $subject->created_by !== $user->id) {
            abort(403, 'Anda tidak berwenang menghapus mata pelajaran ini.');
        }

        // Attempt deletion (foreign keys with cascade should clean related rows)
        try {
            $subject->delete();
            return redirect()->route('guru.dashboard')->with('success', 'Mata pelajaran berhasil dihapus');
        } catch (\Exception $e) {
            \Log::error('Failed to delete subject id=' . $subject->id . ' error=' . $e->getMessage());
            return redirect()->route('guru.dashboard')->with('error', 'Terjadi kesalahan saat menghapus mata pelajaran');
        }
    })->name('subjects.destroy');

    // Modules (real controller)
    Route::get('/modules/create', [TeacherModuleController::class, 'create'])->name('modules.create');
    Route::post('/modules', [TeacherModuleController::class, 'store'])->name('modules.store');

    Route::get('/subject/{subject}/module/{module}/edit', [TeacherModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/subject/{subject}/module/{module}', [TeacherModuleController::class, 'update'])->name('modules.update');
    Route::delete('/subject/{subject}/module/{module}', [TeacherModuleController::class, 'destroy'])->name('modules.destroy');

    // Questions
    Route::get('/questions/create', function () {
        return view('guru.questions.create');
    })->name('questions.create');

    // Import questions (Excel/CSV)
    Route::get('/questions/import', [\App\Http\Controllers\Teacher\QuestionImportController::class, 'create'])->name('questions.import');
    Route::post('/questions/import', [\App\Http\Controllers\Teacher\QuestionImportController::class, 'store'])->name('questions.import.store');
    Route::post('/questions/import/confirm', [\App\Http\Controllers\Teacher\QuestionImportController::class, 'confirm'])->name('questions.import.confirm');

    Route::get('/questions/{question}/edit', function (Question $question) {
        $subjects = Subject::with('modules')->get();
        $modules = Module::all(['id', 'subject_id', 'name']);
        return view('guru.questions.edit', compact('question', 'subjects', 'modules'));
    })->name('questions.edit');

    Route::post('/questions', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'type' => 'required|string',
            'question' => 'required|string',
            'points' => 'required|integer',
            'class' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();

        $submittedType = $request->input('type');

        $question = new \App\Models\Question();
        $question->module_id = $request->input('module_id');
        // If DB enum doesn't include 'mixed' yet, fallback to 'multiple_choice'
        $question->type = $submittedType === 'mixed' ? 'multiple_choice' : $submittedType;
        $question->question = $request->input('question');
        $question->points = $request->input('points');
        // Normalize class input
        $rawClass = $request->input('class');
        if ($rawClass) {
            $normalized = strtoupper(trim((string)$rawClass));
            $normalized = preg_replace('/\s+/', '-', $normalized);
            $normalized = preg_replace('/[^A-Z0-9\-]/', '', $normalized);
            $question->class = $normalized;
        } else {
            $question->class = null;
        }
        $question->created_by = $user->id;

        // Ensure correct_answer always stored as a non-null string
        $question->correct_answer = '';
        $question->options = null;

        // handle options for multiple choice / mixed (use submittedType to detect mixed)
        if (in_array($submittedType, ['multiple_choice', 'mixed'])) {
            $opts = $request->input('options', []);
            $opts = array_values(array_filter($opts, function ($v) {
                return is_string($v) && trim($v) !== '';
            }));
            $question->options = $opts;

            // store the correct answer as the option text (not index) so student-side comparison works
            $correctIndex = $request->input('correct_answer_mc', '');
            if ($correctIndex !== null && $correctIndex !== '' && isset($opts[(int)$correctIndex])) {
                $question->correct_answer = (string) $opts[(int)$correctIndex];
            } else {
                $question->correct_answer = '';
            }
        } elseif ($submittedType === 'true_false') {
            $tf = $request->input('correct_answer_tf', 'true');
            $question->correct_answer = ($tf === 'false' || $tf === '0' || $tf === false) ? 'false' : 'true';
        } else {
            // essay - no structured correct answer; store empty string to satisfy NOT NULL column
            $question->correct_answer = $request->input('correct_answer_essay', '');
            $question->options = null;
        }

        $question->published = true;
        $question->save();

        return redirect()->route('guru.dashboard')->with('success', 'Soal berhasil dibuat');
    })->name('questions.store');

    Route::put('/questions/{question}', function (\Illuminate\Http\Request $request, Question $question) {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'type' => 'required|string',
            'question' => 'required|string',
            'points' => 'required|integer',
            'class' => 'nullable|string|max:255',
        ]);

        $submittedType = $request->input('type');
        if (!in_array($submittedType, ['multiple_choice', 'mixed', 'true_false', 'essay'], true)) {
            $submittedType = 'multiple_choice';
        }

        $question->module_id = $request->input('module_id');
        $question->type = $submittedType === 'mixed' ? 'multiple_choice' : $submittedType;
        $question->question = $request->input('question');
        $question->points = $request->input('points');
        $rawClass = $request->input('class');
        if ($rawClass) {
            $normalized = strtoupper(trim((string)$rawClass));
            $normalized = preg_replace('/\s+/', '-', $normalized);
            $normalized = preg_replace('/[^A-Z0-9\-]/', '', $normalized);
            $question->class = $normalized;
        } else {
            $question->class = null;
        }

        $question->correct_answer = '';
        $question->options = null;

        if (in_array($submittedType, ['multiple_choice', 'mixed'], true)) {
            $opts = $request->input('options', []);
            $opts = array_values(array_filter($opts, function ($v) {
                return is_string($v) && trim($v) !== '';
            }));
            $question->options = $opts;

            $correctIndex = $request->input('correct_answer_mc', '');
            if ($correctIndex !== null && $correctIndex !== '' && isset($opts[(int)$correctIndex])) {
                $question->correct_answer = (string) $opts[(int)$correctIndex];
            } else {
                $question->correct_answer = '';
            }
        } elseif ($submittedType === 'true_false') {
            $tf = $request->input('correct_answer_tf', 'true');
            $question->correct_answer = ($tf === 'false' || $tf === '0' || $tf === false) ? 'false' : 'true';
        } else {
            $question->correct_answer = $request->input('correct_answer_essay', '');
            $question->options = null;
        }

        $question->save();

        return redirect()->route('guru.dashboard')->with('success', 'Soal berhasil diperbarui');
    })->name('questions.update');

    Route::delete('/questions/{question}', function (\Illuminate\Http\Request $request, \App\Models\Question $question) {
        $user = Auth::user();

        // Only the teacher who created the question can delete it
        if (! $user || ! method_exists($user, 'isTeacher') || ! $user->isTeacher() || $question->created_by !== $user->id) {
            abort(403, 'Anda tidak berwenang menghapus soal ini.');
        }

        $question->delete();

        return redirect()->route('guru.dashboard')->with('success', 'Soal berhasil dihapus');
    })->name('questions.destroy');

    // Tasks (Tugas)
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Settings & Progress
    Route::get('/settings', function () {
        return view('guru.settings');
    })->name('settings');

    Route::get('/student-progress/{user}/{subject}', [\App\Http\Controllers\Teacher\StudentProgressController::class, 'show'])->name('student-progress.show');
});
