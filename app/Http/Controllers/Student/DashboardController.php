<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionAnswer;
use App\Models\TeacherNote;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subjects = Subject::all();

        $totalSubjects = $subjects->count();
        $totalModules = $subjects->sum(fn($s) => $s->modules->count());

        $completedSubjects = StudentProgress::where('user_id', $user->id)
            ->where('status', 'completed')
            ->distinct('subject_id')
            ->count();

        // Average over subject-level progress only (module_id is null) to avoid dilution by module rows
        $averageProgress = StudentProgress::where('user_id', $user->id)
            ->whereNull('module_id')
            ->average('percentage') ?? 0;

        // Recent graded answers by teachers for this student
        $gradedAnswers = QuestionAnswer::where('user_id', $user->id)
            ->whereNotNull('teacher_score')
            ->with('question', 'question.module')
            ->orderBy('graded_at', 'desc')
            ->limit(10)
            ->get();

        // Recent teacher notes targeting this student
        $teacherNotes = TeacherNote::where('user_id', $user->id)
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('siswa.dashboard', [
            'subjects' => $subjects,
            'totalSubjects' => $totalSubjects,
            'totalModules' => $totalModules,
            'completedSubjects' => $completedSubjects,
            'averageProgress' => $averageProgress,
            'gradedAnswers' => $gradedAnswers,
            'teacherNotes' => $teacherNotes,
        ]);
    }
}
