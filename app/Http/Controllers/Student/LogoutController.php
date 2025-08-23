<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $student = auth()->guard('student')->user();
        if ($student) {
            // mark in-progress exams as exited
            Grade::where('student_id', $student->id)
                ->whereNull('end_time')
                ->update(['status' => 'exited']);

            auth()->guard('student')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/beranda');
    }
}
