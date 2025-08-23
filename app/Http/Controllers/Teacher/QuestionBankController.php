<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Lesson;
use App\Models\Classroom;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Exports\QuestionsExport;
use Maatwebsite\Excel\Facades\Excel;

class QuestionBankController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $lessonId = $request->get('lesson_id');
        $classroomId = $request->get('classroom_id');
        $examId = $request->get('exam_id');

        $builder = Question::with(['exam.lesson','exam.classroom'])
            ->when($q, function ($query) use ($q) {
                $query->where('question', 'like', "%{$q}%");
            })
            ->when($lessonId, function ($query) use ($lessonId) {
                $query->whereHas('exam', fn($e) => $e->where('lesson_id', $lessonId));
            })
            ->when($classroomId, function ($query) use ($classroomId) {
                $query->whereHas('exam', fn($e) => $e->where('classroom_id', $classroomId));
            })
            ->when($examId, function ($query) use ($examId) {
                $query->where('exam_id', $examId);
            })
            ->latest();

        $questions = $builder->paginate(10)->appends([
            'q' => $q,
            'lesson_id' => $lessonId,
            'classroom_id' => $classroomId,
            'exam_id' => $examId,
        ]);

        $lessons = Lesson::orderBy('title')->get();
        $classrooms = Classroom::orderBy('title')->get();
        $exams = Exam::with('lesson','classroom')->orderBy('created_at','desc')->get();

        return inertia('Teacher/Questions/Index', [
            'questions' => $questions,
            'filters' => [
                'q' => $q,
                'lesson_id' => $lessonId,
                'classroom_id' => $classroomId,
                'exam_id' => $examId,
            ],
            'lessons' => $lessons,
            'classrooms' => $classrooms,
            'exams' => $exams,
        ]);
    }

    public function export(Request $request)
    {
        $q = $request->get('q');
        $lessonId = $request->get('lesson_id');
        $classroomId = $request->get('classroom_id');
        $examId = $request->get('exam_id');

        $builder = Question::with(['exam.lesson','exam.classroom'])
            ->when($q, fn($query)=>$query->where('question','like',"%{$q}%"))
            ->when($lessonId, fn($query)=>$query->whereHas('exam', fn($e)=>$e->where('lesson_id',$lessonId)))
            ->when($classroomId, fn($query)=>$query->whereHas('exam', fn($e)=>$e->where('classroom_id',$classroomId)))
            ->when($examId, fn($query)=>$query->where('exam_id',$examId));

        $questions = $builder->get();
        return Excel::download(new QuestionsExport($questions), 'bank-soal.xlsx');
    }
}
