<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IsStudent
{
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user && $user->isStudent()) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Anda tidak memiliki akses sebagai siswa.');
    }
}
