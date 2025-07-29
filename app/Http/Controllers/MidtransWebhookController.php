<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Transaction;

class MidtransWebhookController extends Controller
{
    /**
     * Handle Midtrans notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        // 1. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        // 2. Buat instance notifikasi & lakukan verifikasi signature
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            Log::error('Gagal membuat instance Midtrans Notification: ' . $e);
            return response()->json(['message' => 'Error processing notification.'], 500);
        }

        // Log semua notifikasi yang masuk untuk debugging
        Log::info('Midtrans notification received:', (array) $notification->getResponse());

        // 3. Ambil data dari notifikasi
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $orderId = $notification->order_id;
        $transactionId = $notification->transaction_id;
        $fraudStatus = $notification->fraud_status;

        $statusProof = (object) Transaction::status($orderId);

        Log::info("MEMEK KONTOL ANJING BANGSAT \n Status Proof: " . json_encode($statusProof));

        Log::info("TRX ID : {$transactionId}, \n ORDER ID : {$orderId}, \n STATUS : {$transactionStatus}, \n PAYMENT TYPE : {$paymentType}, \n FRAUD STATUS : {$fraudStatus}");

        // Cari payment berdasarkan transaction_id dari Midtrans
        $payment = Payment::where('transaction_id', $orderId)->first();

        if (!$payment) {
            Log::warning("Webhook Ignored: Payment dengan transaction_id {$orderId} tidak ditemukan.");
            return response()->json(['message' => 'Payment not found.'], 404);
        }

        // Jangan proses status yang sama berulang kali
        if ($payment->status === 'success' || $payment->status === 'failed') {
            Log::info("Webhook Ignored: Payment {$orderId} sudah dalam status final.");
            return response()->json(['message' => 'Webhook has been processed.']);
        }




        if (isset($statusProof->pdf_url)) {
            $payment->proof_url = $statusProof->pdf_url;
        }
        // 4. Update status berdasarkan notifikasi
        DB::transaction(function () use ($transactionStatus, $payment, $notification, $transactionId) {

            $statusProof = (object) \Midtrans\Transaction::status($transactionId);

            if (isset($statusProof->pdf_url)) {
                $payment->proof_url = $statusProof->pdf_url;
                Log::info("Proof URL ditemukan: {$statusProof->pdf_url}");
            }
            // Jika pembayaran GAGAL atau DIBATALKAN oleh Midtrans, update status payment.
            // Ini adalah status final yang tidak perlu verifikasi admin.
            if ($transactionStatus == 'cancel') {
                $payment->status = 'cancelled';
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $payment->status = 'failed';
            }


            // Untuk status lain (termasuk 'settlement' yang berhasil), 
            // status payment di database TIDAK diubah (tetap 'pending').
            // Admin yang akan mengubahnya nanti menjadi 'verified'.

            // Webhook HANYA bertugas mencatat detail dari Midtrans ke tabel payments.
            $payment->payment_method = $this->formatPaymentMethod($notification);
            $payment->payment_status_gateway = $transactionStatus;
            $payment->payment_gateway_response = (array) $notification->getResponse();



            // Simpan perubahan HANYA pada tabel payments
            $payment->save();
            Log::info("Successfully updated payment status to {$payment->status} for transaction_id {$payment->transaction_id}");

            // Tidak ada perubahan apapun pada tabel orders.
        });

        return response()->json(['message' => 'Notification processed successfully.']);
    }

    /**
     * Format nama metode pembayaran agar lebih mudah dibaca.
     *
     * @param object $notification
     * @return string
     */
    private function formatPaymentMethod($notification)
    {
        $type = $notification->payment_type;

        if ($type == 'bank_transfer') {
            if (isset($notification->va_numbers[0]->bank)) {
                return 'VA ' . strtoupper($notification->va_numbers[0]->bank);
            }
            return 'Bank Transfer';
        }

        if ($type == 'qris') {
            return 'QRIS';
        }

        if ($type == 'cstore') {
            return strtoupper($notification->store); // e.g., INDOMARET, ALFAMART
        }

        // Tambahkan kondisi lain jika perlu (gopay, shopeepay, etc.)
        return ucfirst(str_replace('_', ' ', $type));
    }
}
