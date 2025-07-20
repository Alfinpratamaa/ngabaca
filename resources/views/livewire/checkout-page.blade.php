<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-2">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Alamat Pengiriman</h2>

            <form wire:submit.prevent="placeOrder">
                <div class="mb-4">
                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat
                        Lengkap</label>
                    <textarea wire:model.defer="shipping_address" id="shipping_address" rows="4"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('shipping_address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <div class="flex">
                        <span
                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">+62</span>
                        <input type="text" wire:model.defer="phone" id="phone" placeholder="82xxxxxxx"
                            class="flex-1 border-gray-300 rounded-r-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    @error('phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled" wire:target="placeOrder"
                    class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-md hover:bg-indigo-700 disabled:bg-indigo-300 transition-colors">
                    <span wire:loading.remove wire:target="placeOrder">Bayar Sekarang</span>
                    <span wire:loading wire:target="placeOrder">Memproses...</span>
                </button>
            </form>
        </div>
    </div>

    <div class="md:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>
            <div class="space-y-3">
                @forelse ($cartItems as $item)
                    <div class="flex justify-between items-center text-sm">
                        <span>{{ $item['title'] ?? 'Unknown Product' }} (x{{ $item['quantity'] ?? 0 }})</span>
                        <span class="font-medium">Rp
                            {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-gray-500">Keranjang Anda kosong.</p>
                @endforelse
            </div>
            <hr class="my-4">
            <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
