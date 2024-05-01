<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty';

    protected $fillable = ['name', 'faculty_id', 'college', 'department'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_faculty', 'faculty_id', 'subject_id');
    }

}
