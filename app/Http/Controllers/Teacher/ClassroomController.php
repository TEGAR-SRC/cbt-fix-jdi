<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::when(request()->q, function ($classrooms) {
            $classrooms = $classrooms->where('title', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        $classrooms->appends(['q' => request()->q]);

        return inertia('Teacher/Classrooms/Index', [
            'classrooms' => $classrooms,
        ]);
    }

    public function create()
    {
        return inertia('Teacher/Classrooms/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:classrooms'
        ]);

        Classroom::create([
            'title' => $request->title,
        ]);

    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.classrooms.index');
    }

    public function edit($id)
    {
        $classroom = Classroom::findOrFail($id);
        return inertia('Teacher/Classrooms/Edit', [
            'classroom' => $classroom,
        ]);
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'title' => 'required|string|unique:classrooms,title,' . $classroom->id,
        ]);

        $classroom->update([
            'title' => $request->title,
        ]);

    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.classrooms.index');
    }

    public function destroy($id)
    {
        $classroom = Classroom::findOrFail($id);
        $classroom->delete();
        return redirect()->route('teacher.classrooms.index');
    }
}
