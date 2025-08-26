<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\Assignment;
use App\Models\Tryout;
use App\Models\TryoutAttempt;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        // Assume we store teacher's subject in users.subject (simple approach). If missing, default to null.
    $subject = $user->subject ?? null;
    $lessonExists = $subject ? Lesson::where('title', $subject)->exists() : false;

        // Exams filtered by teacher subject (list limited)
        $exams = Exam::with('lesson')
            ->when($subject, function ($q) use ($subject) {
                $q->whereHas('lesson', function ($qq) use ($subject) { $qq->where('title', $subject); });
            })
            ->latest()->limit(8)->get()->map(function($e){
                return [
                    'id' => $e->id,
                    'title' => $e->title,
                    'lesson' => optional($e->lesson)->title,
                    'created_at' => optional($e->created_at)->diffForHumans(),
                ];
            });

        // Basic counts (scoped if subject exists)
        $examCount = Exam::when($subject, function ($q) use ($subject) { $q->whereHas('lesson', fn($qq)=>$qq->where('title',$subject)); })->count();
        $questionCount = Question::when($subject, function ($q) use ($subject) { $q->whereHas('exam.lesson', fn($qq)=>$qq->where('title',$subject)); })->count();
        $assignmentCount = Assignment::when($subject, function ($q) use ($subject) { $q->whereHas('lesson', fn($qq)=>$qq->where('title',$subject)); })->count();
        $tryoutCount = Tryout::when($subject, function ($q) use ($subject) { $q->whereHas('lesson', fn($qq)=>$qq->where('title',$subject)); })->count();

        $now = Carbon::now();
        $activeSessions = ExamSession::when($subject, function ($q) use ($subject) { $q->whereHas('exam.lesson', fn($qq)=>$qq->where('title',$subject)); })
            ->where('start_time','<=', $now)
            ->where('end_time','>=', $now)
            ->count();
        $endedToday = ExamSession::when($subject, function ($q) use ($subject) { $q->whereHas('exam.lesson', fn($qq)=>$qq->where('title',$subject)); })
            ->whereDate('end_time', $now->toDateString())
            ->where('end_time','<',$now)
            ->count();
        $sessionStatus = [ 'active'=>$activeSessions, 'ended'=>$endedToday ];

        // Last 7 day activity (ended sessions + tryout attempts) scoped
        $examActivity = [];
        for($i=6;$i>=0;$i--){
            $day = $now->copy()->subDays($i)->toDateString();
            $sessionsFinished = ExamSession::when($subject, function ($q) use ($subject) { $q->whereHas('exam.lesson', fn($qq)=>$qq->where('title',$subject)); })
                ->whereDate('end_time',$day)->count();
            $tryoutsFinished = TryoutAttempt::when($subject, function($q) use ($subject){ $q->whereHas('tryout.lesson', fn($qq)=>$qq->where('title',$subject)); })
                ->whereDate('created_at',$day)->count();
            $examActivity[] = [ 'date'=>substr($day,5), 'exams'=>$sessionsFinished, 'tryouts'=>$tryoutsFinished ];
        }

        $upcoming = ExamSession::with(['exam.lesson'])
            ->when($subject, function ($q) use ($subject) { $q->whereHas('exam.lesson', fn($qq)=>$qq->where('title',$subject)); })
            ->orderBy('start_time','asc')->limit(5)->get()->map(function($s){
                return [
                    'id' => $s->id,
                    'title' => $s->title,
                    'exam' => $s->exam?->title,
                    'lesson' => $s->exam?->lesson?->title,
                    'start_time' => optional($s->start_time)->toDateTimeString(),
                ];
            });

        // recent activity (sessions + exams created) scoped
        $recentSessions = ExamSession::when($subject, function ($q) use ($subject) { $q->whereHas('exam.lesson', fn($qq)=>$qq->where('title',$subject)); })
            ->orderBy('created_at','desc')->limit(5)->get()->map(function($s){
                return [ 'type'=>'session','title'=>'Sesi: '.($s->title ?: 'Tanpa Judul'),'time'=>$s->created_at?->diffForHumans(),'status'=>'Created' ];
            });
        $recentExams = Exam::when($subject, function ($q) use ($subject) { $q->whereHas('lesson', fn($qq)=>$qq->where('title',$subject)); })
            ->orderBy('created_at','desc')->limit(5)->get()->map(function($e){
                return [ 'type'=>'exam','title'=>'Ujian: '.($e->title ?: 'Tanpa Judul'),'time'=>$e->created_at?->diffForHumans(),'status'=>'Created' ];
            });
        $recentActivity = $recentSessions->merge($recentExams)->sortByDesc('time')->take(7)->values();

        // initial lightweight status (reuse admin endpoint on frontend for realtime if desired)
        $systemStatus = [ 'database' => true, 'websocket' => false, 'anti_cheat' => true, 'storage_used' => 0.5, 'last_backup' => '—' ];

        // global student count (or could be scoped later)
        $students = Student::count();

        return Inertia::render('Teacher/Dashboard/Index', [
            'subject' => $subject,
            'lessonExists' => $lessonExists,
            'students' => $students,
            'exams' => $examCount,
            'questions' => $questionCount,
            'assignments' => $assignmentCount,
            'tryouts' => $tryoutCount,
            'active_sessions' => $activeSessions,
            'ended_sessions_today' => $endedToday,
            'session_status' => $sessionStatus,
            'exam_activity' => $examActivity,
            'upcoming_exams' => $upcoming, // keep prop name same as admin for reuse
            'system_status' => $systemStatus,
            'recent_activity' => $recentActivity,
        ]);
    }
}
