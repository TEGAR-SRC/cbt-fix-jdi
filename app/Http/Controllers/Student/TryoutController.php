<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
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
}
