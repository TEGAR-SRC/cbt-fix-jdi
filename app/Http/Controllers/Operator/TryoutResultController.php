<?php
namespace App\Http\Controllers\Operator;
use App\Http\Controllers\Controller;
use App\Models\{Tryout,TryoutAttempt,TryoutAnswer};
use Illuminate\Http\Request;

class TryoutResultController extends Controller
{
    public function index(Tryout $tryout, Request $request)
    {
        $attempts = TryoutAttempt::with('student')
            ->where('tryout_id',$tryout->id)
            ->when($request->q, function($q,$term){
                $q->whereHas('student', fn($s)=>$s->where('name','like',"%$term%"));
            })
            ->orderByDesc('finished_at')
            ->paginate(20)
            ->withQueryString();
        return inertia('Operator/Tryouts/Results/Index',[ 'tryout'=>$tryout, 'attempts'=>$attempts, 'filters'=>['q'=>$request->q] ]);
    }
    public function show(Tryout $tryout, TryoutAttempt $attempt)
    {
        abort_unless($attempt->tryout_id == $tryout->id,404);
        $attempt->load('student');
        $answers = TryoutAnswer::with('question')->where('attempt_id',$attempt->id)->get();
        return inertia('Operator/Tryouts/Results/Show',[ 'tryout'=>$tryout, 'attempt'=>$attempt, 'answers'=>$answers ]);
    }
    public function forceFinish(Tryout $tryout, TryoutAttempt $attempt)
    {
        abort_unless($attempt->tryout_id == $tryout->id,404);
        if(!$attempt->finished_at){
            $attempt->finished_at = now();
            $total = $attempt->total_questions ?: $tryout->questions()->count();
            $correct = $attempt->answers()->where('is_correct',true)->count();
            $attempt->total_questions = $total;
            $attempt->total_correct = $correct;
            $attempt->score = $total ? round(($correct/$total)*100,2) : 0;
            $attempt->save();
        }
        return back()->with('success','Tryout dihentikan & diselesaikan.');
    }
    public function reopen(Tryout $tryout, TryoutAttempt $attempt)
    {
        abort_unless($attempt->tryout_id == $tryout->id,404);
        if($attempt->finished_at){
            $attempt->finished_at = null;
            $attempt->save();
        }
        return back()->with('success','Tryout dibuka kembali.');
    }
}
