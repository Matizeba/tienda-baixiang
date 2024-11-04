<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Ejecutar las semillas de la base de datos.
     *
     * @return void
     */
    public function run()
    {
        // Crear usuarios de ejemplo
        DB::table('users')->insert([
            [
                'name' => 'Carlos Alberto',
                'first_surname' => 'García',
                'second_surname' => 'López',
                'ci' => '12345678',
                'phone' => '3001234567',
                'email' => 'admin@admin.com',
                'role' => '1',
                'userid' => 1,
                'status' => 1,
                'password' => Hash::make('12345678'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'María Fernanda',
                'first_surname' => 'Martínez',
                'second_surname' => 'Pérez',
                'ci' => '23456789',
                'phone' => '3009876543',
                'email' => 'mmartinez@example.com',
                'role' => '2',
                'userid' => 2,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'José Luis',
                'first_surname' => 'Ramírez',
                'second_surname' => 'Hernández',
                'ci' => '34567890',
                'phone' => '3012345678',
                'email' => 'jramirez@example.com',
                'role' => '1',
                'userid' => 1,
                'status' => 1,
                'password' => Hash::make('12345678'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ana Isabel',
                'first_surname' => 'González',
                'second_surname' => 'Moreno',
                'ci' => '45678901',
                'phone' => '3023456789',
                'email' => 'agonzalez@example.com',
                'role' => '3',
                'userid' => 3,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Roberto Carlos',
                'first_surname' => 'Hernández',
                'second_surname' => 'Castillo',
                'ci' => '56789012',
                'phone' => '3034567890',
                'email' => 'rhernandez@example.com',
                'role' => '2',
                'userid' => 4,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lucía María',
                'first_surname' => 'Serrano',
                'second_surname' => 'Salazar',
                'ci' => '67890123',
                'phone' => '3045678901',
                'email' => 'lserrano@example.com',
                'role' => '3',
                'userid' => 5,
                'status' => 1,
                'password' => Hash::make('password123'),
                'passwordUpdate'=> true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
