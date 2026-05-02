<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Module;
use App\Models\Question;
use App\Models\StudentProgress;

class StudentProgressController extends Controller
{
    /**
     * Show student progress for a subject (per module)
     */
    public function show($userId, $subjectId)
    {
        $student = User::find($userId);
        if (!$student) {
            abort(404, 'Siswa tidak ditemukan');
        }

        $subject = Subject::with('modules')->find($subjectId);
        if (!$subject) {
            abort(404, 'Mata pelajaran tidak ditemukan');
        }

        $modules = $subject->modules()->orderBy('id')->get();

        $rows = [];
        $totals = [
            'total_questions' => 0,
            'answered_questions' => 0,
            'correct_answers' => 0,
            'total_points' => 0,
            'earned_points' => 0,
        ];

        foreach ($modules as $module) {
            $qCount = Question::where('module_id', $module->id)->count();

            $progress = StudentProgress::where('user_id', $student->id)
                ->where('module_id', $module->id)
                ->where('subject_id', $subject->id)
                ->first();

            $answered = $progress ? (int)$progress->answered_questions : 0;
            $correct = $progress ? (int)$progress->correct_answers : 0;
            $totalPoints = $progress ? (int)$progress->total_points : 0;
            $earned = $progress ? (int)$progress->earned_points : 0;
            $percentage = $progress ? (float)$progress->percentage : ($qCount ? round(($correct / max(1, $qCount)) * 100, 2) : 0);
            $status = $progress ? ($progress->status ?: 'incomplete') : 'incomplete';

            $rows[] = [
                'module' => $module,
                'total_questions' => $qCount,
                'answered_questions' => $answered,
                'correct_answers' => $correct,
                'total_points' => $totalPoints,
                'earned_points' => $earned,
                'percentage' => $percentage,
                'status' => $status,
            ];

            $totals['total_questions'] += $qCount;
            $totals['answered_questions'] += $answered;
            $totals['correct_answers'] += $correct;
            $totals['total_points'] += $totalPoints;
            $totals['earned_points'] += $earned;
        }

        // compute overall percentage safely
        $overallPercentage = 0;
        if ($totals['total_questions'] > 0) {
            $overallPercentage = round(($totals['correct_answers'] / max(1, $totals['total_questions'])) * 100, 2);
        }

        return view('guru.student-progress.show', [
            'student' => $student,
            'subject' => $subject,
            'rows' => $rows,
            'totals' => $totals,
            'overallPercentage' => $overallPercentage,
        ]);
    }
}
