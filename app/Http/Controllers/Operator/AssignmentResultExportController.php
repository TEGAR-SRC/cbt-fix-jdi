<?php
namespace App\Http\Controllers\Operator;
use App\Http\Controllers\Controller;
use App\Models\{Assignment, AssignmentSubmission};
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssignmentResultExportController extends Controller
{
    public function __invoke(Assignment $assignment)
    {
        $filename = 'assignment-results-operator-'.$assignment->id.'-'.now()->format('Ymd_His').'.csv';
        $response = new StreamedResponse(function() use ($assignment){
            $out = fopen('php://output','w');
            fputcsv($out,['Student','Started At','Finished At','Correct','Total','Score']);
            AssignmentSubmission::with('student')
                ->where('assignment_id',$assignment->id)
                ->orderByDesc('finished_at')
                ->chunk(200, function($rows) use ($out){
                    foreach($rows as $s){
                        fputcsv($out,[
                            $s->student->name ?? '-',
                            optional($s->started_at)->format('Y-m-d H:i:s'),
                            optional($s->finished_at)->format('Y-m-d H:i:s'),
                            $s->total_correct,
                            $s->total_questions,
                            $s->score,
                        ]);
                    }
                });
            fclose($out);
        });
        $response->headers->set('Content-Type','text/csv');
        $response->headers->set('Content-Disposition','attachment; filename="'.$filename.'"');
        return $response;
    }
}
