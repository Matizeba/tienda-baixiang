<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleDetailsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('sale_details')->insert([
            [
                'sale_id' => 1, // Referencia a la venta existente
                'product_id' => 1, // Referencia a un producto existente
                'unit_id' => 1, // Referencia a una unidad existente
                'quantity' => 1,
                'price' => 100.00,
                'total' => 200.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sale_id' => 1,
                'product_id' => 1,
                'unit_id' => 1,
                'quantity' => 1,
                'price' => 100.00,
                'total' => 100.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'sale_id' => 1,
                'product_id' => 2,
                'unit_id' => 1,
                'quantity' => 1,
                'price' => 150.00,
                'total' => 150.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
