<div class="grid grid-cols-1 md:grid-cols-12 gap-10">
    <div class="md:col-span-7 bg-white w-full p-6 rounded-lg shadow-md">
        <div class="text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-secondary">Home</a>
            <span class="mx-2">&gt;</span>
            <a href="{{ route('cart') }}" class="hover:text-secondary">Cart</a>
            <span class="mx-2">&gt;</span>
            <a href="{{ route('checkout') }}" class="text-secondary">Checkout</a>
        </div>

        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Shipping Address</h2>
        <div class="space-y-6">
            {{-- Full Name (bukan input) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
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
                    <label class="block text-sm font-medium text-gray-700">Phone number</label>
                    <div class="mt-1 p-3 w-full bg-gray-100 border border-gray-200 rounded-md text-gray-600">
                        {{ $phoneNumber }}
                    </div>
                </div>
            </div>

            {{-- City, State, Zip Code (input) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative">
                    <input type="text" wire:model.live="city" id="city"
                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer"
                        placeholder=" " />
                    <label for="city"
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">City</label>
                </div>
                <div class="relative">
                    <input type="text" wire:model.live="state" id="state"
                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer"
                        placeholder=" " />
                    <label for="state"
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">State</label>
                </div>
                <div class="relative">
                    <input type="text" wire:model.live="zipCode" id="zipCode"
                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer"
                        placeholder=" " />
                    <label for="zipCode"
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Zip
                        Code</label>
                </div>
            </div>

            {{-- Address (input) --}}
            <div class="relative mt-6">
                <input type="text" wire:model.live="address" id="address"
                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-black peer"
                    placeholder=" " />
                <label for="address"
                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-black peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Address</label>
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

            {{-- Metode Pengiriman --}}
            {{-- <h3 class="text-lg font-medium text-gray-800 pt-6">Shipping Method</h3>
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
                                <img src="{{ $item['cover_image_url'] }}" alt="{{ $item['title'] }}"
                                    class="w-16 h-16 object-cover rounded-md">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $item['title'] }}</h3>
                                    <p class="text-sm text-gray-600">Jumlah : {{ $item['quantity'] }}</p>
                                    <p class="text-sm text-gray-600">Harga : Rp.
                                        {{ number_format($item['price'], 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-lg font-semibold text-gray-800">
                                Rp. {{ number_format($item['price'], 0, ',', '.') }}
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
                    <span>Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
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
                <span>${{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <button wire:click="processCheckout"
                class="w-full bg-primary text-secondary font-bold py-3 px-4 rounded-md mt-6 hover:bg-primary/75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                Lanjutkan Ke Pembayaran
            </button>

        </div>
    </div>
</div>
