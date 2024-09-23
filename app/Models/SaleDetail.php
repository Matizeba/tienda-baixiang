<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $fillable = ['product_id', 'unit_id', 'quantity', 'price', 'total', 'sale_id'];


    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id'); // Asegúrate de que 'unit_id' es el nombre correcto de la columna
    }
}
