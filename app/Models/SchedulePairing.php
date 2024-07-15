<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulePairing extends Model
{
    use HasFactory;

    protected $fillable = ['days'];

    protected $casts = [
        'days' => 'array',
    ];

}
