<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $students = Student::count();
        $exams = Exam::count();
        $exam_sessions = ExamSession::count();
        $classrooms = Classroom::count();

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

        $systemStatus = [
            'database' => true,
            'websocket' => false,
            'anti_cheat' => true,
            'storage_used' => 0.75,
            'last_backup' => '2 hours ago',
        ];

        return inertia('Operator/Dashboard/Index', [
            'students' => $students,
            'exams' => $exams,
            'exam_sessions' => $exam_sessions,
            'classrooms' => $classrooms,
            'upcoming_exams' => $upcoming,
            'system_status' => $systemStatus,
        ]);
    }
}
