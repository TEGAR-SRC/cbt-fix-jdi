<?php

namespace App\Http\Controllers\Teacher;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\ExamSession;
use Illuminate\Http\Request;
use App\Exports\GradesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $exams = Exam::with('lesson','classroom')->get();
        return inertia('Teacher/Reports/Index', [
            'exams' => $exams,
            'grades' => [],
        ]);
    }

    public function filter(Request $request)
    {
        $request->validate(['exam_id' => 'required']);

        $exams = Exam::with('lesson','classroom')->get();
        $exam = Exam::with('lesson','classroom')->find($request->exam_id);

        if ($exam) {
            $exam_session = ExamSession::where('exam_id', $exam->id)->first();
            $grades = Grade::with('student','exam.classroom','exam.lesson','exam_session')
                ->where('exam_id', $exam->id)
                ->when($exam_session, fn($q) => $q->where('exam_session_id', $exam_session->id))
                ->get();
        } else {
            $grades = [];
        }

        return inertia('Teacher/Reports/Index', [
            'exams' => $exams,
            'grades' => $grades,
        ]);
    }

    public function export(Request $request)
    {
        $exam = Exam::with('lesson','classroom')->findOrFail($request->exam_id);
        $exam_session = ExamSession::where('exam_id', $exam->id)->first();

        $grades = Grade::with('student','exam.classroom','exam.lesson','exam_session')
            ->where('exam_id', $exam->id)
            ->when($exam_session, fn($q) => $q->where('exam_session_id', $exam_session->id))
            ->get();

        return Excel::download(new GradesExport($grades), 'grade - '.$exam->title.' — '.$exam->lesson->title.' — '.Carbon::now().'.xlsx');
    }
}
