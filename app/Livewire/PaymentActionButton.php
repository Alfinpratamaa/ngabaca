<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class PaymentActionButton extends Component
{
    public $orderId;
    public $order;

    public function mount($orderId)
    {
        $this->checkExpired($orderId);
        $this->orderId = $orderId;

        $this->order = Order::with('payment', 'user')->findOrFail($orderId);
    }

    public function continuePayment()
    {
        if (empty($this->order->payment->expires_at) || now()->greaterThan($this->order->payment->expires_at)) {
            $this->order->update(['status' => 'batal']);
            $this->order->payment?->update(['status' => 'cancelled']);
            session()->flash('error', 'Waktu pembayaran sudah habis (24 jam). Pesanan dibatalkan.');
            return;
        }

        $payment = $this->order->payment;

        if ($payment && $payment->status === 'pending') {
            if ($payment->proof_url) {
                return redirect()->away($payment->proof_url);
            }

            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.is_production', false);

            $snapResponse = Snap::createTransaction([
                'transaction_details' => [
                    'order_id' => $payment->transaction_id,
                    'gross_amount' => (int) $this->order->total_price,
                ],
                'customer_details' => [
                    'first_name' => $this->order->user->name,
                    'email' => $this->order->user->email,
                    'phone' => $this->order->user->phone_number,
                ],
            ]);

            $payment->update(['proof_url' => $snapResponse->redirect_url ?? null]);

            return redirect()->away($snapResponse->redirect_url);
        }

        session()->flash('error', 'Status pembayaran tidak bisa dilanjutkan.');
    }

    public function cancelOrder()
    {
        if ($this->order->status === 'selesai' || $this->order->status === 'dikirim') {
            session()->flash('error', 'Pesanan sudah diproses, tidak bisa dibatalkan.');
            return;
        }

        $this->order->update(['status' => 'batal']);
        $this->order->payment?->update(['status' => 'cancelled']);
        session()->flash('success', 'Pesanan berhasil dibatalkan.');

        // Refresh data order
        $this->order = $this->order->fresh();
    }

    public function checkExpired($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            session()->flash('error', 'Order not found.');
            return;
        }

        if ($order->payment->sexpires_at && now()->greaterThan($order->payment->sexpires_at)) {
            $order->update(['status' => 'batal']);
            $order->payment?->update(['status' => 'cancelled']);
            session()->flash('error', 'Waktu pembayaran sudah habis (24 jam). Pesanan dibatalkan.');
        }
    }


    public function render()
    {
        return view('livewire.payment-action-button');
    }
}
