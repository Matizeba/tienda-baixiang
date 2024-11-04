<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    // Especifica los campos que pueden ser asignados masivamente
    protected $fillable = ['user_id', 'customer_id', 'tipe_sale','total_amount', 'status'];

    // Relación con los detalles de la venta (muchos detalles para una venta)
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    // Relación con el usuario que creó la venta (usuario asociado a la venta)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con el cliente (cliente que realiza la compra)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function details()
    {
        return $this->hasMany(SaleDetail::class, 'sale_id');
    }

    public function scopeOnlyTypeSale($query, $type)
    {
        return $query->where('tipe_sale', $type);
    }

}
