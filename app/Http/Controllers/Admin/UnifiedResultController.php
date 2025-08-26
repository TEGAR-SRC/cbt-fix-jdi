<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Tryout;
use App\Models\TryoutAttempt;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradesExport;
use App\Exports\AssignmentResultsExport;
use App\Exports\TryoutResultsExport;
use Carbon\Carbon;

class UnifiedResultController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'exam');
        $examId = $request->get('exam_id');
        $assignmentId = $request->get('assignment_id');
        $tryoutId = $request->get('tryout_id');

        $exams = Exam::with('lesson','classroom')->orderByDesc('id')->get();
        $assignments = Assignment::with('lesson','classroom')->orderByDesc('id')->get();
        $tryouts = Tryout::with('lesson','classroom')->orderByDesc('id')->get();

        $results = [];

        if ($type === 'exam' && $examId) {
            $exam = $exams->firstWhere('id', (int)$examId);
            if ($exam) {
                $session = ExamSession::where('exam_id',$exam->id)->first();
                if ($session) {
                    $results = Grade::with('student','exam.classroom','exam.lesson','exam_session')
                        ->where('exam_id',$exam->id)
                        ->where('exam_session_id',$session->id)
                        ->get();
                }
            }
        }
        if ($type === 'assignment' && $assignmentId) {
            $assignment = $assignments->firstWhere('id',(int)$assignmentId);
            if ($assignment) {
                $results = AssignmentSubmission::with('student','assignment.classroom','assignment.lesson')
                    ->where('assignment_id',$assignment->id)
                    ->get();
            }
        }
        if ($type === 'tryout' && $tryoutId) {
            $tryout = $tryouts->firstWhere('id',(int)$tryoutId);
            if ($tryout) {
                $results = TryoutAttempt::with('student','tryout.classroom','tryout.lesson')
                    ->where('tryout_id',$tryout->id)
                    ->get();
            }
        }

        return Inertia::render('Admin/Results/Unified', [
            'type' => $type,
            'exams' => $exams,
            'assignments' => $assignments,
            'tryouts' => $tryouts,
            'results' => $results,
            'exam_id' => $examId,
            'assignment_id' => $assignmentId,
            'tryout_id' => $tryoutId,
        ]);
    }

    public function export(Request $request)
    {
        $type = $request->get('type','exam');
        if ($type === 'exam') {
            $exam = Exam::with('lesson','classroom')->findOrFail($request->get('exam_id'));
            $session = ExamSession::where('exam_id',$exam->id)->firstOrFail();
            $grades = Grade::with('student','exam.classroom','exam.lesson','exam_session')
                ->where('exam_id',$exam->id)
                ->where('exam_session_id',$session->id)
                ->get();
            return Excel::download(new GradesExport($grades), 'hasil-ujian-'.$exam->title.'-'.Carbon::now().'.xlsx');
        }
        if ($type === 'assignment') {
            $assignment = Assignment::with('lesson','classroom')->findOrFail($request->get('assignment_id'));
            $subs = AssignmentSubmission::with('student','assignment.classroom','assignment.lesson')
                ->where('assignment_id',$assignment->id)->get();
            return Excel::download(new AssignmentResultsExport($subs), 'hasil-tugas-'.$assignment->title.'-'.Carbon::now().'.xlsx');
        }
        if ($type === 'tryout') {
            $tryout = Tryout::with('lesson','classroom')->findOrFail($request->get('tryout_id'));
            $attempts = TryoutAttempt::with('student','tryout.classroom','tryout.lesson')
                ->where('tryout_id',$tryout->id)->get();
            return Excel::download(new TryoutResultsExport($attempts), 'hasil-tryout-'.$tryout->title.'-'.Carbon::now().'.xlsx');
        }
        abort(404);
    }
}
