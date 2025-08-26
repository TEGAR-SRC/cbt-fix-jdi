<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\Grade;
use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();

        // High-level stats
        $totalSessions   = ExamSession::count();
        $activeSessions  = ExamSession::where('start_time','<=',$now)->where('end_time','>=',$now)->count();
        $upcomingSessions= ExamSession::where('start_time','>',$now)->count();
        $endedSessions   = ExamSession::where('end_time','<',$now)->count();
        $totalExams      = Exam::count();
        $distinctStudents= Grade::distinct('student_id')->count('student_id');
        $averageGrade    = round((float) Grade::avg('grade'),2);

        // Grade distribution buckets
        $distribution = [ '90+' => 0, '80-89' => 0, '70-79' => 0, '60-69' => 0, '<60' => 0 ];
        Grade::select('grade')->chunk(1000, function($chunk) use (&$distribution){
            foreach($chunk as $g){
                $v = (int) $g->grade;
                if($v >= 90) $distribution['90+']++; elseif($v >= 80) $distribution['80-89']++; elseif($v >= 70) $distribution['70-79']++; elseif($v >= 60) $distribution['60-69']++; else $distribution['<60']++;            }
        });

        // Session status distribution (for doughnut)
        $sessionStatus = [
            'upcoming' => $upcomingSessions,
            'ongoing'  => $activeSessions,
            'ended'    => $endedSessions,
        ];

        // 7-day activity (sessions ended per day)
        $examActivity = [];
        for($i=6;$i>=0;$i--) {
            $day = Carbon::today()->subDays($i);
            $examActivity[] = [
                'date' => $day->format('Y-m-d'),
                'sessions_finished' => ExamSession::whereDate('end_time',$day)->count(),
            ];
        }

        // Recent sessions (limit 8)
        $recentSessions = ExamSession::with(['exam.lesson'])
            ->withCount('exam_groups')
            ->orderByDesc('start_time')
            ->limit(8)
            ->get()
            ->map(function($s) use ($now){
                $status = 'Upcoming';
                if($s->start_time && $s->end_time){
                    if($now->between(Carbon::parse($s->start_time), Carbon::parse($s->end_time))) $status='Active';
                    elseif($now->greaterThan(Carbon::parse($s->end_time))) $status='Ended';
                }
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'exam' => [
                        'id' => $s->exam?->id,
                        'title' => $s->exam?->title,
                        'lesson' => $s->exam?->lesson?->title,
                    ],
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                    'participants' => $s->exam_groups_count,
                    'status' => $status,
                ];
            });

        $systemStatus = [ 'database' => true, 'websocket' => false, 'anti_cheat' => true ];

        return inertia('Dinas/Dashboard/Index', [
            'stats' => [
                'exams' => $totalExams,
                'sessions' => $totalSessions,
                'active_sessions' => $activeSessions,
                'upcoming_sessions' => $upcomingSessions,
                'ended_sessions' => $endedSessions,
                'students' => $distinctStudents,
                'average_grade' => $averageGrade,
            ],
            'distribution' => $distribution,
            'session_status' => $sessionStatus,
            'exam_activity' => $examActivity,
            'recent_sessions' => $recentSessions,
            'system_status' => $systemStatus,
            'is_admin_proxy' => request()->routeIs('admin.dinas.*'),
        ]);
    }
}
