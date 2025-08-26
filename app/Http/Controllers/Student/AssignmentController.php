<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->guard('student')->user();
        $query = Assignment::with('lesson','classroom')
            ->where('classroom_id', $student->classroom_id)
            ->whereNotNull('published_at');

        if ($search = $request->get('q')) {
            $query->where('title','like',"%$search%");
        }

        $assignments = $query->orderByDesc('published_at')->get();
        return inertia('Student/Assignments/Index', [
            'assignments' => $assignments,
            'filters' => [ 'q' => $request->get('q') ]
        ]);
    }
}
