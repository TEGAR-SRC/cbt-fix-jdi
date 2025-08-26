<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssignmentResultsExport implements FromCollection, WithMapping, WithHeadings
{
    protected Collection $submissions;

    public function __construct($submissions)
    {
        $this->submissions = collect($submissions);
    }

    public function collection()
    {
        return $this->submissions;
    }

    public function map($submission): array
    {
        return [
            optional($submission->assignment)->title,
            optional($submission->student)->name,
            optional(optional($submission->assignment)->classroom)->title,
            optional(optional($submission->assignment)->lesson)->title,
            $submission->total_correct,
            $submission->total_questions,
            $submission->score,
            optional($submission->started_at)->format('Y-m-d H:i:s'),
            optional($submission->finished_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'Tugas Harian',
            'Nama Siswa',
            'Kelas',
            'Pelajaran',
            'Benar',
            'Total Soal',
            'Nilai',
            'Mulai',
            'Selesai',
        ];
    }
}
