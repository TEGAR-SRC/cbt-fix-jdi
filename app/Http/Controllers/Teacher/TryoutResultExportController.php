<?php
namespace App\Http\Controllers\Teacher;
use App\Http\Controllers\Controller;
use App\Models\{Tryout, TryoutAttempt};
use Symfony\Component\HttpFoundation\StreamedResponse;

class TryoutResultExportController extends Controller
{
    public function __invoke(Tryout $tryout)
    {
        $filename = 'tryout-results-teacher-'.$tryout->id.'-'.now()->format('Ymd_His').'.csv';
        $response = new StreamedResponse(function() use ($tryout){
            $out = fopen('php://output','w');
            fputcsv($out,['Student','Started At','Finished At','Correct','Total','Score']);
            TryoutAttempt::with('student')
                ->where('tryout_id',$tryout->id)
                ->orderByDesc('finished_at')
                ->chunk(200, function($rows) use ($out){
                    foreach($rows as $a){
                        fputcsv($out,[
                            $a->student->name ?? '-',
                            optional($a->started_at)->format('Y-m-d H:i:s'),
                            optional($a->finished_at)->format('Y-m-d H:i:s'),
                            $a->total_correct,
                            $a->total_questions,
                            $a->score,
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
