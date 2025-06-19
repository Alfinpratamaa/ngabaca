<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $pelangganUser = User::where('role', 'pelanggan')->first();

        if (!$pelangganUser) {
            $this->call(UserSeeder::class); // Pastikan UserSeeder dijalankan duluan
            $pelangganUser = User::where('role', 'pelanggan')->first();
        }

        if ($pelangganUser) {
        if ($pelangganUser) {
            $statuses = ['pending', 'completed', 'cancelled'];
            Order::create([
            'user_id' => $pelangganUser->id,
            'total_amount' => 250000.00,
            'status' => $statuses[array_rand($statuses)],
            'shipping_address' => 'Jl. Contoh No. 123, Kota Fiktif, Negara Imajinasi',
            ]);

            Order::create([
            'user_id' => $pelangganUser->id,
            'total_amount' => 150000.00,
            'status' => $statuses[array_rand($statuses)],
            'shipping_address' => 'Jl. Lain No. 45, Desa Khayalan, Provinsi Fiksi',
            ]);
        } else {
            $this->command->error("User dengan role 'pelanggan' tidak ditemukan. Tidak dapat membuat order.");
        }
    }
}
}
