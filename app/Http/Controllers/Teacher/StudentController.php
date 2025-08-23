<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::when(request()->q, function ($students) {
            $students = $students->where('name', 'like', '%' . request()->q . '%');
        })->with('classroom')->latest()->paginate(5);

        $students->appends(['q' => request()->q]);

        return inertia('Teacher/Students/Index', [
            'students' => $students,
        ]);
    }

    public function create()
    {
        $classrooms = Classroom::all();
        return inertia('Teacher/Students/Create', [
            'classrooms' => $classrooms,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|unique:students',
            'gender' => 'required|string',
            'password' => 'required|confirmed',
            'classroom_id' => 'required'
        ]);

        Student::create([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'gender' => $request->gender,
            'password' => $request->password,
            'classroom_id' => $request->classroom_id
        ]);

    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.students.index');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classrooms = Classroom::all();
        return inertia('Teacher/Students/Edit', [
            'student' => $student,
            'classrooms' => $classrooms,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|unique:students,nisn,' . $student->id,
            'gender' => 'required|string',
            'classroom_id' => 'required',
            'password' => 'confirmed'
        ]);

        if ($request->password == "") {
            $student->update([
                'name' => $request->name,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'classroom_id' => $request->classroom_id
            ]);
        } else {
            $student->update([
                'name' => $request->name,
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'password' => $request->password,
                'classroom_id' => $request->classroom_id
            ]);
        }

    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.students.index');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.students.index');
    }

    public function import()
    {
        return inertia('Teacher/Students/Import');
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        Excel::import(new StudentsImport(), $request->file('file'));

        return redirect()->route('teacher.students.index');
    }

    public function export()
    {
        $students = Student::with('classroom')
            ->when(request()->q, fn($q)=>$q->where('name','like','%'.request()->q.'%'))
            ->get();
        return \Maatwebsite\Excel\Facades\Excel::download(new StudentsExport($students), 'students.xlsx');
    }
}
