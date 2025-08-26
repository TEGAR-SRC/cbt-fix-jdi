<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
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
    public function confirmation(Assignment $assignment)
    {
        $student = auth()->guard('student')->user();
        abort_unless($assignment->classroom_id === $student->classroom_id, 403);
        $assignment->loadCount('questions');
        $submission = AssignmentSubmission::where('assignment_id',$assignment->id)->where('student_id',$student->id)->first();
        return inertia('Student/Assignments/Confirmation', [ 'assignment'=>$assignment, 'submission'=>$submission ]);
    }
}
