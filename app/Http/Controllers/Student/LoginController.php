<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //validate the form data
        $request->validate([
            'nisn'      => 'required',
            'password'  => 'required',
        ]);

        //cek nisn dan password
        $student = Student::where([
            'nisn'      => $request->nisn,
            'password'  => $request->password
        ])->first();

        if(!$student) {
            return redirect()->back()->with('error', 'NISN atau Password salah');
        }
        
    // single-login: generate a unique session id and store on student
    $sessionId = bin2hex(random_bytes(16));
    $student->current_session_id = $sessionId;
    $student->save();

    //login the user and store session id
    auth()->guard('student')->login($student);
    session(['student_session_id' => $sessionId]);

        //redirect to dashboard
        return redirect()->route('student.dashboard');
    }
}