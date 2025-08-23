<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\Grade;
use App\Models\Answer;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index(Request $request)
    {
        $examSessionId = $request->get('exam_session_id');

        $sessions = ExamSession::with('exam.lesson')->latest()->get();

        $query = Grade::with(['student.classroom', 'exam.lesson', 'exam_session'])
            ->when($examSessionId, function ($q) use ($examSessionId) {
                $q->where('exam_session_id', $examSessionId);
            });

        // statuses: not started (start_time null), in progress (start_time set, end_time null), finished (end_time set)
        $inProgressCount = (clone $query)->whereNotNull('start_time')->whereNull('end_time')->count();
        $finishedCount   = (clone $query)->whereNotNull('end_time')->count();
        $notStartedCount = (clone $query)->whereNull('start_time')->count();

        $grades = $query->latest()->paginate(10);
        $grades->appends(['exam_session_id' => $examSessionId]);

        return inertia('Admin/Monitor/Index', [
            'sessions' => $sessions,
            'grades' => $grades,
            'stats' => [
                'in_progress' => $inProgressCount,
                'finished' => $finishedCount,
                'not_started' => $notStartedCount,
            ],
            'filters' => [
                'exam_session_id' => $examSessionId,
            ],
        ]);
    }

    public function show(Grade $grade)
    {
        $grade->load(['student.classroom', 'exam.lesson', 'exam_session']);

        // fetch answers with question info, ordered by question_order
        $answers = Answer::with('question')
            ->where('exam_id', $grade->exam_id)
            ->where('exam_session_id', $grade->exam_session_id)
            ->where('student_id', $grade->student_id)
            ->orderBy('question_order')
            ->get();

        return inertia('Admin/Monitor/Show', [
            'grade' => $grade,
            'answers' => $answers,
        ]);
    }

    public function unlock(Grade $grade)
    {
        // allow admin to unlock exited status so student can resume
        $grade->status = null;
        $grade->save();
    return back()->with('success', 'Ujian berhasil dibuka untuk siswa.');
    }

    public function stop(Grade $grade)
    {
        // mark as exited to stop the exam
        $grade->status = 'exited';
        $grade->save();
    return back()->with('success', 'Ujian berhasil dihentikan untuk siswa.');
    }

    public function reopen(Grade $grade)
    {
        // Allow admin to reopen a finished or exited attempt
        // Reset finish state and monitoring flags; keep existing answers and grade values for continuity
        $grade->end_time = null;
        $grade->status = null; // allow resume
        // If desired, admin can also reset duration to remaining time; here we keep current duration as-is.
        // Also reset current question to 1 for a clean resume (optional; comment out if not desired)
        $grade->current_question = 1;
        $grade->last_activity_at = now();
        $grade->save();

    return back()->with('success', 'Ujian yang sudah selesai berhasil dibuka kembali.');
    }

    public function addTime(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'extra_minutes' => 'required|integer|min:1|max:180',
        ]);

        // duration stored in ms; add minutes
        $grade->duration = (int) $grade->duration + ((int) $validated['extra_minutes'] * 60000);
        // flag in session for student to see a toast via shared props
        session()->put("grade_{$grade->id}_extra_time_added", $validated['extra_minutes']);
        $grade->save();

        return back()->with('success', "Waktu ditambah {$validated['extra_minutes']} menit.");
    }
}
