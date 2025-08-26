<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Models\{Tryout,TryoutAttempt,TryoutQuestion,TryoutAnswer};
use Illuminate\Http\Request;

class TryoutPlayController extends Controller
{
    public function confirmation(Tryout $tryout)
    {
        $student = auth()->guard('student')->user();
        abort_unless($tryout->classroom_id === $student->classroom_id, 403);
        $attempt = TryoutAttempt::where('tryout_id',$tryout->id)->where('student_id',$student->id)->first();
        return inertia('Student/Tryouts/Confirmation',[ 'tryout'=>$tryout, 'attempt'=>$attempt ]);
    }
    public function start(Tryout $tryout)
    {
        $student = auth()->guard('student')->user();
        abort_unless($tryout->classroom_id === $student->classroom_id, 403);
        $attempt = TryoutAttempt::firstOrCreate([
            'tryout_id'=>$tryout->id,
            'student_id'=>$student->id,
        ],[
            'started_at'=>now(),
            'total_questions'=>$tryout->questions()->count(),
        ]);
        if(!$attempt->started_at){ $attempt->started_at = now(); $attempt->save(); }
        return redirect()->route('student.tryouts.show', [$tryout->id, 1]);
    }
    public function show(Tryout $tryout, int $page)
    {
        $student = auth()->guard('student')->user();
        abort_unless($tryout->classroom_id === $student->classroom_id, 403);
        $attempt = TryoutAttempt::where('tryout_id',$tryout->id)->where('student_id',$student->id)->first();
        if(!$attempt){
            return redirect()->route('student.tryouts.start',$tryout->id);
        }
            if($attempt->finished_at){
                return redirect()->route('student.tryouts.result',$tryout->id);
            }
            if($attempt->status === 'exited'){
                return redirect()->route('student.tryouts.confirmation',$tryout->id)->with('error','Sesi sebelumnya terdeteksi keluar. Hubungi operator untuk membuka kembali.');
            }
        $allQuestions = $tryout->questions()->orderBy('order')->get();
        $answers = TryoutAnswer::where('attempt_id',$attempt->id)->get()->keyBy('tryout_question_id');
        $index = $page - 1;
        $active = $allQuestions[$index] ?? null;
        $answerModel = $active ? $answers->get($active->id) : null;
        $answered = $answers->count();
        return inertia('Student/Tryouts/PlaySingle', [
            'tryout'=>$tryout,
            'attempt'=>$attempt,
            'page'=>$page,
            'all_questions'=>$allQuestions->map(function($q) use ($answers){ return ['id'=>$q->id,'answer'=>$answers->get($q->id)?->answer ?? 0]; }),
            'question_active'=>$active ? [ 'question'=>$active, 'answer'=>$answerModel?->answer ] : null,
            'question_answered'=>$answered,
            'finished'=>$attempt->finished_at ? true : false,
        ]);
    }
    public function answer(Request $request, Tryout $tryout)
    {
        $data = $request->validate([
            'question_id'=>'required|integer',
            'answer'=>'nullable|integer|min:1|max:5'
        ]);
        $student = auth()->guard('student')->user();
        abort_unless($tryout->classroom_id === $student->classroom_id, 403);
        $attempt = TryoutAttempt::where('tryout_id',$tryout->id)->where('student_id',$student->id)->firstOrFail();
        $question = TryoutQuestion::where('tryout_id',$tryout->id)->findOrFail($data['question_id']);
        TryoutAnswer::updateOrCreate([
            'attempt_id'=>$attempt->id,
            'tryout_question_id'=>$question->id,
        ],[
            'answer'=>$data['answer'],
            'is_correct'=>$question->answer == $data['answer']
        ]);
        $correct = TryoutAnswer::where('attempt_id',$attempt->id)->where('is_correct',true)->count();
        $attempt->total_correct = $correct;
        $attempt->score = $attempt->total_questions ? round(($correct / $attempt->total_questions)*100,2) : 0;
        $attempt->save();
        return back();
    }
    public function finish(Tryout $tryout)
    {
        $student = auth()->guard('student')->user();
        $attempt = TryoutAttempt::where('tryout_id',$tryout->id)->where('student_id',$student->id)->firstOrFail();
        if(!$attempt->finished_at){ $attempt->finished_at = now(); $attempt->save(); }
        return redirect()->route('student.tryouts.result', $tryout->id);
    }
        public function abort(Tryout $tryout)
        {
            $student = auth()->guard('student')->user();
            $attempt = TryoutAttempt::where('tryout_id',$tryout->id)->where('student_id',$student->id)->first();
            if($attempt && !$attempt->finished_at){
                $attempt->status = 'exited';
                $attempt->save();
            }
            return response()->json(['status'=>'ok']);
        }
        public function exit(Tryout $tryout)
        {
            $student = auth()->guard('student')->user();
            $attempt = TryoutAttempt::where('tryout_id',$tryout->id)->where('student_id',$student->id)->first();
            if($attempt && !$attempt->finished_at){
                $attempt->status = 'exited';
                $attempt->save();
            }
            return response()->json(['status'=>'ok']);
        }
    public function result(Tryout $tryout)
    {
        $student = auth()->guard('student')->user();
        $attempt = TryoutAttempt::where('tryout_id',$tryout->id)->where('student_id',$student->id)->firstOrFail();
        abort_unless($attempt->finished_at, 403);
        return inertia('Student/Tryouts/Result', [
            'tryout'=>$tryout,
            'attempt'=>$attempt,
        ]);
    }
}
