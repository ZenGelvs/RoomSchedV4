<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name', 
        'year_level',   
        'section',
        'college',
        'department',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'section_subject', 'section_id', 'subject_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedules::class, 'section_id');
    }
}
