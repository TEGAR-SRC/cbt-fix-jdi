<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Student;
use App\Models\Question;
use App\Models\Assignment;
use App\Models\Tryout;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
    $students = Student::count();
    $exams = Exam::count();
    $questions = Question::count();
    $exam_sessions = ExamSession::count();
    $classrooms = Classroom::count();
    $assignments = Assignment::count();
    $tryouts = Tryout::count();

        $upcoming = ExamSession::with(['exam.lesson'])
            ->withCount('exam_groups')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($s) {
                $now = Carbon::now();
                $status = 'Upcoming';
                if ($s->start_time && $s->end_time) {
                    if ($now->between(Carbon::parse($s->start_time), Carbon::parse($s->end_time))) {
                        $status = 'Active';
                    } elseif ($now->greaterThan(Carbon::parse($s->end_time))) {
                        $status = 'Ended';
                    }
                }
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'exam' => [
                        'id' => $s->exam?->id,
                        'title' => $s->exam?->title,
                        'lesson' => $s->exam?->lesson?->title,
                    ],
                    'status' => $status,
                    'participants' => $s->exam_groups_count,
                    'duration_min' => $s->exam?->duration,
                ];
            });

        $now = Carbon::now();
        $active_sessions = ExamSession::where('start_time','<=',$now)->where('end_time','>=',$now)->count();
        $ended_today = ExamSession::whereDate('end_time', $now->toDateString())->count();

        $systemStatus = [ 'database' => true, 'websocket' => false, 'anti_cheat' => true, 'storage_used' => 0.65, 'last_backup' => '—' ];

        return inertia('Operator/Dashboard/Index', [
            'students' => $students,
            'exams' => $exams,
            'questions' => $questions,
            'exam_sessions' => $exam_sessions,
            'classrooms' => $classrooms,
            'assignments' => $assignments,
            'tryouts' => $tryouts,
            'active_sessions' => $active_sessions,
            'ended_sessions_today' => $ended_today,
            'upcoming_exams' => $upcoming,
            'system_status' => $systemStatus,
        ]);
    }
}
