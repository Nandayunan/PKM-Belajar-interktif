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

        return view('guru.dashboard', [
            'subjects' => $subjects,
            'modules' => $modules,
            'questions' => $questions,
            'studentProgress' => $studentProgress,
            'totalSubjects' => $totalSubjects,
            'totalModules' => $totalModules,
            'totalQuestions' => $totalQuestions,
            'totalStudents' => $totalStudents,
        ]);
    }
}
