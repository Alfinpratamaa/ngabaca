<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-secondary mb-8">Keranjang Belanja Anda</h1>

    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">WOI</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if (empty($cartItems))
        <!-- Empty Cart View -->
        <div class="text-center py-12 bg-white rounded-xl shadow-md">
            <p class="text-gray-600 text-lg mb-4">Keranjang Anda kosong.</p>
            <a href="{{ route('catalog') }}" wire:navigate
                class="bg-primary hover:bg-primary/90 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items List -->
            <div class="lg:w-2/3 bg-white rounded-xl shadow-md p-6">
                <h2 class="text-2xl font-bold text-secondary mb-6">Item di Keranjang</h2>
                <div class="space-y-6">
                    @foreach ($cartItems as $id => $item)
                        <div class="flex items-center border-b pb-4 last:border-b-0 last:pb-0">
                            <!-- PERUBAHAN: Checkbox untuk memilih item -->
                            <div class="mr-4">
                                <input type="checkbox" wire:model.live="selectedItems" value="{{ $id }}"
                                    class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            </div>

                            <img src="{{ $item['cover_image_url'] }}" alt="{{ $item['title'] }}"
                                class="w-24 h-24 object-cover rounded-lg mr-4 flex-shrink-0"
                                onerror="this.onerror=null;this.src='https://placehold.co/400x600/e2c9a0/6B3F13?text=No+Image';">

                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-secondary">{{ $item['title'] }}</h3>
                                <p class="text-gray-600">Harga: {{ $this->formatPrice($item['price']) }}</p>
                                <div class="flex items-center mt-2">
                                    <button wire:click="decreaseQuantity({{ $id }})"
                                        class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md hover:bg-gray-300 transition-colors">-</button>
                                    <span class="mx-3 text-lg font-semibold">{{ $item['quantity'] }}</span>
                                    <button wire:click="increaseQuantity({{ $id }})"
                                        class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md hover:bg-gray-300 transition-colors">+</button>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Subtotal:
                                    {{ $this->formatPrice($item['price'] * $item['quantity']) }}</p>
                            </div>

                            <button wire:click="removeItem({{ $id }})"
                                class="text-red-500 hover:text-red-700 ml-4 p-2 rounded-full hover:bg-red-50 transition-colors">
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
            <div class="lg:w-1/3 bg-white rounded-xl shadow-md p-6 self-start sticky top-8">
                <h2 class="text-2xl font-bold text-secondary mb-6">Ringkasan Checkout</h2>

                <!-- PERUBAHAN: Menghapus biaya pengiriman -->
                <div class="space-y-3 mb-6 text-lg">
                    <div class="flex justify-between text-xl font-bold">
                        <span class="text-secondary">Total ({{ count($selectedItems) }} item)</span>
                        <span class="text-primary">{{ $this->formatPrice($selectedSubtotal) }}</span>
                    </div>
                </div>

                <!-- PERUBAHAN: Tombol checkout dinonaktifkan jika tidak ada item dipilih -->
                <button wire:click="proceedToCheckout"
                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 disabled:opacity-50 disabled:cursor-not-allowed"
                    @if (empty($selectedItems)) disabled @endif>
                    Lanjutkan ke Checkout ({{ count($selectedItems) }})
                </button>

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
