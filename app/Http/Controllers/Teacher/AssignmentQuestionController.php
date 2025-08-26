<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use Illuminate\Http\Request;

class AssignmentQuestionController extends Controller
{
    public function index(Assignment $assignment)
    {
        $assignment->load('questions');
        $query = request()->q;
        $questions = $assignment->questions()
            ->when($query, fn($q,$term)=>$q->where('question','like',"%$term%"))
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();
        return inertia('Teacher/Assignments/Questions/Index', [
            'assignment' => $assignment,
            'questions' => $questions,
            'filters' => ['q'=>$query]
        ]);
    }

    public function create(Assignment $assignment)
    {
        return inertia('Teacher/Assignments/Questions/Create', [
            'assignment' => $assignment,
        ]);
    }

    public function store(Request $request, Assignment $assignment)
    {
        $data = $request->validate([
            'question' => 'required|string',
            'option_1' => 'nullable|string',
            'option_2' => 'nullable|string',
            'option_3' => 'nullable|string',
            'option_4' => 'nullable|string',
            'option_5' => 'nullable|string',
            'answer' => 'nullable|string|in:option_1,option_2,option_3,option_4,option_5',
            'order' => 'nullable|integer|min:0',
        ]);
        $data['assignment_id'] = $assignment->id;
        AssignmentQuestion::create($data);
        return redirect()->route('teacher.assignments.questions.index', $assignment)->with('success', 'Soal ditambahkan');
    }

    public function edit(Assignment $assignment, AssignmentQuestion $question)
    {
        return inertia('Teacher/Assignments/Questions/Edit', [
            'assignment' => $assignment,
            'question' => $question,
        ]);
    }

    public function update(Request $request, Assignment $assignment, AssignmentQuestion $question)
    {
        $data = $request->validate([
            'question' => 'required|string',
            'option_1' => 'nullable|string',
            'option_2' => 'nullable|string',
            'option_3' => 'nullable|string',
            'option_4' => 'nullable|string',
            'option_5' => 'nullable|string',
            'answer' => 'nullable|string|in:option_1,option_2,option_3,option_4,option_5',
            'order' => 'nullable|integer|min:0',
        ]);
        $question->update($data);
        return redirect()->route('teacher.assignments.questions.index', $assignment)->with('success', 'Soal diperbarui');
    }

    public function destroy(Assignment $assignment, AssignmentQuestion $question)
    {
        $question->delete();
        return redirect()->route('teacher.assignments.questions.index', $assignment)->with('success', 'Soal dihapus');
    }
}
