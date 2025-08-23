<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithMapping, WithHeadings
{
    protected $students;

    public function __construct($students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students;
    }

    public function map($student): array
    {
        return [
            $student->name,
            $student->nisn,
            optional($student->classroom)->title,
            $student->gender,
        ];
    }

    public function headings(): array
    {
        return ['Nama', 'NISN', 'Kelas', 'Jenis Kelamin'];
    }
}
