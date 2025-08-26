<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Assignment,AssignmentEnrollment,Student};
use Illuminate\Http\Request;

class AssignmentEnrollmentController extends Controller
{
    public function index(Assignment $assignment)
    {
        $assignment->load('lesson','classroom');
        $enrollments = AssignmentEnrollment::with('student.classroom')->where('assignment_id',$assignment->id)->paginate(10);
        return inertia('Admin/Assignments/Enrollments/Index',[ 'assignment'=>$assignment, 'enrollments'=>$enrollments ]);
    }
    public function create(Assignment $assignment)
    {
        $assignment->load('classroom');
        $enrolledIds = AssignmentEnrollment::where('assignment_id',$assignment->id)->pluck('student_id')->all();
        $students = Student::with('classroom')->where('classroom_id',$assignment->classroom_id)->whereNotIn('id',$enrolledIds)->get();
        return inertia('Admin/Assignments/Enrollments/Create',[ 'assignment'=>$assignment, 'students'=>$students ]);
    }
    public function store(Request $request, Assignment $assignment)
    {
        $data = $request->validate(['student_id'=>'required|array']);
        foreach($data['student_id'] as $sid){ AssignmentEnrollment::firstOrCreate(['assignment_id'=>$assignment->id,'student_id'=>$sid]); }
        return redirect()->route('admin.assignments.enrollments.index',$assignment->id);
    }
    public function destroy(Assignment $assignment, AssignmentEnrollment $enrollment)
    {
        $enrollment->delete();
        return back();
    }
}
