<?php

namespace App\Imports;

use App\Models\TryoutQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TryoutQuestionsImport implements ToModel, WithHeadingRow
{
    protected int $tryoutId;
    public function __construct(int $tryoutId)
    {
        $this->tryoutId = $tryoutId;
    }

    public function model(array $row)
    {
        if(!isset($row['question'])) return null;
        return new TryoutQuestion([
            'tryout_id' => $this->tryoutId,
            'question' => $row['question'],
            'option_1' => $row['option_1'] ?? null,
            'option_2' => $row['option_2'] ?? null,
            'option_3' => $row['option_3'] ?? null,
            'option_4' => $row['option_4'] ?? null,
            'option_5' => $row['option_5'] ?? null,
            'answer'   => (string)($row['answer'] ?? null),
            'order'    => (int)($row['order'] ?? 0),
        ]);
    }
}
