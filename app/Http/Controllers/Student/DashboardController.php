<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $averageProgress = StudentProgress::where('user_id', $user->id)
            ->average('percentage') ?? 0;

        return view('siswa.dashboard', [
            'subjects' => $subjects,
            'totalSubjects' => $totalSubjects,
            'totalModules' => $totalModules,
            'completedSubjects' => $completedSubjects,
            'averageProgress' => $averageProgress,
        ]);
    }
}
