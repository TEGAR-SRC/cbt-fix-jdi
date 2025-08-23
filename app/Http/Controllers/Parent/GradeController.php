<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Guardian;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        // If logged in as parent, auto-load linked students and grades
        if (auth()->check() && (auth()->user()->role ?? null) === 'parent') {
            $guardian = Guardian::with(['students.classroom'])
                ->where('user_id', auth()->id())
                ->first();

            $students = [];
            $grades = [];
            if ($guardian) {
                $students = $guardian->students->map(function($s){
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'classroom' => $s->classroom,
                    ];
                })->values()->all();

                $studentIds = $guardian->students->pluck('id');
                if ($studentIds->isNotEmpty()) {
                    $grades = Grade::with('exam.lesson','exam.classroom','exam_session','student.classroom')
                        ->whereIn('student_id', $studentIds)
                        ->latest('id')
                        ->get();
                }
            }

            return inertia('Parent/Grades/Index', [
                'students' => $students,
                'grades' => $grades,
                'selected_student' => null,
                'is_parent' => true,
            ]);
        }

        // Default: landing screen asks for NIS (and optional name) to lookup
        return inertia('Parent/Grades/Index', [
            'students' => [],
            'grades' => [],
            'selected_student' => null,
            'is_parent' => false,
        ]);
    }

    public function filter(Request $request)
    {
        $request->validate([
            'nisn' => 'required|digits_between:6,20',
            'name' => 'nullable|string',
        ]);

        $student = Student::with('classroom')
            ->when($request->name, function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->name.'%');
            })
            ->where('nisn', $request->nisn)
            ->first();

        $grades = [];
        if ($student) {
            $grades = Grade::with('exam.lesson','exam.classroom','exam_session','student.classroom')
                ->where('student_id', $student->id)
                ->latest('id')
                ->get();
        }

        return inertia('Parent/Grades/Index', [
            'students' => $student ? [[
                'id' => $student->id,
                'name' => $student->name,
                'classroom' => $student->classroom,
            ]] : [],
            'grades' => $grades,
            'selected_student' => $student?->id,
            'not_found' => $student ? null : 'Siswa dengan NIS tersebut tidak ditemukan',
        ]);
    }
}
