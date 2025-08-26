<?php
namespace App\Http\Controllers\Admin;
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
        return inertia('Admin/Tryouts/Results/Index',[ 'tryout'=>$tryout, 'attempts'=>$attempts, 'filters'=>['q'=>$request->q] ]);
    }
    public function show(Tryout $tryout, TryoutAttempt $attempt)
    {
        abort_unless($attempt->tryout_id == $tryout->id,404);
        $attempt->load('student');
        $answers = TryoutAnswer::with('question')->where('attempt_id',$attempt->id)->get();
        return inertia('Admin/Tryouts/Results/Show',[ 'tryout'=>$tryout, 'attempt'=>$attempt, 'answers'=>$answers ]);
    }
}
