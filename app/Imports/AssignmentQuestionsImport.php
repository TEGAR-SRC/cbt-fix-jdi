<?php

namespace App\Imports;

use App\Models\AssignmentQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssignmentQuestionsImport implements ToModel, WithHeadingRow
{
    protected int $assignmentId;

    public function __construct(int $assignmentId)
    {
        $this->assignmentId = $assignmentId;
    }

    public function model(array $row)
    {
        // Expected headings: question, option_1..option_5, answer, order
        return new AssignmentQuestion([
            'assignment_id' => $this->assignmentId,
            'question'      => $row['question'] ?? '',
            'option_1'      => $row['option_1'] ?? null,
            'option_2'      => $row['option_2'] ?? null,
            'option_3'      => $row['option_3'] ?? null,
            'option_4'      => $row['option_4'] ?? null,
            'option_5'      => $row['option_5'] ?? null,
            'answer'        => $row['answer'] ?? null,
            'order'         => isset($row['order']) ? (int) $row['order'] : null,
        ]);
    }
}
