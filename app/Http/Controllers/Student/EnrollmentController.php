<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\SubjectEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Request $request, $subjectId)
    {
        $subject = Subject::findOrFail($subjectId);
        $user = Auth::user();

        // Only students can enroll
        if (!$user->isStudent()) {
            abort(403);
        }

        // If subject has an access code, require it
        if ($subject->access_code) {
            $inputCode = $request->input('access_code', '');
            if (!hash_equals((string)$subject->access_code, (string)$inputCode)) {
                return back()->with('error', 'Kode akses salah. Masukkan kode yang diberikan guru.');
            }
        }

        SubjectEnrollment::firstOrCreate([
            'user_id' => $user->id,
            'subject_id' => $subject->id,
        ]);

        return redirect()->route('siswa.modules.index', $subject->id)
            ->with('success', "Berhasil mendaftar ke mata pelajaran {$subject->name}. Anda sekarang bisa membuka modul dan kuis.");
    }
}
