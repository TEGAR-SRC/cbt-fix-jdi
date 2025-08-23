<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ExamSession;
use App\Models\Grade;
use Illuminate\Http\Request;

class ExamControlController extends Controller
{
    public function index(Request $request)
    {
        $examSessionId = $request->get('exam_session_id');

        $sessions = ExamSession::with('exam.lesson')->latest()->get();

        $grades = Grade::with(['student.classroom', 'exam.lesson', 'exam_session'])
            ->when($examSessionId, fn($q) => $q->where('exam_session_id', $examSessionId))
            ->latest()->paginate(15)->appends(['exam_session_id' => $examSessionId]);

        return inertia('Teacher/ExamControl/Index', [
            'sessions' => $sessions,
            'grades' => $grades,
            'filters' => [
                'exam_session_id' => $examSessionId,
            ],
        ]);
    }
}
