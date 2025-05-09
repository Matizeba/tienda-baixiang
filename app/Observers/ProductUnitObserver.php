<?php

namespace App\Observers;

use App\Models\ProductUnit;
use App\Models\Alert;

class ProductUnitObserver
{
    /**
     * Handle the ProductUnit "created" event.
     */
    public function created(ProductUnit $productUnit): void
    {
        //
    }

    /**
     * Handle the ProductUnit "updated" event.
     */
    public function updated(ProductUnit $productUnit)
    {
        // Verificar si el stock bajó a menos de 10
        if ($productUnit->stock < 10) {
            // Comprobar si ya existe una alerta activa (no vista) para este producto y unidad
            $existingAlert = Alert::where('product_unit_id', $productUnit->id)
                ->where('status', 0) // Solo alertas no vistas
                ->first();

            if (!$existingAlert) {
                // Crear una nueva alerta
                Alert::create([
                    'product_unit_id' => $productUnit->id,
                    'title' => 'Stock bajo',
                    'message' => "El producto {$productUnit->product->name} tiene un stock bajo ({$productUnit->stock} unidades).",
                    'type' => 'warning',
                    'status' => 0, // No vista por defecto
                ]);
            }
        }
    }

    /**
     * Handle the ProductUnit "deleted" event.
     */
    public function deleted(ProductUnit $productUnit): void
    {
        //
    }

    /**
     * Handle the ProductUnit "restored" event.
     */
    public function restored(ProductUnit $productUnit): void
    {
        //
    }

    /**
     * Handle the ProductUnit "force deleted" event.
     */
    public function forceDeleted(ProductUnit $productUnit): void
    {
        //
    }
}
