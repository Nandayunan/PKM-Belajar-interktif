<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Module;
use App\Models\Question;
use App\Models\StudentProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionAnswer;
use App\Models\TeacherNote;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $subjects = Subject::where('created_by', $user->id)->get();
        $modules = Module::whereIn('subject_id', $subjects->pluck('id'))->get();
        $questions = Question::whereIn('module_id', $modules->pluck('id'))->get();

        $totalSubjects = $subjects->count();
        $totalModules = $modules->count();
        $totalQuestions = $questions->count();
        $totalStudents = User::where('role', 0)->count();

        $studentProgress = StudentProgress::whereIn('subject_id', $subjects->pluck('id'))
            ->with('user', 'subject')
            ->get();

        // Fetch recent submissions (answers) for questions created by this teacher
        $submissions = QuestionAnswer::whereHas('question', function ($q) use ($user) {
            $q->where('created_by', $user->id);
        })
            ->with('user', 'question', 'question.module')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($a) => $a->user_id);

        // Fetch all students (untuk manajemen siswa)
        $students = User::where('role', 0)->paginate(15);

        // Fetch teacher notes grouped by user
        $teacherNotes = TeacherNote::where('teacher_id', $user->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn($n) => $n->user_id);

        return view('guru.dashboard', [
            'subjects' => $subjects,
            'modules' => $modules,
            'questions' => $questions,
            'studentProgress' => $studentProgress,
            'students' => $students,
            'submissions' => $submissions,
            'teacherNotes' => $teacherNotes,
            'totalSubjects' => $totalSubjects,
            'totalModules' => $totalModules,
            'totalQuestions' => $totalQuestions,
            'totalStudents' => $totalStudents,
        ]);
    }

    /**
     * Store a teacher note for a student (optional subject/module scoped)
     */
    public function storeNote(Request $request)
    {
        $teacher = Auth::user();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'module_id' => 'nullable|exists:modules,id',
            'note' => 'required|string|max:2000',
        ]);

        TeacherNote::create([
            'teacher_id' => $teacher->id,
            'user_id' => $validated['user_id'],
            'subject_id' => $validated['subject_id'] ?? null,
            'module_id' => $validated['module_id'] ?? null,
            'note' => $validated['note'],
        ]);

        return back()->with('success', 'Catatan berhasil disimpan untuk siswa');
    }
}
