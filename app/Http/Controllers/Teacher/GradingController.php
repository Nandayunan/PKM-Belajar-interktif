<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\QuestionAnswer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradingController extends Controller
{
    /**
     * Show list of pending essay answers to grade
     */
    public function index()
    {
        $teacher = Auth::user();

        // Get all essay/mixed questions created by this teacher
        $pendingAnswers = QuestionAnswer::whereHas('question', function ($query) use ($teacher) {
            $query->where('created_by', $teacher->id)
                ->whereIn('type', ['essay', 'mixed']);
        })
            ->where('teacher_score', null)  // Not yet graded
            ->with('user', 'question', 'question.module')
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('guru.grading.index', compact('pendingAnswers'));
    }

    /**
     * Show the grading form for a specific answer
     */
    public function show(QuestionAnswer $answer)
    {
        $teacher = Auth::user();
        $question = $answer->question;

        // Verify that the question was created by this teacher
        if ($question->created_by !== $teacher->id) {
            return redirect()->route('guru.grading.index')
                ->with('error', 'Anda tidak memiliki akses untuk menilai soal ini');
        }

        // Only allow grading essay/mixed questions that haven't been graded
        if (!in_array($question->type, ['essay', 'mixed']) || $answer->teacher_score !== null) {
            return redirect()->route('guru.grading.index')
                ->with('error', 'Soal ini tidak perlu dinilai atau sudah dinilai');
        }

        return view('guru.grading.show', compact('answer', 'question'));
    }

    /**
     * Store the grading result
     */
    public function store(Request $request, QuestionAnswer $answer)
    {
        $teacher = Auth::user();
        $question = $answer->question;

        // Verify authorization
        if ($question->created_by !== $teacher->id) {
            return redirect()->route('guru.grading.index')
                ->with('error', 'Anda tidak memiliki akses untuk menilai soal ini');
        }

        $validated = $request->validate([
            'teacher_score' => 'required|integer|min:0|max:' . $question->points,
            'teacher_feedback' => 'required|string|max:1000',
        ]);

        $answer->update([
            'teacher_score' => $validated['teacher_score'],
            'teacher_feedback' => $validated['teacher_feedback'],
            'graded_at' => now(),
        ]);

        // If it's a mixed question, calculate is_correct based on teacher score
        if ($question->type === 'mixed') {
            $answer->update([
                'is_correct' => $validated['teacher_score'] >= ($question->points / 2), // Passed if >= 50%
            ]);
        }

        return redirect()->route('guru.grading.index')
            ->with('success', "Soal dari {$answer->user->name} berhasil dinilai!");
    }

    /**
     * Show previously graded answers
     */
    public function graded()
    {
        $teacher = Auth::user();

        $gradedAnswers = QuestionAnswer::whereHas('question', function ($query) use ($teacher) {
            $query->where('created_by', $teacher->id)
                ->whereIn('type', ['essay', 'mixed']);
        })
            ->whereNotNull('teacher_score')
            ->with('user', 'question', 'question.module')
            ->orderBy('graded_at', 'desc')
            ->paginate(15);

        return view('guru.grading.graded', compact('gradedAnswers'));
    }
}
