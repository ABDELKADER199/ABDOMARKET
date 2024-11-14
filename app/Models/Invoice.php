<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $table = 'reseiptes_item';
    protected $fillable = [
        'product_name',
        'product_parcode',
        'product_price',
        'quantity',
        'total_item',
        'total'
    ];
}
