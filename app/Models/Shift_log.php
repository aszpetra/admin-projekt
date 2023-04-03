<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift_log extends Model
{
    use HasFactory;

    protected $casts = [
        'time' => 'datetime:Y-m-d h:m',
    ];

    protected $fillable = [
        'people',
        'time'
    ];
}
