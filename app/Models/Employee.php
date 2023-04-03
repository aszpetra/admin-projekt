<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $casts = [
        'born_date' => 'datetime:Y-m-d',
    ];

    protected $fillable = [
        'email',
        'born_date',
        'phone',
        'city',
        'address',
        'company_id',
        'is_efo'
    ];
}
