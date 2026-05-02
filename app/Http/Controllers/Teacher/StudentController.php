<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('guru.students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'phone'            => 'required|string|max:20',
            'date_of_birth'    => 'required|date',
            'class'            => 'required|string|max:50',
            'homeroom_teacher' => 'required|string|max:255',
            'password'         => 'required|string|min:8|regex:/[0-9]/|regex:/[a-zA-Z]/',
        ], [
            'password.regex'   => 'Password harus mengandung minimal satu angka dan satu huruf.',
            'password.min'     => 'Password minimal 8 karakter.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'class' => $validated['class'],
            'homeroom_teacher' => $validated['homeroom_teacher'],
            'password' => Hash::make($validated['password']),
            'role' => 0, // Siswa
        ]);

        return redirect()
            ->route('guru.dashboard')
            ->with('success', "Akun siswa '{$user->name}' berhasil dibuat!");
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(User $student)
    {
        if ($student->role !== 0) {
            return redirect()->route('guru.dashboard')->with('error', 'User ini bukan siswa!');
        }

        return view('guru.students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, User $student)
    {
        if ($student->role !== 0) {
            return redirect()->route('guru.dashboard')->with('error', 'User ini bukan siswa!');
        }

        // Only validate the fields present in the edit form. Email and password are not editable here.
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'date_of_birth'    => 'required|date',
            'class'            => 'required|string|max:50',
            'homeroom_teacher' => 'required|string|max:255',
        ]);

        $student->update($validated);

        return redirect()
            ->route('guru.dashboard')
            ->with('success', "Data siswa '{$student->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(User $student)
    {
        if ($student->role !== 0) {
            return redirect()->route('guru.dashboard')->with('error', 'User ini bukan siswa!');
        }

        $studentName = $student->name;
        $student->delete();

        return redirect()
            ->route('guru.dashboard')
            ->with('success', "Akun siswa '{$studentName}' berhasil dihapus!");
    }

    /**
     * Show list of students created by this teacher.
     */
    public function index()
    {
        $students = User::where('role', 0)
            ->orderBy('class')
            ->orderBy('name')
            ->paginate(15);

        return view('guru.students.index', compact('students'));
    }
}
