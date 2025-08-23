<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user && ($user->role ?? null) === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user && ($user->role ?? null) === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        // Fallback: if student guard is used elsewhere, keep original behavior
        return redirect('/');
    }
}
