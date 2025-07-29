<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function notificationHandler(Request $request)
    {
        // Set konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        try {
            // Buat instance dari notifikasi Midtrans
            $notification = new Notification();

            // Ambil order_id dan status transaksi dari notifikasi
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;

            // Cari payment berdasarkan transaction_id (yaitu order_id dari Midtrans)
            $payment = Payment::where('transaction_id', $orderId)->first();

            if (!$payment) {
                // Jika pembayaran tidak ditemukan, kirim response error
                return response()->json(['message' => 'Payment not found.'], 404);
            }

            // Validasi signature key untuk keamanan (SANGAT PENTING)
            $signatureKey = hash('sha512', $notification->order_id . $notification->status_code . $notification->gross_amount . config('midtrans.server_key'));
            if ($notification->signature_key != $signatureKey) {
                return response()->json(['message' => 'Invalid signature.'], 403);
            }

            DB::transaction(function () use ($transactionStatus, $fraudStatus, $payment, $notification) {
                // Update status payment berdasarkan notifikasi
                $payment->payment_status_gateway = $transactionStatus;
                $payment->payment_gateway_response = json_encode($notification->getResponse());

                if ($transactionStatus == 'settlement') {
                    // Pembayaran berhasil dan dana sudah masuk
                    if ($fraudStatus == 'accept') {
                        $payment->status = 'verified';
                        $payment->verified_at = now();
                        $payment->order->status = Order::STATUS_SELESAI; // Update status order
                    }
                } elseif ($transactionStatus == 'capture' && $payment->payment_method == 'credit_card') {
                    if ($fraudStatus == 'accept') {
                        $payment->status = 'verified';
                        $payment->verified_at = now();
                        $payment->order->status = Order::STATUS_SELESAI;
                    }
                } elseif ($transactionStatus == 'pending') {
                    // Pembayaran masih menunggu (misal: transfer bank)
                    $payment->status = 'pending';
                } elseif ($transactionStatus == 'expire') {
                    // Pembayaran kadaluarsa
                    $payment->status = 'failed';
                    $payment->order->status = Order::STATUS_BATAL;
                } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {
                    // Pembayaran dibatalkan atau ditolak
                    $payment->status = 'cancelled';
                    $payment->order->status = Order::STATUS_BATAL;
                }

                $payment->order->save();
                $payment->save();
            });

            // Beri response 200 OK ke Midtrans
            return response()->json(['message' => 'Notification processed successfully.']);
        } catch (\Exception $e) {
            // Tangani error
            return response()->json(['message' => 'Error processing notification: ' . $e->getMessage()], 500);
        }
    }
}
