<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\StudentProgress;
use App\Models\SubjectEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuestionAnswer;
use App\Models\TeacherNote;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $studentClass = trim($user->class ?? '');

        $subjectsQuery = Subject::query();

        if ($studentClass !== '') {
            $classParts = explode('-', $studentClass, 2);
            $grade = $classParts[0];
            $section = $classParts[1] ?? null;

            $subjectsQuery->where(function ($query) use ($studentClass, $grade, $section) {
                $query->where('class', $studentClass)
                    ->orWhere('class', $grade)
                    ->orWhere('class', $grade . '-ALL');

                if ($section) {
                    $query->orWhere('class', 'LIKE', $grade . '-' . $section . '%');
                }
            });
        } else {
            $subjectsQuery->whereNull('class');
        }

        $subjects = $subjectsQuery->get();

        $enrolledSubjectIds = SubjectEnrollment::where('user_id', $user->id)
            ->pluck('subject_id')
            ->toArray();

        $totalSubjects = $subjects->count();
        $totalModules = $subjects->sum(fn($s) => $s->publishedModules()->count());

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
            'enrolledSubjectIds' => $enrolledSubjectIds,
            'totalSubjects' => $totalSubjects,
            'totalModules' => $totalModules,
            'completedSubjects' => $completedSubjects,
            'averageProgress' => $averageProgress,
            'gradedAnswers' => $gradedAnswers,
            'teacherNotes' => $teacherNotes,
        ]);
    }
}
