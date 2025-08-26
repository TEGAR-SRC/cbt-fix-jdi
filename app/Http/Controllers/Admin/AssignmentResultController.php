<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Assignment,AssignmentSubmission,AssignmentAnswer,Student};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

    // Force stop an ongoing submission (mark finished now)
    public function forceFinish(Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->authorizeView();
        abort_unless($submission->assignment_id == $assignment->id,404);
        if(!$submission->finished_at){
            $submission->finished_at = now();
            // Recalculate score (in case some answers missing)
            $total = $submission->total_questions ?: $assignment->questions()->count();
            $correct = $submission->answers()->where('is_correct',true)->count();
            $submission->total_questions = $total;
            $submission->total_correct = $correct;
            $submission->score = $total ? round(($correct/$total)*100,2) : 0;
            $submission->save();
        }
        return back()->with('success','Tugas harian dihentikan & diselesaikan.');
    }

    // Reopen a finished submission (allow student to continue)
    public function reopen(Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->authorizeView();
        abort_unless($submission->assignment_id == $assignment->id,404);
        if($submission->finished_at){
            $submission->finished_at = null;
            $submission->save();
        }
        return back()->with('success','Tugas harian dibuka kembali.');
    }
}
