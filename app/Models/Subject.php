<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'Subjects';

    protected $fillable = [
        'Subject_Code',
        'Description',
        'Lec',
        'Lab',
        'Units',
        'Pre_Req',
        'Year_Level',
        'Semester',
        'College',
        'Department',
        'Program',
        'Academic_Year'
    ];

}
