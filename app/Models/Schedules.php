<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;

    protected $fillable = [
        'day', 
        'start_time',   
        'end_time', 
        'room_id',
        'section_id',
        'subject_id', 
        'type', 
        'college',
        'department',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
