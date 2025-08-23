<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\ExamSession;
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
            'database' => true,
            'websocket' => false,
            'anti_cheat' => true,
            'storage_used' => 0.75, // placeholder 75%
            'last_backup' => '2 hours ago',
        ];

        return inertia('Admin/Dashboard/Index', [
            'students'        => $students,
            'exams'           => $exams,
            'exam_sessions'   => $exam_sessions,
            'classrooms'      => $classrooms,
            'upcoming_exams'  => $upcoming,
            'system_status'   => $systemStatus,
        ]);
    }
}