<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Unidad', 'description' => 'Cada uno', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Paquete', 'description' => 'Contenido de varios', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Caja', 'description' => 'Caja completa', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($units as $unit) {
            DB::table('units')->insert($unit);
        }
    }
}
