<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_unit_id', 
        'title', 
        'message', 
        'type', 
        'status'
    ];

    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class);
    }
}
