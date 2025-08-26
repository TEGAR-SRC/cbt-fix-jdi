<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\TryoutAttempt;
use Illuminate\Http\Request;

class TryoutController extends Controller
{
    public function index(Request $request)
    {
        $student = auth()->guard('student')->user();
        $query = Tryout::with('lesson','classroom')
            ->where('classroom_id', $student->classroom_id)
            ->whereNotNull('published_at');
        if ($search = $request->get('q')) {
            $query->where('title','like',"%$search%");
        }
        $tryouts = $query->orderByDesc('published_at')->get();
        return inertia('Student/Tryouts/Index', [
            'tryouts' => $tryouts,
            'filters' => [ 'q' => $request->get('q') ]
        ]);
    }
    public function confirmation(Tryout $tryout)
    {
        $student = auth()->guard('student')->user();
        abort_unless($tryout->classroom_id === $student->classroom_id, 403);
        $tryout->loadCount('questions');
        $attempt = TryoutAttempt::where('tryout_id',$tryout->id)->where('student_id',$student->id)->first();
        return inertia('Student/Tryouts/Confirmation', [ 'tryout'=>$tryout, 'attempt'=>$attempt ]);
    }
}
