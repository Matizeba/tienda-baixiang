<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalesSeeder extends Seeder
{
    public function run()
    {
        // Insertar datos en la tabla sales
        DB::table('sales')->insert([
            ['user_id' => 1, 'customer_id' => 2, 'total_amount' => 300.00, 'status' => 'completed', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 1, 'customer_id' => 2, 'total_amount' => 200.00, 'status' => 'completed', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
