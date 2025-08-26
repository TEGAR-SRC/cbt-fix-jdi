<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\Lesson;
use App\Models\Classroom;
use Illuminate\Http\Request;

class TryoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Tryout::with(['lesson','classroom','creator'])
            ->when($request->q, function($q,$search){
                $q->where(function($sub) use ($search){
                    $sub->where('title','like',"%$search%")
                        ->orWhere('description','like',"%$search%") ;
                });
            })
            ->latest();
        $tryouts = $query->paginate(10)->withQueryString();
        return inertia('Operator/Tryouts/Index',[ 'tryouts'=>$tryouts, 'filters'=>['q'=>$request->q] ]);
    }

    public function create()
    {
        return inertia('Operator/Tryouts/Create', [
            'lessons' => Lesson::orderBy('title')->get(['id','title']),
            'classrooms' => Classroom::orderBy('title')->get(['id','title']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lesson_id' => 'required|exists:lessons,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'published_at' => 'nullable|date',
        ]);
        $data['created_by'] = $request->user()->id;
        Tryout::create($data);
        return redirect()->route('operator.tryouts.index')->with('success','Tryout dibuat');
    }

    public function edit(Tryout $tryout)
    {
        return inertia('Operator/Tryouts/Edit', [
            'tryout' => $tryout->load(['lesson','classroom']),
            'lessons' => Lesson::orderBy('title')->get(['id','title']),
            'classrooms' => Classroom::orderBy('title')->get(['id','title']),
        ]);
    }

    public function update(Request $request, Tryout $tryout)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'lesson_id' => 'required|exists:lessons,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'published_at' => 'nullable|date',
        ]);
        $tryout->update($data);
        return redirect()->route('operator.tryouts.index')->with('success','Tryout diupdate');
    }

    public function destroy(Tryout $tryout)
    {
        $tryout->delete();
        return redirect()->route('operator.tryouts.index')->with('success','Tryout dihapus');
    }
}
