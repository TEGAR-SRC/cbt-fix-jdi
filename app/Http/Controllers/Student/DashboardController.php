<?php

namespace App\Http\Controllers\Student;

use App\Models\Grade;
use App\Models\ExamGroup;
use App\Models\Assignment;
use App\Models\Tryout;
use App\Models\AssignmentSubmission;
use App\Models\TryoutAttempt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        //get exam groups
        $exam_groups = ExamGroup::with('exam.lesson', 'exam_session', 'student.classroom')
            ->where('student_id', auth()->guard('student')->user()->id)
            ->get();

        //define variable array
        $data = [];

        //get nilai
        foreach($exam_groups as $exam_group) {
            
            //get data nilai / grade
            $grade = Grade::where('exam_id', $exam_group->exam_id)
                ->where('exam_session_id', $exam_group->exam_session_id)
                ->where('student_id', auth()->guard('student')->user()->id)
                ->first();

            //jika nilai / grade kosong, maka buat baru
            if($grade == null) {

                //create defaul grade
                $grade = new Grade();
                $grade->exam_id         = $exam_group->exam_id;
                $grade->exam_session_id = $exam_group->exam_session_id;
                $grade->student_id      = auth()->guard('student')->user()->id;
                $grade->duration        = $exam_group->exam->duration * 60000;
                $grade->total_correct   = 0;
                $grade->grade           = 0;
                $grade->save();

            }

            $data[] = [
                'exam_group' => $exam_group,
                'grade'      => $grade
            ];

        }

        $student = auth()->guard('student')->user();
        // assignments (published for student's classroom)
        $assignments = Assignment::with('lesson','classroom')
            ->where('classroom_id', $student->classroom_id)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->take(10)
            ->get()
            ->map(function($a) use ($student){
                $a->submission = AssignmentSubmission::where('assignment_id',$a->id)->where('student_id',$student->id)->first();
                return $a;
            });

        // tryouts (published for student's classroom)
        $tryouts = Tryout::with('lesson','classroom')
            ->where('classroom_id', $student->classroom_id)
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->take(10)
            ->get()
            ->map(function($t) use ($student){
                $t->attempt = TryoutAttempt::where('tryout_id',$t->id)->where('student_id',$student->id)->first();
                return $t;
            });

        //return with inertia including new categories
        return inertia('Student/Dashboard/Index', [
            'exam_groups' => $data,
            'assignments' => $assignments,
            'tryouts' => $tryouts,
        ]);
    }
}