<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Midtrans\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function finish(Request $request)
    {
        $orderIdMidtrans = $request->query('order_id');

        if (!$orderIdMidtrans) {
            return view('payment.finish', [
                'status' => 'error',
                'message' => 'Order ID tidak ditemukan.'
            ]);
        }

        // Ambil ID order lokal dari format TRX-{id}-{timestamp}
        $parts = explode('-', $orderIdMidtrans);
        $orderId = $parts[1] ?? null;

        $order = Order::find($orderId);
        if (!$order) {
            return view('payment.finish', [
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.'
            ]);
        }

        // ✅ Cek status transaksi langsung ke Midtrans
        try {
            $status = Transaction::status($orderIdMidtrans);
            $transactionStatus = $status->transaction_status ?? 'unknown';
        } catch (\Exception $e) {
            $transactionStatus = 'unknown';
        }

        // ✅ Jika expired, update payment jadi failed
        if ($transactionStatus === 'expire') {
            if ($order->payment) {
                $order->payment->status = 'failed';
                $order->payment->save();
            }
            return view('payment.finish', [
                'order' => $order,
                'status' => 'expired',
                'message' => 'Transaksi kamu sudah expired.'
            ]);
        }

        return view('payment.finish', [
            'order' => $order,
            'status' => $transactionStatus
        ]);
    }
}
