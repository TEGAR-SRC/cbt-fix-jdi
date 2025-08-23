<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuestionsExport implements FromCollection, WithMapping, WithHeadings
{
    protected $questions;

    public function __construct($questions)
    {
        $this->questions = $questions;
    }

    public function collection()
    {
        return $this->questions;
    }

    public function map($q): array
    {
        return [
            $q->exam_id,
            strip_tags($q->question),
            $q->option_1,
            $q->option_2,
            $q->option_3,
            $q->option_4,
            $q->option_5,
            $q->answer,
        ];
    }

    public function headings(): array
    {
        return ['exam_id','question','option_1','option_2','option_3','option_4','option_5','answer'];
    }
}
