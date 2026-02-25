<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:0,1',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => (int) $validated['role'],
        ]);

        event(new Registered($user));

        Auth::login($user);

        /** @var User $user */
        $user = Auth::user();
        if ($user->isTeacher()) {
            return redirect()->route('guru.dashboard')->with('success', 'Akun Anda berhasil dibuat!');
        } else {
            return redirect()->route('siswa.dashboard')->with('success', 'Akun Anda berhasil dibuat!');
        }
    }
}
