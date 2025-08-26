<?php
namespace App\Http\Controllers\Teacher;
use App\Http\Controllers\Controller;
use App\Models\{Assignment,Lesson,Classroom};
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $assignments = Assignment::with(['lesson','classroom','questions'])
            ->when($request->q, function($query,$q){
                $query->where(function($sub) use ($q){
                    $sub->where('title','like',"%$q%")
                        ->orWhere('description','like',"%$q%");
                });
            })
            ->latest()->paginate(10)->withQueryString();
        return inertia('Teacher/Assignments/Index',[ 'assignments'=>$assignments, 'filters'=>['q'=>$request->q] ]);
    }
    public function create()
    {
        return inertia('Teacher/Assignments/Create', [
            'lessons'=>Lesson::orderBy('title')->get(['id','title']),
            'classrooms'=>Classroom::orderBy('title')->get(['id','title']),
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'lesson_id'=>'required|exists:lessons,id',
            'classroom_id'=>'required|exists:classrooms,id',
            'due_at'=>'nullable|date',
            'published_at'=>'nullable|date',
        ]);
        $data['created_by'] = $request->user()->id;
        Assignment::create($data);
        return redirect()->route('teacher.assignments.index')->with('success','Tugas dibuat');
    }
    public function edit(Assignment $assignment)
    {
        return inertia('Teacher/Assignments/Edit', [
            'assignment'=>$assignment->load(['lesson','classroom']),
            'lessons'=>Lesson::orderBy('title')->get(['id','title']),
            'classrooms'=>Classroom::orderBy('title')->get(['id','title']),
        ]);
    }
    public function update(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'lesson_id'=>'required|exists:lessons,id',
            'classroom_id'=>'required|exists:classrooms,id',
            'due_at'=>'nullable|date',
            'published_at'=>'nullable|date',
        ]);
        $assignment->update($data);
        return redirect()->route('teacher.assignments.index')->with('success','Tugas diupdate');
    }
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('teacher.assignments.index')->with('success','Tugas dihapus');
    }
}
