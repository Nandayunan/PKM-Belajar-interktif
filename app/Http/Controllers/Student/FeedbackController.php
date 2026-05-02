<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Show feedback form after completing module
     */
    public function create(StudentProgress $progress)
    {
        $student = Auth::user();

        // Verify this is the student's progress record
        if ($progress->user_id !== $student->id) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Akses ditolak');
        }

        // Only show form if not yet submitted
        if ($progress->feedback_submitted_at !== null) {
            return redirect()->route('siswa.modules.show', [$progress->subject_id, $progress->module_id])
                ->with('info', 'Anda sudah mengirimkan feedback untuk modul ini');
        }

        return view('siswa.feedback', compact('progress'));
    }

    /**
     * Store feedback
     */
    public function store(Request $request, StudentProgress $progress)
    {
        $student = Auth::user();

        // Verify this is the student's progress record
        if ($progress->user_id !== $student->id) {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Akses ditolak');
        }

        $validated = $request->validate([
            'student_feedback' => 'required|string|max:1000',
            'student_feelings' => 'required|string|max:500',
        ]);

        $progress->update([
            'student_feedback' => $validated['student_feedback'],
            'student_feelings' => $validated['student_feelings'],
            'feedback_submitted_at' => now(),
        ]);

        // Prepare a review link the student can use from dashboard to review their answers
        $reviewLink = route('siswa.modules.review', [$progress->subject_id, $progress->module_id]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Terima kasih! Feedback Anda telah disimpan.')
            ->with('review_link', $reviewLink);
    }
}
