<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'role' => 1, // Admin
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'remember_token' => null,
            ],
            [
                'name' => 'Vendor User',
                'email' => 'vendor@example.com',
                'role' => 2, // Vendor
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
            [
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'role' => 3, // Customer
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
            [
                'name' => 'Test User 1',
                'email' => 'test1@example.com',
                'role' => 1, // Admin
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
            [
                'name' => 'Test User 2',
                'email' => 'test2@example.com',
                'role' => 2, // Vendor
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
            ],
        ];

        // Inserta los datos en la base de datos
        DB::table('users')->insert($users);
    }
}
