<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    use HasFactory;

    protected $table = 'locker';
    protected $fillable = [
        'employees_id',
        'status',
        'total_computer',
        'total_cash',
        'visa',
        'Deficit',
    ];

}
