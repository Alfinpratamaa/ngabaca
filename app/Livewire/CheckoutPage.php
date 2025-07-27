<?php

namespace App\Livewire;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class CheckoutPage extends Component
{
    public $fullName;
    public $email;
    public $phoneNumber;

    public $city = 'Bangalore';
    public $state = 'Karnataka';
    public $zipCode = '560021';
    public $address = '123 Main St Apartment 4B';
    public $notes;

    public $shippingMethod = 'free'; // Nilai default: 'free' atau 'express'
    public $subtotal = 2199.00;
    public $taxes = 5.00;
    public $shippingCost = 0.00;
    public $total;

    public $cartItems = [];
    public $totalPrice = 0;
    public $shipping_address = '';
    public $phone = '';


    public function mount()
    {
        // PERBAIKAN KUNCI: Ambil data dari session flash 'checkout_cart' yang dikirim dari CartPage
        $this->cartItems = session()->get('checkout_cart', []);

        // Jika session checkout kosong (misal, user refresh halaman), kembalikan ke keranjang
        if (empty($this->cartItems)) {
            session()->flash('error', 'Tidak ada item untuk di-checkout. Silakan pilih dari keranjang Anda.');
            return redirect()->route('cart');
        }

        if (Auth::check()) {
            $user = Auth::user();
            $this->fullName = $user->name;         // Asumsi model User memiliki atribut 'name'
            $this->email = $user->email;          // Asumsi model User memiliki atribut 'email'
            $this->phoneNumber = $user->phone_number; // Asumsi model User memiliki atribut 'phone_number'
        }

        $this->totalPrice = collect($this->cartItems)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });
    }

    public function placeOrder()
    {
        $this->validate();

        $this->shipping_address = trim("{$this->city}, {$this->state}, {$this->zipCode} , {$this->address}");
        $this->shipping_address = str_replace("\n", ', ', $this->shipping_address);
        $this->shipping_address = trim($this->shipping_address, ', ');
        $this->phone = trim($this->phoneNumber);

        $order = null;
        $snapToken = null;

        try {
            DB::transaction(function () use (&$order, &$snapToken) {
                $validItems = collect($this->cartItems)->filter(function ($item) {
                    return isset($item['id'], $item['title'], $item['price'], $item['quantity']);
                });

                if ($validItems->isEmpty()) {
                    throw new \Exception("Tidak ada item yang valid di keranjang.");
                }

                $this->totalPrice = $validItems->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                });

                // PERBAIKAN: Gunakan data dinamis dari properti, bukan hardcode
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $this->totalPrice,
                    'status' => 'diproses',
                    'shipping_address' => $this->shipping_address,
                ]);

                // PERBAIKAN: Hapus item yang sudah di-checkout dari keranjang utama
                $mainCart = session()->get('cart', []);
                foreach ($validItems as $item) {
                    $order->orderItems()->create([
                        'book_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                    // Hapus dari keranjang utama
                    unset($mainCart[$item['id']]);
                }
                // Simpan kembali keranjang utama yang sudah diperbarui
                session(['cart' => $mainCart]);

                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production');
                Config::$isSanitized = config('services.midtrans.is_sanitized');
                Config::$is3ds = config('services.midtrans.is_3ds');

                $item_details = $validItems->map(function ($item) {
                    return [
                        'id'       => (string) $item['id'],
                        'price'    => (int) $item['price'],
                        'quantity' => (int) $item['quantity'],
                        'name'     => (string) $item['title'],
                    ];
                })->values()->toArray();

                $gross_amount = collect($item_details)->sum(function ($item) {
                    return $item['price'] * $item['quantity'];
                });

                $midtrans_params = [
                    'transaction_details' => [
                        'order_id' => 'ORDER-' . $order->id . '-' . time(),
                        'gross_amount' => $gross_amount,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                        'phone' => '+62' . $this->phone,
                    ],
                    'item_details' => $item_details,
                ];

                $snapToken = Snap::getSnapToken($midtrans_params);

                $order->payment()->create([
                    'transaction_id' => $midtrans_params['transaction_details']['order_id'],
                    'total_price' => $order->total_price,
                    'status' => 'pending',
                ]);

                // Jangan gunakan forget('cart') agar item yang tidak dicheckout tetap ada
                // session()->forget('cart');
            });
        } catch (QueryException $e) {
            Log::error('Database Error during checkout', [
                'error_code' => $e->getCode(),
                'error_message' => $e->getMessage(),
            ]);
            session()->flash('error', 'Terjadi kesalahan database. Silakan coba lagi.');
            return;
        } catch (\Exception $e) {
            Log::error('General Error during checkout: ' . $e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return;
        }

        if ($snapToken) {
            session()->forget('checkout_cart');
            $this->dispatch('snap-redirect', token: $snapToken);
        } else {
            session()->flash('error', 'Gagal memproses pembayaran. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}
