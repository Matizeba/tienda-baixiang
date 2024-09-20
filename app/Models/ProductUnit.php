<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductUnit extends Pivot
{
    protected $table = 'product_unit';

    protected $fillable = [
        'product_id',
        'unit_id',
        'price',
        'stock'
    ];
}
