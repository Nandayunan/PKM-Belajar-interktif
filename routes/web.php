<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ModuleController;
use App\Http\Controllers\Student\FeedbackController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Teacher\StudentController;
use App\Http\Controllers\Teacher\GradingController;
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
});

// Student Routes
Route::middleware(['auth', 'student'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::prefix('/subject/{subject}')->group(function () {
        Route::get('/modules', [ModuleController::class, 'index'])->name('modules.index');

        Route::prefix('/module/{module}')->group(function () {
            Route::get('/', [ModuleController::class, 'show'])->name('modules.show');
            Route::post('/submit-answer', [ModuleController::class, 'submitAnswer'])->name('modules.submit-answer');
            Route::get('/review', [ModuleController::class, 'review'])->name('modules.review');
        });
    });

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
    Route::resource('students', StudentController::class)->only(['create', 'store', 'edit', 'update', 'destroy', 'index']);

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

    Route::get('/subjects/{subject}', function () {
        return view('guru.subjects.show');
    })->name('subjects.show');

    Route::get('/subjects/{subject}/edit', function () {
        return view('guru.subjects.edit');
    })->name('subjects.edit');

    Route::post('/subjects', function () {
        return redirect()->route('guru.dashboard');
    })->name('subjects.store');

    Route::put('/subjects/{subject}', function () {
        return redirect()->route('guru.dashboard');
    })->name('subjects.update');

    Route::delete('/subjects/{subject}', function () {
        return redirect()->route('guru.dashboard');
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

    Route::get('/questions/{question}/edit', function () {
        return view('guru.questions.edit');
    })->name('questions.edit');

    Route::post('/questions', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'type' => 'required|string',
            'question' => 'required|string',
            'points' => 'required|integer',
        ]);

        $user = Auth::user();

        $submittedType = $request->input('type');

        $question = new \App\Models\Question();
        $question->module_id = $request->input('module_id');
        // If DB enum doesn't include 'mixed' yet, fallback to 'multiple_choice'
        $question->type = $submittedType === 'mixed' ? 'multiple_choice' : $submittedType;
        $question->question = $request->input('question');
        $question->points = $request->input('points');
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

    Route::put('/questions/{question}', function () {
        return redirect()->route('guru.dashboard');
    })->name('questions.update');

    Route::delete('/questions/{question}', function () {
        return redirect()->route('guru.dashboard');
    })->name('questions.destroy');

    // Settings & Progress
    Route::get('/settings', function () {
        return view('guru.settings');
    })->name('settings');

    Route::get('/student-progress/{user}/{subject}', function () {
        return view('guru.student-progress.show');
    })->name('student-progress.show');
});
