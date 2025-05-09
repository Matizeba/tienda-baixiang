<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productUnits = [
            ['product_id' => 1, 'unit_id' => 1, 'price' => 120.00, 'stock' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 1, 'unit_id' => 2, 'price' => 110.00, 'stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'unit_id' => 1, 'price' => 80.00, 'stock' => 30, 'created_at' => now(), 'updated_at' => now()],
            // Agrega más relaciones según sea necesario
        ];

        foreach ($productUnits as $productUnits) {
            DB::table('product_units')->insert($productUnits);
        }
    }
}
