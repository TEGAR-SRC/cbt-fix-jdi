<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Lesson;
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

        // Join exams with lessons and filter by lesson title matching subject
        $exams = Exam::with('lesson')
            ->when($subject, function ($q) use ($subject) {
                $q->whereHas('lesson', function ($qq) use ($subject) {
                    $qq->where('title', $subject);
                });
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'title' => $exam->title,
                    'lesson' => optional($exam->lesson)->title,
                    'created_at' => optional($exam->created_at)->format('Y-m-d'),
                ];
            });

        return Inertia::render('Teacher/Dashboard/Index', [
            'subject' => $subject,
            'exams' => $exams,
            'lessonExists' => $lessonExists,
        ]);
    }
}
