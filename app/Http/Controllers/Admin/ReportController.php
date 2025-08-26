<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\ExamSession;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Tryout;
use App\Models\TryoutAttempt;
use Illuminate\Http\Request;
use App\Exports\GradesExport;
use App\Exports\AssignmentResultsExport;
use App\Exports\TryoutResultsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'exam');
        $exams = Exam::with('lesson','classroom')->get();
        $assignments = Assignment::with('lesson','classroom')->get();
        $tryouts = Tryout::with('lesson','classroom')->get();
        return inertia('Admin/Reports/Index', [
            'type' => $type,
            'exams' => $exams,
            'assignments' => $assignments,
            'tryouts' => $tryouts,
            'results' => [],
            'exam_id' => null,
            'assignment_id' => null,
            'tryout_id' => null,
        ]);
    }
    
    /**
     * filter
     *
     * @param  mixed $request
     * @return void
     */
    public function filter(Request $request)
    {
        $type = $request->get('type', 'exam');
        $exams = Exam::with('lesson','classroom')->get();
        $assignments = Assignment::with('lesson','classroom')->get();
        $tryouts = Tryout::with('lesson','classroom')->get();
        $results = [];
        $exam_id = $request->get('exam_id');
        $assignment_id = $request->get('assignment_id');
        $tryout_id = $request->get('tryout_id');

        if ($type === 'exam' && $exam_id) {
            $exam = $exams->firstWhere('id', (int)$exam_id);
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
        if ($type === 'assignment' && $assignment_id) {
            $assignment = $assignments->firstWhere('id',(int)$assignment_id);
            if ($assignment) {
                $results = AssignmentSubmission::with('student','assignment.classroom','assignment.lesson')
                    ->where('assignment_id',$assignment->id)
                    ->get();
            }
        }
        if ($type === 'tryout' && $tryout_id) {
            $tryout = $tryouts->firstWhere('id',(int)$tryout_id);
            if ($tryout) {
                $results = TryoutAttempt::with('student','tryout.classroom','tryout.lesson')
                    ->where('tryout_id',$tryout->id)
                    ->get();
            }
        }

        return inertia('Admin/Reports/Index', [
            'type' => $type,
            'exams' => $exams,
            'assignments' => $assignments,
            'tryouts' => $tryouts,
            'results' => $results,
            'exam_id' => $exam_id,
            'assignment_id' => $assignment_id,
            'tryout_id' => $tryout_id,
        ]);
    }

    /**
     * export
     *
     * @param  mixed $request
     * @return void
     */
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
            return Excel::download(new GradesExport($grades), 'laporan-ujian-'.$exam->title.'-'.Carbon::now().'.xlsx');
        }
        if ($type === 'assignment') {
            $assignment = Assignment::with('lesson','classroom')->findOrFail($request->get('assignment_id'));
            $subs = AssignmentSubmission::with('student','assignment.classroom','assignment.lesson')
                ->where('assignment_id',$assignment->id)->get();
            return Excel::download(new AssignmentResultsExport($subs), 'laporan-tugas-'.$assignment->title.'-'.Carbon::now().'.xlsx');
        }
        if ($type === 'tryout') {
            $tryout = Tryout::with('lesson','classroom')->findOrFail($request->get('tryout_id'));
            $attempts = TryoutAttempt::with('student','tryout.classroom','tryout.lesson')
                ->where('tryout_id',$tryout->id)->get();
            return Excel::download(new TryoutResultsExport($attempts), 'laporan-tryout-'.$tryout->title.'-'.Carbon::now().'.xlsx');
        }
        abort(404);
    }
}