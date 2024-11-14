<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $fillable = [
        'products_name',
        'product_description',
        'parcode',
        'branch',
        'salary',
        'products_image',
    ];
    public $timestamps = false;
}
