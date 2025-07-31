<div>
    <form wire:submit="placeOrder" class="grid grid-cols-1 md:grid-cols-12 gap-10">
        <div class="md:col-span-7 bg-white w-full p-6 rounded-lg shadow-md">
            <div class="text-sm text-gray-500 mb-6">
                <a wire:navigate href="{{ route('home') }}" class="hover:text-secondary">Home</a>
                <span class="mx-2">&gt;</span>
                <a wire:navigate href="{{ route('cart') }}" class="hover:text-secondary">Cart</a>
                <span class="mx-2">&gt;</span>
                <a wire:navigate href="{{ route('checkout') }}" class="text-secondary">Checkout</a>
            </div>

            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Shipping Address</h2>
            <div class="space-y-6">
                {{-- Full Name (bukan input) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <div class="mt-1 p-3 w-full bg-gray-100 border border-gray-200 rounded-md text-gray-600">
                        {{ $fullName }}
                    </div>
                </div>

                {{-- Email & Phone (bukan input) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 p-3 w-full bg-gray-100 border border-gray-200 rounded-md text-gray-600">
                            {{ $email }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <div class="mt-1 p-3 w-full bg-gray-100 border border-gray-200 rounded-md text-gray-600">
                            {{ $phoneNumber }}
                        </div>
                    </div>
                </div>

                {{-- Address (input) --}}
                <div class="relative mt-6">
                    <input type="text" wire:model.live="address" id="address" required
                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer"
                        placeholder=" " />
                    <label for="address"
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Alamat</label>
                </div>
                {{-- City, State, Zip Code (input) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="relative">
                        <select wire:model.live="province_id" id="province" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer">
                            <option value="" selected>Pilih Provinsi</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->code }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        <label for="province"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Provinsi</label>
                    </div>
                    <div class="relative">
                        <select wire:model.live="city_id" id="city" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer">
                            <option value="" selected>Pilih Kota</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->code }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        <label for="city"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Kota</label>

                    </div>
                    <div class="relative">
                        <input type="text" required wire:model.live="postalCode" id="postalCode"
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer"
                            placeholder=" " />
                        <label for="postalCode"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Kode
                            Pos</label>
                    </div>
                </div>
                {{-- Desa/Kelurahan & Kecamatan (input) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <select wire:model.live="district_id" id="district" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer">
                            <option value="" selected>Pilih Kemacatan</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->code }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                        <label for="disctrict"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Kecamatan</label>
                    </div>
                    <div class="relative">
                        <select wire:model.live="village_id" id="village" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer">
                            <option value="" selected>Pilih Desa/Kelurahan</option>
                            @foreach ($villages as $village)
                                <option value="{{ $village->code }}">{{ $village->name }}</option>
                            @endforeach
                        </select>
                        <label for="village"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Desa/Kelurahan</label>
                    </div>
                </div>



                {{-- Catatan (input) --}}
                <div class="relative">
                    <textarea wire:model.live="notes" id="notes" rows="3"
                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer"
                        placeholder=" "></textarea>
                    <label for="notes"
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Catatan
                        (opsional)</label>
                </div>
                {{-- Masih dalam pertimbangan --}}
                {{-- Metode Pengiriman --}}
                {{-- <h3 class="text-lg font-medium text-gray-800 pt-6">Jasa Ongkir</h3>
                <div class="mt-4 space-y-4">
                    <label
                        class="relative flex items-center border rounded-lg p-4 cursor-pointer @if ($shippingMethod == 'free') border-black bg-gray-100 @else border-gray-300 @endif">
                        <input type="radio" wire:model.live="shippingMethod" value="free"
                            class="h-4 w-4 text-black border-gray-300 focus:ring-black">
                        <div class="ml-3 flex flex-col flex-grow">
                            <span class="font-medium text-gray-900">Free Shipping</span>
                            <span class="text-sm text-gray-500">7-20 Days</span>
                        </div>
                        <span class="font-semibold text-gray-900">$0</span>
                    </label>

                    <label
                        class="relative flex items-center border rounded-lg p-4 cursor-pointer @if ($shippingMethod == 'express') border-black bg-gray-100 @else border-gray-300 @endif">
                        <input type="radio" wire:model.live="shippingMethod" value="express"
                            class="h-4 w-4 text-black border-gray-300 focus:ring-black">
                        <div class="ml-3 flex flex-col flex-grow">
                            <span class="font-medium text-gray-900">Express Shipping</span>
                            <span class="text-sm text-gray-500">1-3 Days</span>
                        </div>
                        <span class="font-semibold text-gray-900">$9</span>
                    </label>
                </div> --}}
            </div>
        </div>

        <div class="md:col-span-5 mt-8 md:mt-0">
            <div class="bg-white p-6 rounded-lg shadow-sm sticky top-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Your Cart</h2>

                @if (empty($cartItems))
                    <p class="text-gray-500">Your cart is empty.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($cartItems as $item)
                            <div class="flex items-center justify-between border-b pb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-20 h-28 bg-muted rounded-lg overflow-hidden shadow-lg">
                                        @if ($item['cover_image_url'])
                                            <img src="{{ $item['cover_image_url'] }}" alt="{{ $item['title'] }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white">
                                                No Cover</div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $item['title'] }}</h3>
                                        <p class="text-sm text-gray-600">Jumlah : {{ $item['quantity'] }}</p>
                                        <p class="text-sm text-gray-600">Harga : Rp.
                                            {{ number_format($item['price'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-lg font-semibold text-gray-800">
                                    Rp. {{ number_format($item['subTotal'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="border-t border-gray-200 my-6"></div>

                {{-- Kode Diskon --}}
                <div class="flex space-x-2">
                    <input type="text" placeholder="Kode Diskon"
                        class="flex-grow block w-full border-gray-300 rounded-md p-2 shadow-sm border focus:ring-black focus:border-black sm:text-sm">
                    <button
                        class="px-4 py-2 bg-muted cursor-pointer text-gray-800 font-semibold rounded-md hover:bg-gray-300">Terapkan</button>
                </div>

                <div class="border-t border-gray-200 my-6"></div>

                {{-- Rincian Biaya --}}
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span>Total Harga buku</span>
                        <span>Rp. {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Ongkir</span>
                        <span class="font-semibold">Rp. {{ number_format($shippingCost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Estimasi Pajak</span>
                        <span>Rp. {{ number_format($taxes, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="border-t border-gray-200 my-6"></div>

                {{-- Total --}}
                <div class="flex justify-between font-bold text-lg text-gray-800">
                    <span>Total Biaya </span>
                    <span>Rp. {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="placeOrder"
                    class="w-full bg-primary text-white font-bold py-3 px-4 rounded-md hover:bg-primary disabled:bg-muted transition-colors">
                    <span wire:loading.remove wire:target="placeOrder">Bayar Sekarang</span>
                    <span wire:loading wire:target="placeOrder">Memproses...</span>
                </button>

            </div>
        </div>
    </form>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        document.addEventListener('livewire:navigated', () => {
            // Hapus listener lama jika ada untuk mencegah duplikasi
            if (window.livewireListener) {
                Livewire.off('orderPlaced', window.livewireListener);
            }

            // Definisikan listener baru
            window.livewireListener = function(payload) {
                console.log('Event orderPlaced diterima:', payload);

                if (payload[0] && payload[0].snapToken && payload[0].orderId) {
                    const snapToken = payload[0].snapToken;
                    const orderId = payload[0].orderId;

                    snap.pay(snapToken, {
                        onSuccess: function(result) {
                            console.log('Pembayaran Sukses:', result);
                            window.location.href =
                                '/orders'
                        },
                        onPending: function(result) {
                            console.log('Pembayaran Tertunda:', result);
                            window.location.href =
                                '/orders'
                        },
                        onError: function(result) {
                            console.error('Pembayaran Gagal:', result);
                            window.location.href =
                                '/orders'
                        },
                        // PERBAIKAN: Tambahkan callback onClose
                        onClose: function() {
                            console.log('Pop-up pembayaran ditutup oleh pengguna.');
                            // Kirim event kembali ke backend untuk membatalkan order
                            Livewire.dispatch('paymentCanceled', {
                                order_id: orderId
                            });
                        }
                    });
                } else {
                    console.error('Snap Token atau Order ID tidak ditemukan dalam payload.');
                    alert('Gagal memproses pembayaran. Token tidak valid.');
                }
            };

            // Pasang listener
            Livewire.on('orderPlaced', window.livewireListener);
        });
    </script>
</div>
