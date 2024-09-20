<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Producto 1',
                'description' => 'Descripción del producto 1.',
                'image' => 'products/p1.png',
                'category_id' => 1,
                'status' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Producto 2',
                'description' => 'Descripción del producto 2.',
                'image' => 'path/to/image2.jpg',
                'category_id' => 2,
                'status' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agrega más productos según sea necesario
        ];

        foreach ($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
