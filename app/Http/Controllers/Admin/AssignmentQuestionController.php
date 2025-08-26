<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentQuestion;
use Illuminate\Http\Request;
use App\Imports\AssignmentQuestionsImport;
use Maatwebsite\Excel\Facades\Excel;

class AssignmentQuestionController extends Controller
{
    public function index(Assignment $assignment)
    {
        $assignment->load('questions');
        return inertia('Admin/Assignments/Questions/Index', [
            'assignment' => $assignment,
            'questions' => $assignment->questions,
        ]);
    }

    public function create(Assignment $assignment)
    {
        return inertia('Admin/Assignments/Questions/Create', [
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
        return redirect()->route('admin.assignments.questions.index', $assignment)->with('success', 'Soal ditambahkan');
    }

    public function edit(Assignment $assignment, AssignmentQuestion $question)
    {
        return inertia('Admin/Assignments/Questions/Edit', [
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
        return redirect()->route('admin.assignments.questions.index', $assignment)->with('success', 'Soal diperbarui');
    }

    public function destroy(Assignment $assignment, AssignmentQuestion $question)
    {
        $question->delete();
        return redirect()->route('admin.assignments.questions.index', $assignment)->with('success', 'Soal dihapus');
    }

    public function import(Assignment $assignment)
    {
        return inertia('Admin/Assignments/Questions/Import', [
            'assignment' => $assignment
        ]);
    }

    public function storeImport(Request $request, Assignment $assignment)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);
        Excel::import(new AssignmentQuestionsImport($assignment->id), $request->file('file'));
        return redirect()->route('admin.assignments.questions.index', $assignment)->with('success', 'Import soal tugas berhasil');
    }
}
