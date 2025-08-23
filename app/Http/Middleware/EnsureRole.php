<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureRole
{
    /**
     * Handle an incoming request.
     * Usage in routes: [EnsureRole::class.':admin'] or ':teacher'
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();
        if (!$user) {
            // Not authenticated: send to login
            return redirect('/login');
        }

        // If role doesn't match, redirect to that user's dashboard instead of 403
        if (($user->role ?? null) !== $role) {
            if (($user->role ?? null) === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if (($user->role ?? null) === 'teacher') {
                return redirect()->route('teacher.dashboard');
            }
            // Unknown role: logout or login
            return redirect('/login');
        }

        return $next($request);
    }
}
