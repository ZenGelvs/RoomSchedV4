<?php
namespace App\Imports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Subject ([
            'Subject_Code' => $row['subject_code'],
            'Description' => $row['description'],
            'Lec' => $row['lec'],
            'Lab' => $row['lab'],
            'Units' => $row['units'],
            'Pre-Req' => $row['pre_req'],
            'Year_Level' => $row['year_level'],
            'Semester' => $row['semester'],
            'College' => $row['college'],
            'Department' => $row['department'],
            'Program' => $row['program'],
            'Academic_Year' => $row['academic_year'],
            
        ]);
    }
}
