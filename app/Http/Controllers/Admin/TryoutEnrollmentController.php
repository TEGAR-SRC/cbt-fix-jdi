<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Tryout,TryoutEnrollment,Student};
use Illuminate\Http\Request;

class TryoutEnrollmentController extends Controller
{
    public function index(Tryout $tryout)
    {
        $tryout->load('lesson','classroom');
        $enrollments = TryoutEnrollment::with('student.classroom')->where('tryout_id',$tryout->id)->paginate(10);
        return inertia('Admin/Tryouts/Enrollments/Index',[ 'tryout'=>$tryout, 'enrollments'=>$enrollments ]);
    }
    public function create(Tryout $tryout)
    {
        $tryout->load('classroom');
        $enrolledIds = TryoutEnrollment::where('tryout_id',$tryout->id)->pluck('student_id')->all();
        $students = Student::with('classroom')->where('classroom_id',$tryout->classroom_id)->whereNotIn('id',$enrolledIds)->get();
        return inertia('Admin/Tryouts/Enrollments/Create',[ 'tryout'=>$tryout, 'students'=>$students ]);
    }
    public function store(Request $request, Tryout $tryout)
    {
        $data = $request->validate(['student_id'=>'required|array']);
        foreach($data['student_id'] as $sid){ TryoutEnrollment::firstOrCreate(['tryout_id'=>$tryout->id,'student_id'=>$sid]); }
        return redirect()->route('admin.tryouts.enrollments.index',$tryout->id);
    }
    public function destroy(Tryout $tryout, TryoutEnrollment $enrollment)
    {
        $enrollment->delete();
        return back();
    }
}
