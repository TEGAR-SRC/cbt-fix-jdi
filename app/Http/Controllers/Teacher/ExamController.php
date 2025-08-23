<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Exam;
use App\Models\Lesson;
use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;

class ExamController extends Controller
{
    // Subject scoping removed per requirement: teacher uses admin-managed data

    public function index(Request $request)
    {
        $exams = Exam::with('lesson','classroom')
            ->when($request->q, fn($q) => $q->where('title','like','%'.$request->q.'%'))
            ->latest()->paginate(10)->appends(['q'=>$request->q]);
        return Inertia::render('Teacher/Exams/Index', [
            'exams' => $exams,
            'subject' => $request->user()->subject,
        ]);
    }

    public function create(Request $request)
    {
        $lessons = Lesson::orderBy('title')->get();
        $classrooms = Classroom::orderBy('title')->get();
        return Inertia::render('Teacher/Exams/Create', [
            'subject' => $request->user()->subject,
            'lessons' => $lessons,
            'classrooms' => $classrooms,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'lesson_id' => 'required|integer|exists:lessons,id',
            'classroom_id' => 'required|integer',
            'duration' => 'required|integer',
            'description' => 'required|string',
            'random_question' => 'required|in:Y,N',
            'random_answer' => 'required|in:Y,N',
            'show_answer' => 'required|in:Y,N',
        ]);
        Exam::create($validated);
        return redirect()->route('teacher.exams.index');
    }

    public function show(Request $request, Exam $exam)
    {
        $exam->load('lesson','classroom');
        $exam->setRelation('questions', $exam->questions()->paginate(10));
        return Inertia::render('Teacher/Exams/Show', [
            'exam' => $exam,
            'subject' => $request->user()->subject,
        ]);
    }

    public function edit(Request $request, Exam $exam)
    {
        $lessons = Lesson::orderBy('title')->get();
        $classrooms = Classroom::orderBy('title')->get();
        return Inertia::render('Teacher/Exams/Edit', [
            'exam' => $exam,
            'classrooms' => $classrooms,
            'lessons' => $lessons,
            'subject' => $request->user()->subject,
        ]);
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'lesson_id' => 'required|integer|exists:lessons,id',
            'classroom_id' => 'required|integer',
            'duration' => 'required|integer',
            'description' => 'required|string',
            'random_question' => 'required|in:Y,N',
            'random_answer' => 'required|in:Y,N',
            'show_answer' => 'required|in:Y,N',
        ]);
        $exam->update($validated);
        return redirect()->route('teacher.exams.index');
    }

    public function destroy(Request $request, Exam $exam)
    {
        $exam->delete();
        return back();
    }

    // Questions
    public function createQuestion(Request $request, Exam $exam)
    {
        return Inertia::render('Teacher/Questions/Create', [ 'exam' => $exam ]);
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $request->validate([
            'question'          => 'required',
            'image'             => 'nullable|image|max:5120',
            'audio'             => 'nullable|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/ogg|max:10240',
            'video'             => 'nullable|mimetypes:video/mp4,video/webm,video/ogg|max:51200',
            'option_1'          => 'required',
            'option_2'          => 'required',
            'option_3'          => 'required',
            'option_4'          => 'required',
            'option_5'          => 'required',
            'answer'            => 'required|in:1,2,3,4,5',
        ]);

        $imagePath = null; $audioPath = null; $videoPath = null;
        if ($request->hasFile('image')) { $imagePath = $request->file('image')->store('questions/images', 'public'); }
        if ($request->hasFile('audio')) { $audioPath = $request->file('audio')->store('questions/audios', 'public'); }
        if ($request->hasFile('video')) { $videoPath = $request->file('video')->store('questions/videos', 'public'); }

        Question::create([
            'exam_id'  => $exam->id,
            'question' => $request->question,
            'image_path' => $imagePath,
            'audio_path' => $audioPath,
            'video_path' => $videoPath,
            'option_1' => $request->option_1,
            'option_2' => $request->option_2,
            'option_3' => $request->option_3,
            'option_4' => $request->option_4,
            'option_5' => $request->option_5,
            'answer'   => $request->answer,
        ]);
        return redirect()->route('teacher.exams.show', $exam->id);
    }

    public function editQuestion(Request $request, Exam $exam, Question $question)
    {
        return Inertia::render('Teacher/Questions/Edit', [ 'exam' => $exam, 'question' => $question ]);
    }

    public function updateQuestion(Request $request, Exam $exam, Question $question)
    {
        $request->validate([
            'question'          => 'required',
            'image'             => 'nullable|image|max:5120',
            'audio'             => 'nullable|mimetypes:audio/mpeg,audio/mp3,audio/wav,audio/x-wav,audio/ogg|max:10240',
            'video'             => 'nullable|mimetypes:video/mp4,video/webm,video/ogg|max:51200',
            'option_1'          => 'required',
            'option_2'          => 'required',
            'option_3'          => 'required',
            'option_4'          => 'required',
            'option_5'          => 'required',
            'answer'            => 'required|in:1,2,3,4,5',
        ]);

        $data = [
            'question' => $request->question,
            'option_1' => $request->option_1,
            'option_2' => $request->option_2,
            'option_3' => $request->option_3,
            'option_4' => $request->option_4,
            'option_5' => $request->option_5,
            'answer'   => $request->answer,
        ];

        if ($request->hasFile('image')) {
            if ($question->image_path) { \Storage::disk('public')->delete($question->image_path); }
            $data['image_path'] = $request->file('image')->store('questions/images', 'public');
        }
        if ($request->hasFile('audio')) {
            if ($question->audio_path) { \Storage::disk('public')->delete($question->audio_path); }
            $data['audio_path'] = $request->file('audio')->store('questions/audios', 'public');
        }
        if ($request->hasFile('video')) {
            if ($question->video_path) { \Storage::disk('public')->delete($question->video_path); }
            $data['video_path'] = $request->file('video')->store('questions/videos', 'public');
        }

        $question->update($data);
        return redirect()->route('teacher.exams.show', $exam->id);
    }

    public function destroyQuestion(Request $request, Exam $exam, Question $question)
    {
    if ($question->image_path) { \Storage::disk('public')->delete($question->image_path); }
    if ($question->audio_path) { \Storage::disk('public')->delete($question->audio_path); }
    if ($question->video_path) { \Storage::disk('public')->delete($question->video_path); }
    $question->delete();
        return redirect()->route('teacher.exams.show', $exam->id);
    }

    // Import questions (Excel) similar to admin
    public function import(Request $request, Exam $exam)
    {
        return Inertia::render('Teacher/Questions/Import', [ 'exam' => $exam ]);
    }

    public function storeImport(Request $request, Exam $exam)
    {
        $request->validate(['file' => 'required|mimes:csv,xls,xlsx']);
        Excel::import(new QuestionsImport(), $request->file('file'));
        return redirect()->route('teacher.exams.show', $exam->id);
    }
}
