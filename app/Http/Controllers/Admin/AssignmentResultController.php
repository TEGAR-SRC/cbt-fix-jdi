<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Assignment,AssignmentSubmission,AssignmentAnswer,Student};
use Illuminate\Http\Request;

class AssignmentResultController extends Controller
{
    public function index(Assignment $assignment, Request $request)
    {
        $this->authorizeView();
        $submissions = AssignmentSubmission::with('student')
            ->where('assignment_id',$assignment->id)
            ->when($request->q, function($q,$term){
                $q->whereHas('student', fn($s)=>$s->where('name','like',"%$term%"));
            })
            ->orderByDesc('finished_at')
            ->paginate(20)
            ->withQueryString();
        return inertia('Admin/Assignments/Results/Index',[ 'assignment'=>$assignment, 'submissions'=>$submissions, 'filters'=>['q'=>$request->q] ]);
    }
    public function show(Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->authorizeView();
        abort_unless($submission->assignment_id == $assignment->id,404);
        $submission->load('student');
        $answers = AssignmentAnswer::with('question')->where('submission_id',$submission->id)->get();
        return inertia('Admin/Assignments/Results/Show',[ 'assignment'=>$assignment, 'submission'=>$submission, 'answers'=>$answers ]);
    }
    protected function authorizeView(){ /* placeholder for role checks if needed later */ }
}
