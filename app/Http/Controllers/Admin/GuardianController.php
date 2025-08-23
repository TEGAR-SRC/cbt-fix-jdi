<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;

class GuardianController extends Controller
{
    public function index()
    {
        $guardians = Guardian::withCount('students')
            ->when(request('q'), fn($q) => $q->where('name','like','%'.request('q').'%'))
            ->latest('id')->paginate(10)->appends(['q'=>request('q')]);

        return inertia('Admin/Guardians/Index', compact('guardians'));
    }

    public function create()
    {
        return inertia('Admin/Guardians/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:120',
        ]);
        Guardian::create($data);
        return redirect()->route('admin.guardians.index');
    }

    public function edit(Guardian $guardian)
    {
    $students = Student::with('classroom')->orderBy('name')->get(['id','name','classroom_id']);
    $guardian->load('students','user');
        return inertia('Admin/Guardians/Edit', compact('guardian','students'));
    }

    public function update(Request $request, Guardian $guardian)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:120',
            'student_ids' => 'array',
            'student_ids.*' => 'integer|exists:students,id',
        ]);

        $guardian->update($data);
        if ($request->has('student_ids')) {
            $guardian->students()->sync($request->student_ids);
        }
        return redirect()->route('admin.guardians.index');
    }

    public function createAccount(Request $request, Guardian $guardian)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'parent',
        ]);
        $guardian->update(['user_id' => $user->id]);
        return back();
    }

    public function updateAccount(Request $request, Guardian $guardian)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email,'.optional($guardian->user)->id,
            'password' => 'nullable|string|min:6',
        ]);
        $user = $guardian->user;
        if (!$user) { return back()->withErrors(['user' => 'Akun belum ada']); }
        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) { $user->password = $data['password']; }
        $user->role = 'parent';
        $user->save();
        return back();
    }

    public function deleteAccount(Guardian $guardian)
    {
        if ($guardian->user) { $guardian->user->delete(); }
        $guardian->update(['user_id' => null]);
        return back();
    }

    public function destroy(Guardian $guardian)
    {
        $guardian->delete();
        return redirect()->route('admin.guardians.index');
    }
}
