<!-- filepath: /home/alfin/Desktop/ngabaca/resources/views/livewire/cart-page.blade.php -->
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-secondary mb-8">Keranjang Belanja Anda</h1>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Message -->
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Cart Content -->
    @if (empty($cartItems))
        <!-- Empty Cart -->
        <div class="text-center py-12 bg-white rounded-xl shadow-md">
            <div class="mb-4">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5a1 1 0 001 1h9.2a1 1 0 001-1L15 13M7 13v4a1 1 0 001 1h8a1 1 0 001-1v-4m-9 0V9a1 1 0 011-1h8a1 1 0 011 1v4" />
                </svg>
            </div>
            <p class="text-gray-600 text-lg mb-4">Keranjang Anda kosong.</p>
            <a href="{{ route('catalog') }}" wire:navigate
                class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Mulai Belanja
            </a>
        </div>
    @else
        <!-- Cart Items -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items List -->
            <div class="lg:w-2/3 bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-secondary mb-6">Item di Keranjang</h2>
                <div class="space-y-6">
                    @foreach ($cartItems as $id => $item)
                        <div class="flex items-center border-b pb-4 last:border-b-0 last:pb-0">
                            <!-- Book Image -->
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                class="w-24 h-24 object-cover rounded-lg mr-4 flex-shrink-0"
                                onerror="this.onerror=null;this.src='https://placehold.co/400x600/e2c9a0/6B3F13?text=No+Image';">
                            
                            <!-- Book Details -->
                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-secondary">{{ $item['name'] }}</h3>
                                <p class="text-gray-600">Harga: {{ $this->formatPrice($item['price']) }}</p>
                                
                                <!-- Quantity Controls -->
                                <div class="flex items-center mt-2">
                                    <button wire:click="updateQuantity({{ $id }}, 'decrease')"
                                        class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                                        -
                                    </button>
                                    <span class="mx-3 text-lg font-semibold">{{ $item['quantity'] }}</span>
                                    <button wire:click="updateQuantity({{ $id }}, 'increase')"
                                        class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md hover:bg-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-400">
                                        +
                                    </button>
                                </div>
                                
                                <!-- Item Total -->
                                <p class="text-sm text-gray-500 mt-1">
                                    Subtotal: {{ $this->formatPrice($item['price'] * $item['quantity']) }}
                                </p>
                            </div>
                            
                            <!-- Remove Button -->
                            <button wire:click="removeItem({{ $id }})"
                                class="text-red-500 hover:text-red-700 ml-4 focus:outline-none focus:ring-2 focus:ring-red-400 p-2 rounded-full hover:bg-red-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:w-1/3 bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-secondary mb-6">Ringkasan Pesanan</h2>
                
                <!-- Price Breakdown -->
                <div class="space-y-3 mb-6 text-lg">
                    <div class="flex justify-between">
                        <span class="text-gray-700">Subtotal ({{ count($cartItems) }} item)</span>
                        <span class="font-semibold text-secondary">{{ $this->formatPrice($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-700">Biaya Pengiriman</span>
                        <span class="font-semibold text-secondary">{{ $this->formatPrice($shippingCost) }}</span>
                    </div>
                    <div class="border-t pt-3 border-gray-200"></div>
                    <div class="flex justify-between text-xl font-bold">
                        <span class="text-secondary">Total</span>
                        <span class="text-primary">{{ $this->formatPrice($total) }}</span>
                    </div>
                </div>
                
                <!-- Checkout Button -->
                <button wire:click="proceedToCheckout"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 disabled:opacity-50 disabled:cursor-not-allowed">
                    Lanjutkan ke Checkout
                </button>
                
                <!-- Continue Shopping -->
                <div class="mt-4 text-center">
                    <a href="{{ route('catalog') }}" wire:navigate
                        class="text-primary hover:text-primary/80 text-sm font-medium">
                        ← Lanjutkan Belanja
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>