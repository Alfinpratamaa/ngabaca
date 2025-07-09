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
        // Ambil semua user dengan role pelanggan
        $pelangganUsers = User::where('role', 'pelanggan')->get();

        if ($pelangganUsers->isEmpty()) {
            $this->call(UserSeeder::class);
            $pelangganUsers = User::where('role', 'pelanggan')->get();
        }

        if ($pelangganUsers->isNotEmpty()) {
            $statuses = ['Diproses', 'Terpenuhi', 'Batal'];

            // Buat beberapa order dengan variasi user dan data
            foreach ($pelangganUsers as $user) {
                // Setiap user mendapat 2-3 order
                $orderCount = rand(2, 3);

                for ($i = 0; $i < $orderCount; $i++) {
                    Order::create([
                        'user_id' => $user->id,
                        'total_price' => rand(50000, 500000), // Harga random
                        'status' => 'Diproses',
                        // Berikan alamat dummy untuk tampilan yang lebih baik
                        'shipping_address' => $this->getRandomAddress(),
                    ]);
                }
            }

            $this->command->info("Berhasil membuat order untuk " . $pelangganUsers->count() . " pelanggan.");
        } else {
            $this->command->error("User dengan role 'pelanggan' tidak ditemukan. Tidak dapat membuat order.");
        }
    }

    /**
     * Generate random shipping address for testing
     */
    private function getRandomAddress(): string
    {
        $addresses = [
            'Jl. Merdeka No123',
            'Jl. Sudirman No23',
            'Jl. Malioboro No123',
            'Jl. Diponegoro No123',
            'Jl. Ahmad Yani No123',
            'Jl. Gatot Subroto No123',
        ];

        return $addresses[array_rand($addresses)];
    }
}
