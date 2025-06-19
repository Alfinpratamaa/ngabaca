<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderWithNoPayment = Order::has('payment', '=', 0)->first();
        $adminUser = User::where('role', 'admin')->first();

        if (!$orderWithNoPayment || !$adminUser) {
            $this->command->warn("Tidak cukup Order tanpa Payment atau User admin untuk membuat Payment. Jalankan OrderSeeder dan UserSeeder terlebih dahulu.");
            return;
        }

        Payment::create([
            'order_id' => $orderWithNoPayment->id,
            'transaction_id' => 'TRX-' . uniqid(),
            'amount' => $orderWithNoPayment->total_amount,
            'currency' => 'IDR',
            'payment_method' => 'bank_transfer',
            'proof_url' => 'https://example.com/proof/transfer_abc.jpg',
            'status' => 'pending',
            'payment_status_gateway' => null,
            'payment_gateway_response' => null,
            'verified_at' => null,
            'verified_by' => null,
        ]);

        $orderWithNoPayment2 = Order::has('payment', '=', 0)->skip(1)->first();

        if ($orderWithNoPayment2) {
             Payment::create([
                'order_id' => $orderWithNoPayment2->id,
                'transaction_id' => 'TRX-' . uniqid(),
                'amount' => $orderWithNoPayment2->total_amount,
                'currency' => 'IDR',
                'payment_method' => 'e_wallet',
                'proof_url' => null,
                'status' => 'verified',
                'payment_status_gateway' => 'settlement',
                'payment_gateway_response' => json_encode(['status_code' => '200', 'message' => 'success']),
                'verified_at' => now(),
                'verified_by' => $adminUser->id,
            ]);
        }
    }
}
