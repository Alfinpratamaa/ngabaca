<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), 
            'role' => 'admin',
        ]);

         User::create([
            'name' => 'Pelanggan Biasa',
            'email' => 'pelanggan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'pelanggan',
        ]);
    }
}
