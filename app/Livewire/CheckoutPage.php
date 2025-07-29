<?php

namespace App\Livewire;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravolt\Indonesia\Models\City;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Database\QueryException;
use GuzzleHttp\Exception\RequestException;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;

class CheckoutPage extends Component
{
    public $fullName;
    public $email;
    public $phoneNumber;

    public $province_id = '';
    public $city_id = '';
    public $district_id = '';
    public $village_id = '';
    public $postalCode = '';
    public $address = '';
    public $notes;

    // List untuk dropdown
    public $provinces = [];
    public $cities = [];
    public $districts = [];
    public $villages = [];

    public $shippingMethod = 'free';
    public $subtotal = 0;
    public $shippingCost = 240000;
    public $total;
    public $taxes = 0;
    public $paymentMethod = 'midtrans';

    public $cartItems = [];
    public $totalPrice = 0;
    public $shipping_address = '';

    protected $rules = [
        'address'       => 'required|string|max:255',
        'province_id'   => 'required',
        'city_id'       => 'required',
        'postalCode'    => 'required|numeric',
        'district_id'   => 'required',
        'village_id'    => 'required',
    ];

    public function mount()
    {
        $this->cartItems = session()->get('checkout_cart', []);

        if (empty($this->cartItems)) {
            session()->flash('error', 'Tidak ada item untuk di-checkout. Silakan pilih dari keranjang Anda.');
            return redirect()->route('cart');
        }

        if (Auth::check()) {
            $user = Auth::user();
            $this->fullName = $user->name;
            $this->email = $user->email;
            $this->phoneNumber = $user->phone_number;
        }
        $this->cartItems = collect($this->cartItems)->map(function ($item) {
            $item['subTotal'] = (int) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1);
            return $item;
        })->toArray();

        // TotalPrice = jumlah semua subTotal
        $this->totalPrice = collect($this->cartItems)->sum('subTotal');

