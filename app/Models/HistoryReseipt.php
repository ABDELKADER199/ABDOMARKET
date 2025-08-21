<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryReseipt extends Model
{
    use HasFactory;
    protected $table = 'cashierview';
    public $timestamps = false;

    protected $fillable = [
        'locker_id',
        'reseipte_id',
        'reseipte_date',
        'product_name',
        'product_parcode',
        'product_price',
        'total',
        'locker_status',
        'locker_total_cash',
        'locker_total_computer',
        'locker_deficit'
    ];
}
