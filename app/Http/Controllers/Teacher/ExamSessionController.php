<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Student;
use App\Models\ExamGroup;
use App\Models\ExamSession;
use Illuminate\Http\Request;

class ExamSessionController extends Controller
{
    public function index()
    {
        $exam_sessions = ExamSession::when(request()->q, function ($exam_sessions) {
            $exam_sessions = $exam_sessions->where('title', 'like', '%' . request()->q . '%');
        })->with('exam.classroom', 'exam.lesson', 'exam_groups')->latest()->paginate(5);

        $exam_sessions->appends(['q' => request()->q]);

        return inertia('Teacher/ExamSessions/Index', [
            'exam_sessions' => $exam_sessions,
        ]);
    }

    public function create()
    {
        $exams = Exam::all();
        return inertia('Teacher/ExamSessions/Create', [
            'exams' => $exams,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'exam_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        ExamSession::create([
            'title' => $request->title,
            'exam_id' => $request->exam_id,
            'start_time' => date('Y-m-d H:i:s', strtotime($request->start_time)),
            'end_time' => date('Y-m-d H:i:s', strtotime($request->end_time)),
        ]);

    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.exam_sessions.index');
    }

    public function show($id)
    {
        $exam_session = ExamSession::with('exam.classroom', 'exam.lesson')->findOrFail($id);
        $exam_session->setRelation('exam_groups', $exam_session->exam_groups()->with('student.classroom')->paginate(5));

        return inertia('Teacher/ExamSessions/Show', [
            'exam_session' => $exam_session,
        ]);
    }

    public function edit($id)
    {
        $exam_session = ExamSession::findOrFail($id);
        $exams = Exam::all();
        return inertia('Teacher/ExamSessions/Edit', [
            'exam_session' => $exam_session,
            'exams' => $exams,
        ]);
    }

    public function update(Request $request, ExamSession $exam_session)
    {
        $request->validate([
            'title' => 'required',
            'exam_id' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $exam_session->update([
            'title' => $request->title,
            'exam_id' => $request->exam_id,
            'start_time' => date('Y-m-d H:i:s', strtotime($request->start_time)),
            'end_time' => date('Y-m-d H:i:s', strtotime($request->end_time)),
        ]);

    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.exam_sessions.index');
    }

    public function destroy($id)
    {
        $exam_session = ExamSession::findOrFail($id);
        $exam_session->delete();
        return redirect()->route('teacher.exam_sessions.index');
    }

    public function createEnrolle(ExamSession $exam_session)
    {
        $exam = $exam_session->exam;
        $students_enrolled = ExamGroup::where('exam_id', $exam->id)->where('exam_session_id', $exam_session->id)->pluck('student_id')->all();
        $students = Student::with('classroom')->where('classroom_id', $exam->classroom_id)->whereNotIn('id', $students_enrolled)->get();

        return inertia('Teacher/ExamGroups/Create', [
            'exam' => $exam,
            'exam_session' => $exam_session,
            'students' => $students,
        ]);
    }

    public function storeEnrolle(Request $request, ExamSession $exam_session)
    {
        $request->validate([
            'student_id' => 'required',
        ]);

        foreach ($request->student_id as $student_id) {
            $student = Student::findOrFail($student_id);
            ExamGroup::create([
                'exam_id' => $request->exam_id,
                'exam_session_id' => $exam_session->id,
                'student_id' => $student->id,
            ]);
        }

    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.exam_sessions.show', $exam_session->id);
    }

    public function destroyEnrolle(ExamSession $exam_session, ExamGroup $exam_group)
    {
        $exam_group->delete();
    $prefix = str_starts_with($request->path(), 'operator/') ? 'operator' : 'teacher';
    return redirect()->route($prefix.'.exam_sessions.show', $exam_session->id);
    }
}