        $this->provinces = Province::orderBy('name')->get();
        $this->shippingCost = 24000; // Biaya pengiriman tetap
        $this->taxes = $this->totalPrice * 0.1; // Pajak tetap 0 untuk contoh ini
        $this->total = $this->totalPrice + $this->shippingCost + $this->taxes;
    }

    public function updatedProvinceId($value)
    {
        $this->cities = City::where('province_code', $value)->orderBy('name')->get();
        $this->city_id = $this->district_id = $this->village_id = '';
        $this->districts = $this->villages = [];
    }

    public function updatedCityId($value)
    {
        $this->districts = District::where('city_code', $value)->orderBy('name')->get();
        $this->district_id = $this->village_id = '';
        $this->villages = [];
    }

    public function updatedDistrictId($value)
    {
        $this->villages = Village::where('district_code', $value)->orderBy('name')->get();
        $this->village_id = '';
    }

    public function placeOrder()
    {
        // Reset error messages
        session()->forget(['error', 'success']);

        $this->validate();

        // PERBAIKAN: Gunakan koleksi untuk membangun alamat dengan aman, menghindari koma ganda.
        $addressParts = [
            $this->address,
            optional(Village::where('code', $this->village_id)->first())->name,
            optional(District::where('code', $this->district_id)->first())->name,
            optional(City::where('code', $this->city_id)->first())->name,
            optional(Province::where('code', $this->province_id)->first())->name,
            $this->postalCode
        ];

        // Filter bagian yang kosong dan gabungkan dengan koma.
        $this->shipping_address = collect($addressParts)->filter()->implode(', ');



        $order = null;
        $snapToken = null;

        Log::info('Processing checkout with cart items', [
            'cart_items' => $this->cartItems,
            'shipping_address' => $this->shipping_address,
            'phone' => $this->phoneNumber,
        ]);

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

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $this->totalPrice,
                    'status' => 'diproses',
                    'shipping_address' => $this->shipping_address,
                ]);

                $mainCart = session()->get('cart', []);
                foreach ($validItems as $item) {
                    $order->orderItems()->create([
                        'book_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                    unset($mainCart[$item['id']]);
                }
                session(['cart' => $mainCart]);

                // Konfigurasi Midtrans
                Config::$serverKey = config('services.midtrans.server_key');
                Config::$isProduction = config('services.midtrans.is_production', false);
                Config::$isSanitized = config('services.midtrans.is_sanitized', true);
                Config::$is3ds = config('services.midtrans.is_3ds', true);

                // 1. Siapkan detail item produk dari keranjang
                $item_details = $validItems->map(function ($item) {
                    return [
                        'id'       => (string) $item['id'],
                        'price'    => (int) round($item['price']),
                        'quantity' => (int) $item['quantity'],
                        'name'     => trim(substr((string) $item['title'], 0, 50)),
                    ];
                })->values()->toArray();

                // 2. Hitung total harga buku, pajak, dan ongkir
                $subtotalItems = $validItems->sum(fn($i) => $i['price'] * $i['quantity']);
                $totalTaxes = $subtotalItems * 0.1;
                $totalShipping = 24000; // Pastikan nilainya benar

                // 3. PERBAIKAN: Tambahkan Pajak sebagai item terpisah jika ada
                if ($totalTaxes > 0) {
                    $item_details[] = [
                        'id'       => 'TAX',
                        'price'    => (int) round($totalTaxes),
                        'quantity' => 1,
                        'name'     => 'Pajak (10%)',
                    ];
                }

                // 4. PERBAIKAN: Tambahkan Ongkir sebagai item terpisah jika ada
                if ($totalShipping > 0) {
                    $item_details[] = [
                        'id'       => 'SHIPPING',
                        'price'    => (int) round($totalShipping),
                        'quantity' => 1,
                        'name'     => 'Biaya Pengiriman',
                    ];
                }

                // 5. Hitung gross_amount dari semua komponen
                $gross_amount = (int) round($subtotalItems + $totalTaxes + $totalShipping);


                $transaction_id = 'TRX-' . $order->id . '-' . time();

                // PERBAIKAN: Sanitasi data pelanggan
                $customer_details = [
                    'first_name' => trim(substr(Auth::user()->name, 0, 50)), // Menghapus spasi ekstra
                    'email'      => Auth::user()->email,
                    'phone'      => preg_replace('/[^0-9]/', '', $this->phoneNumber), // Hanya kirim angka
                    'shipping_address' => $this->shipping_address
                ];

                $midtrans_params = [
                    'transaction_details' => [
                        'order_id' => $transaction_id,
                        'gross_amount' => $gross_amount,
                    ],
                    'customer_details' => $customer_details,
                    'item_details' => $item_details,
                    'callbacks' => [
                        // PERBAIKAN: Gunakan route() dan pastikan URL ini dapat diakses publik.
                        // Untuk testing lokal, gunakan Ngrok atau layanan sejenisnya.
                        'finish' => route('payment.finish'),
                    ],
                    'expiry' => [
                        'start_time' => date('Y-m-d H:i:s O'),
                        'unit' => 'minutes',
                        'duration' => 60 // Durasi 60 menit
                    ]
                ];

                Log::info('Attempting to get Snap token', ['midtrans_params' => $midtrans_params]);

                // Coba dapatkan Snap Token
                try {
                    $snapResponse = Snap::createTransaction($midtrans_params);
                    $snapToken = $snapResponse->token ?? null;
                    $pdfUrl = $snapResponse->redirect_url ?? null;
                } catch (RequestException $e) {
                    $responseBody = 'No response body';
                    if (method_exists($e, 'getResponse')) {
                        $response = $e->getResponse();
                        if ($response && method_exists($response, 'getBody')) {
                            $responseBody = (string) $response->getBody();
                        }
                    }
                    Log::error('Error getting Snap token from Midtrans API: ' . $e->getMessage(), [
                        'midtrans_params' => $midtrans_params,
                        'response_body' => $responseBody
                    ]);
                    throw new \Exception("Gagal berkomunikasi dengan Midtrans: " . $e->getMessage());
                }

                if (!$snapToken) {
                    throw new \Exception("Token pembayaran kosong setelah request berhasil.");
                }

                $order->payment()->create([
                    'transaction_id' => $transaction_id,
                    'total_price' => $order->total_price,
                    'status' => 'pending',
                    'proof_url' => $pdfUrl, // ✅ Simpan pdf_url jika ada
                ]);

                Log::info('Checkout successful', ['order_id' => $order->id, 'snap_token' => $snapToken]);
            });

            if ($snapToken && $order) {
                session()->forget('checkout_cart');
                session()->flash('success', 'Checkout berhasil! Membuka halaman pembayaran...');

                // PERBAIKAN: Kirim juga order->id ke frontend
                $this->dispatch('orderPlaced', [
                    'snapToken' => $snapToken,
                    'orderId'   => $order->id
                ]);
            } else {
                throw new \Exception("Token pembayaran atau pesanan tidak valid.");
            }
        } catch (\Exception $e) {
            Log::error('General Error during checkout: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return;
        }
    }

    #[On('paymentCanceled')]
    public function paymentCanceled($order_id) // <-- UBAH DI SINI
    {
        if ($order_id) {
            $order = Order::find($order_id);

            // Periksa apakah order ada dan statusnya masih menunggu pembayaran
            if ($order && $order->status === 'diproses') {
                // Ubah status order menjadi 'dibatalkan'
                $order->status = Order::STATUS_BATAL;
                $order->save();

                // Beri notifikasi ke pengguna
                session()->flash('error', 'Pembayaran Anda dibatalkan. Jangan khawatir, Anda bisa mencoba lagi kapan saja.');

                // NOTE: Anda bisa menambahkan logika untuk mengembalikan item ke keranjang jika diperlukan

                // mengembalikan item ke keranjang
                $cart = session()->get('cart', []);
                foreach ($order->orderItems as $item) {
                    if (isset($cart[$item->book_id])) {
                        $cart[$item->book_id]['quantity'] += $item->quantity;
                    } else {
                        $cart[$item->book_id] = [
                            'id' => $item->book_id,
                            'title' => $item->book->title,
                            'price' => $item->price,
                            'quantity' => $item->quantity,
                            'cover_image_url' => $item->book->cover_image_url,
                        ];
                    }
                }
                session()->put('cart', $cart);
            }
        }

        // Refresh komponen untuk menampilkan pesan flash
        return $this->redirect(route('checkout'));
    }

    public function render()
    {
        return view('livewire.checkout-page');
    }
}
