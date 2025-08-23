<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $admins = User::whereIn('role', ['admin','operator','teacher','dinas'])
            ->when(request()->q, function ($query) {
            $q = request()->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        })->latest()->paginate(10);

        $admins->appends(['q' => request()->q]);

        return inertia('Admin/Admins/Index', [
            'admins' => $admins,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Admin/Admins/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'nullable|in:admin,operator,teacher,dinas',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // hashed by cast in User model
            'role' => $request->input('role', 'admin'),
        ]);

        return redirect()->route('admin.admins.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        return inertia('Admin/Admins/Edit', [
            'admin' => $admin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|in:admin,operator,teacher,dinas',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password; // hashed by cast
        }

        if ($request->filled('role')) {
            $data['role'] = $request->role;
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        // prevent self-delete optionally
        if (auth()->id() === $admin->id) {
            return back();
        }

        $admin->delete();

        return redirect()->route('admin.admins.index');
    }
}
