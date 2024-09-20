<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'quantity', 'category_id', 'status', 'image', 'user_id'];

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
