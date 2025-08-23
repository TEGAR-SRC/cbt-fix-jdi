<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\Grade;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
    $examSessionId = $request->get('exam_session_id');
    $status = $request->get('status');

        $sessions = ExamSession::with('exam.lesson')->latest()->get();

        $query = Grade::with(['student.classroom', 'exam.lesson', 'exam_session'])
            ->when($examSessionId, function ($q) use ($examSessionId) {
                $q->where('exam_session_id', $examSessionId);
            })
            ->when($status === 'in_progress', function ($q) {
                $q->whereNotNull('start_time')->whereNull('end_time');
            })
            ->when($status === 'finished', function ($q) {
                $q->whereNotNull('end_time');
            })
            ->when($status === 'not_started', function ($q) {
                $q->whereNull('start_time');
            });

        $inProgressCount = (clone $query)->whereNotNull('start_time')->whereNull('end_time')->count();
        $finishedCount   = (clone $query)->whereNotNull('end_time')->count();
        $notStartedCount = (clone $query)->whereNull('start_time')->count();

    $grades = $query->latest()->paginate(10);
    $grades->appends(['exam_session_id' => $examSessionId, 'status' => $status]);

    $classCount = (clone $query)->join('students','students.id','=','grades.student_id')->distinct('students.classroom_id')->count('students.classroom_id');
    $studentCount = (clone $query)->distinct('student_id')->count('student_id');

        return inertia('Dinas/Monitor/Index', [
            'sessions' => $sessions,
            'grades' => $grades,
            'stats' => [
                'in_progress' => $inProgressCount,
                'finished' => $finishedCount,
                'not_started' => $notStartedCount,
                'classes' => $classCount,
                'students' => $studentCount,
            ],
            'filters' => [
                'exam_session_id' => $examSessionId,
                'status' => $status,
            ],
        ]);
    }
}
