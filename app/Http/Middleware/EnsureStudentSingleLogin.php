<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureStudentSingleLogin
{
    public function handle(Request $request, Closure $next)
    {
        $student = auth()->guard('student')->user();
        if ($student) {
            $current = $student->current_session_id;
            $sessionId = session('student_session_id');
            if ($current && $sessionId && $current !== $sessionId) {
                // invalidate session and redirect to login with message
                auth()->guard('student')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/')->with('error', 'Akun sedang digunakan di perangkat lain.');
            }
        }
        return $next($request);
    }
}
