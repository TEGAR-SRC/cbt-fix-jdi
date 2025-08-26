<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TryoutResultsExport implements FromCollection, WithMapping, WithHeadings
{
    protected Collection $attempts;

    public function __construct($attempts)
    {
        $this->attempts = collect($attempts);
    }

    public function collection()
    {
        return $this->attempts;
    }

    public function map($attempt): array
    {
        return [
            optional($attempt->tryout)->title,
            optional($attempt->student)->name,
            optional(optional($attempt->tryout)->classroom)->title,
            optional(optional($attempt->tryout)->lesson)->title,
            $attempt->total_correct,
            $attempt->total_questions,
            $attempt->score,
            optional($attempt->started_at)->format('Y-m-d H:i:s'),
            optional($attempt->finished_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'Tryout',
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
