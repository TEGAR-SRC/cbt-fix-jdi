<?php
namespace App\Http\Controllers\Teacher;
use App\Http\Controllers\Controller;
use App\Models\{Assignment,AssignmentSubmission,AssignmentAnswer};
use Illuminate\Http\Request;

class AssignmentResultController extends Controller
{
    public function index(Assignment $assignment, Request $request)
    {
        $subs = AssignmentSubmission::with('student')
            ->where('assignment_id',$assignment->id)
            ->when($request->q, fn($q,$term)=>$q->whereHas('student', fn($s)=>$s->where('name','like',"%$term%")))
            ->orderByDesc('finished_at')
            ->paginate(20)
            ->withQueryString();
        return inertia('Teacher/Assignments/Results/Index',[ 'assignment'=>$assignment, 'submissions'=>$subs, 'filters'=>['q'=>$request->q] ]);
    }
    public function show(Assignment $assignment, AssignmentSubmission $submission)
    {
        abort_unless($submission->assignment_id == $assignment->id,404);
        $submission->load('student');
        $answers = AssignmentAnswer::with('question')->where('submission_id',$submission->id)->get();
        return inertia('Teacher/Assignments/Results/Show',[ 'assignment'=>$assignment, 'submission'=>$submission, 'answers'=>$answers ]);
    }
    public function forceFinish(Assignment $assignment, AssignmentSubmission $submission)
    {
        abort_unless($submission->assignment_id == $assignment->id,404);
        if(!$submission->finished_at){
            $submission->finished_at = now();
            $total = $submission->total_questions ?: $assignment->questions()->count();
            $correct = $submission->answers()->where('is_correct',true)->count();
            $submission->total_questions = $total;
            $submission->total_correct = $correct;
            $submission->score = $total ? round(($correct/$total)*100,2) : 0;
            $submission->save();
        }
        return back()->with('success','Tugas harian dihentikan & diselesaikan.');
    }
    public function reopen(Assignment $assignment, AssignmentSubmission $submission)
    {
        abort_unless($submission->assignment_id == $assignment->id,404);
        if($submission->finished_at){
            $submission->finished_at = null;
            $submission->save();
        }
        return back()->with('success','Tugas harian dibuka kembali.');
    }
}
