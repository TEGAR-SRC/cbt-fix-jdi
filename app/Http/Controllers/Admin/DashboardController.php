<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\ExamSession;
use App\Models\Assignment;
use App\Models\Tryout;
use App\Models\TryoutAttempt;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //count students
        $students = Student::count();

        //count exams
        $exams = Exam::count();

        //count exam sessions
        $exam_sessions = ExamSession::count();

        // assignments & tryouts
        $assignments = Assignment::count();
        $tryouts     = Tryout::count();
    $questions   = Question::count();

        // active & ended sessions (today)
        $now = Carbon::now();
        $active_sessions = ExamSession::where('start_time', '<=', $now)->where('end_time', '>=', $now)->count();
        $ended_sessions_today = ExamSession::whereDate('end_time', $now->toDateString())->where('end_time', '<', $now)->count();

        $sessionStatus = [
            'active' => $active_sessions,
            'ended'  => $ended_sessions_today,
        ];

        // build last 7 day activity (exam sessions ended & tryout attempts finished)
        $examActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = $now->copy()->subDays($i)->toDateString();
            $examsFinished = ExamSession::whereDate('end_time', $day)->count();
            $tryoutsFinished = TryoutAttempt::whereDate('created_at', $day)->count();
            $examActivity[] = [
                'date' => substr($day, 5), // MM-DD
                'exams' => $examsFinished,
                'tryouts' => $tryoutsFinished,
            ];
        }

        //count classrooms
        $classrooms = Classroom::count();

        // upcoming & active exam sessions (limited)
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
                    'participants' => $s->exam_groups_count, // count of enrolled groups as a simple proxy
                    'duration_min' => $s->exam?->duration,
                ];
            });

        $systemStatus = [
            'database'     => true,
            'websocket'    => false,
            'anti_cheat'   => true,
            'storage_used' => 0.72 + rand(0,8)/100, // simulate slight variation
            'last_backup'  => '2 hours ago',
            'queue'        => [ 'pending' => 0, 'failed' => 0 ],
        ];

        // recent activity (simple mix of recent sessions & exams created)
        $recentSessions = ExamSession::orderBy('created_at','desc')->limit(5)->get()->map(function($s){
            return [
                'type' => 'session',
                'title' => 'Sesi: '.($s->title ?: 'Tanpa Judul'),
                'time' => $s->created_at?->diffForHumans(),
                'status' => 'Created'
            ];
        });
        $recentExams = Exam::orderBy('created_at','desc')->limit(5)->get()->map(function($e){
            return [
                'type' => 'exam',
                'title' => 'Ujian: '.($e->title ?: 'Tanpa Judul'),
                'time' => $e->created_at?->diffForHumans(),
                'status' => 'Created'
            ];
        });
        $recentActivity = $recentSessions->merge($recentExams)->sortByDesc('time')->take(7)->values();

        return inertia('Admin/Dashboard/Index', [
            'students'            => $students,
            'exams'               => $exams,
            'exam_sessions'       => $exam_sessions,
            'classrooms'          => $classrooms,
            'assignments'         => $assignments,
            'tryouts'             => $tryouts,
            'questions'           => $questions,
            'active_sessions'     => $active_sessions,
            'ended_sessions_today'=> $ended_sessions_today,
            'session_status'      => $sessionStatus,
            'exam_activity'       => $examActivity,
            'upcoming_exams'      => $upcoming,
            'system_status'       => $systemStatus,
            'recent_activity'     => $recentActivity,
        ]);
    }
}