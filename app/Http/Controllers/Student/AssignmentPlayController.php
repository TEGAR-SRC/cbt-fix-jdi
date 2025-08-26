<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\{Assignment,AssignmentSubmission,AssignmentQuestion,AssignmentAnswer};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AssignmentPlayController extends Controller
{
    public function start(Assignment $assignment)
    {
        $student = auth()->guard('student')->user();
        abort_unless($assignment->classroom_id === $student->classroom_id, 403);
        $submission = AssignmentSubmission::firstOrCreate([
            'assignment_id'=>$assignment->id,
            'student_id'=>$student->id,
        ],[
            'started_at'=>now(),
            'total_questions'=>$assignment->questions()->count(),
        ]);
        if(!$submission->started_at){ $submission->started_at = now(); $submission->save(); }
        // redirect to per-question view (page 1)
        return redirect()->route('student.assignments.show', [$assignment->id, 1]);
    }
    public function show(Assignment $assignment, int $page)
    {
        $student = auth()->guard('student')->user();
        abort_unless($assignment->classroom_id === $student->classroom_id, 403);
        $submission = AssignmentSubmission::where('assignment_id',$assignment->id)->where('student_id',$student->id)->first();
        if(!$submission){
            return redirect()->route('student.assignments.start',$assignment->id);
        }
        $allQuestions = $assignment->questions()->orderBy('order')->get();
        $answers = AssignmentAnswer::where('submission_id',$submission->id)->get()->keyBy('assignment_question_id');
        $index = $page - 1;
        $active = $allQuestions[$index] ?? null;
        $answerModel = $active ? $answers->get($active->id) : null;
        $answered = $answers->count();
        return inertia('Student/Assignments/PlaySingle', [
            'assignment'=>$assignment,
            'submission'=>$submission,
            'page'=>$page,
            'all_questions'=>$allQuestions->map(function($q) use ($answers){
                return [ 'id'=>$q->id, 'answer'=>$answers->get($q->id)?->answer ?? 0 ];
            })->values(),
            'question_active'=>$active ? [ 'question'=>$active, 'answer'=>$answerModel?->answer ] : null,
            'question_answered'=>$answered,
            'finished'=>$submission->finished_at ? true : false,
        ]);
    }
    public function answer(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'question_id'=>'required|integer',
            'answer'=>'nullable|integer|min:1|max:5'
        ]);
        $student = auth()->guard('student')->user();
        abort_unless($assignment->classroom_id === $student->classroom_id, 403);
        $submission = AssignmentSubmission::where('assignment_id',$assignment->id)->where('student_id',$student->id)->firstOrFail();
        $question = AssignmentQuestion::where('assignment_id',$assignment->id)->findOrFail($data['question_id']);
        $ansModel = AssignmentAnswer::updateOrCreate([
            'submission_id'=>$submission->id,
            'assignment_question_id'=>$question->id,
        ],[
            'answer'=>$data['answer'],
            'is_correct'=>$question->answer == $data['answer']
        ]);
        // recalc score
        $correct = AssignmentAnswer::where('submission_id',$submission->id)->where('is_correct',true)->count();
        $submission->total_correct = $correct;
        $submission->score = $submission->total_questions ? round(($correct / $submission->total_questions)*100,2) : 0;
        $submission->save();
        return back();
    }
    public function finish(Assignment $assignment)
    {
        $student = auth()->guard('student')->user();
        $submission = AssignmentSubmission::where('assignment_id',$assignment->id)->where('student_id',$student->id)->firstOrFail();
        if(!$submission->finished_at){ $submission->finished_at = now(); $submission->save(); }
        return redirect()->route('student.assignments.result', $assignment->id);
    }
    public function result(Assignment $assignment)
    {
        $student = auth()->guard('student')->user();
        $submission = AssignmentSubmission::where('assignment_id',$assignment->id)->where('student_id',$student->id)->firstOrFail();
        abort_unless($submission->finished_at, 403);
        return inertia('Student/Assignments/Result', [
            'assignment'=>$assignment,
            'submission'=>$submission,
        ]);
    }
}
