<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Tryout;
use App\Models\TryoutQuestion;
use Illuminate\Http\Request;
use App\Imports\TryoutQuestionsImport;
use Maatwebsite\Excel\Facades\Excel;

class TryoutQuestionController extends Controller
{
    public function index(Tryout $tryout)
    {
        $tryout->load('lesson','classroom');
        $query = request()->q;
        $questions = $tryout->questions()
            ->when($query, fn($q,$term)=>$q->where('question','like',"%$term%"))
            ->orderBy('order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();
        return inertia('Teacher/Tryouts/Questions/Index', [ 'tryout'=>$tryout, 'questions'=>$questions, 'filters'=>['q'=>$query] ]);
    }
    public function create(Tryout $tryout)
    { return inertia('Teacher/Tryouts/Questions/Create', ['tryout'=>$tryout]); }
    public function store(Request $request, Tryout $tryout)
    { $data = $request->validate(['question'=>'required|string','option_1'=>'nullable|string','option_2'=>'nullable|string','option_3'=>'nullable|string','option_4'=>'nullable|string','option_5'=>'nullable|string','answer'=>'nullable|string|in:1,2,3,4,5','order'=>'nullable|integer|min:0']); $data['tryout_id']=$tryout->id; TryoutQuestion::create($data); return redirect()->route('teacher.tryouts.questions.index',$tryout->id)->with('success','Soal dibuat'); }
    public function edit(Tryout $tryout, TryoutQuestion $question)
    { return inertia('Teacher/Tryouts/Questions/Edit', ['tryout'=>$tryout,'question'=>$question]); }
    public function update(Request $request, Tryout $tryout, TryoutQuestion $question)
    { $data = $request->validate(['question'=>'required|string','option_1'=>'nullable|string','option_2'=>'nullable|string','option_3'=>'nullable|string','option_4'=>'nullable|string','option_5'=>'nullable|string','answer'=>'nullable|string|in:1,2,3,4,5','order'=>'nullable|integer|min:0']); $question->update($data); return redirect()->route('teacher.tryouts.questions.index',$tryout->id)->with('success','Soal diupdate'); }
    public function destroy(Tryout $tryout, TryoutQuestion $question)
    { $question->delete(); return redirect()->route('teacher.tryouts.questions.index',$tryout->id)->with('success','Soal dihapus'); }

    public function import(Tryout $tryout)
    { return inertia('Teacher/Tryouts/Questions/Import',[ 'tryout'=>$tryout ]); }

    public function storeImport(Request $request, Tryout $tryout)
    { $request->validate(['file'=>'required|file|mimes:xls,xlsx']); Excel::import(new TryoutQuestionsImport($tryout->id), $request->file('file')); return redirect()->route('teacher.tryouts.questions.index',$tryout->id)->with('success','Import selesai'); }
}
