<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Ejecutar las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Dumplings',
                'description' => 'Empanadillas chinas tradicionales rellenas de cerdo y verduras.',
                'quantity' => 150,
                'price' => 12.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 1, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pato Pekín',
                'description' => 'Plato famoso chino con piel de pato crujiente y carne tierna.',
                'quantity' => 50,
                'price' => 29.99,
                'category'=> 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 2, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rollos Primavera',
                'description' => 'Rollos crujientes rellenos de una mezcla de verduras y carne.',
                'quantity' => 200,
                'price' => 8.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 3, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pollo agridulce',
                'description' => 'Trozos de pollo cubiertos con una salsa agridulce.',
                'quantity' => 80,
                'price' => 15.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 4, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pollo Kung Pao',
                'description' => 'Pollo salteado con cacahuetes, verduras y salsa picante.',
                'quantity' => 60,
                'price' => 17.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 5, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arroz Frito',
                'description' => 'Arroz salteado con verduras, huevo y carne.',
                'quantity' => 120,
                'price' => 10.49,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 1, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sopa de Rollos de Primavera',
                'description' => 'Sopa con sabor a rollos primavera crujientes y suculentos.',
                'quantity' => 90,
                'price' => 11.49,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 2, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tallarines Chinos',
                'description' => 'Tallarines salteados con verduras y carne en salsa de soja.',
                'quantity' => 75,
                'price' => 13.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 3, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cerdo a la Barbacoa',
                'description' => 'Cerdo cocido lentamente con salsa barbacoa.',
                'quantity' => 40,
                'price' => 19.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 4, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Costillas a la Sichuan',
                'description' => 'Costillas de cerdo con un toque picante y especiado.',
                'quantity' => 30,
                'price' => 22.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 5, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mapo Tofu',
                'description' => 'Tofu cocido en una salsa picante de frijoles.',
                'quantity' => 85,
                'price' => 14.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 1, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dim Sum',
                'description' => 'Selección de pequeñas porciones de comida china tradicional.',
                'quantity' => 110,
                'price' => 16.99,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 2, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pollo al Estilo Cantones',
                'description' => 'Pollo salteado con verduras y salsa cantonesa.',
                'quantity' => 70,
                'price' => 18.49,
                'category' => 1, // Asegúrate de que el ID de la categoría coincida con el ID de la categoría "Food"
                'status' => 1,
                'userId' => 3, // Asegúrate de que el ID del usuario exista en la tabla users
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
