<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            [
                'name' => 'Dim Sum',
                'description' => 'Pequeños bocados tradicionales de la cocina cantonesa, como dumplings y buns.',
                'userId' => 2, // Ajusta el ID del usuario según tu base de datos
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Noodles',
                'description' => 'Platos de fideos chinos, incluyendo variedades como chow mein y lo mein.',
                'userId' => 3, // Ajusta el ID del usuario según tu base de datos
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dumplings',
                'description' => 'Empanadillas rellenas, una especialidad de la comida china que puede ser al vapor, hervida o frita.',
                'userId' => 1, // Ajusta el ID del usuario según tu base de datos
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peking Duck',
                'description' => 'Plato emblemático de la cocina china, conocido por su piel crujiente y carne jugosa.',
                'userId' => 5, // Ajusta el ID del usuario según tu base de datos
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sweet and Sour',
                'description' => 'Platos con una combinación clásica de sabores dulces y ácidos, como el cerdo agridulce.',
                'userId' => 1, // Ajusta el ID del usuario según tu base de datos
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
