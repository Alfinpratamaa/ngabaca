<div>
    @if ($order->status === 'pending' && $order->payment)
        @php
            $expiresAt = \Carbon\Carbon::parse($order->payment->expires_at)->setTimezone('Asia/Jakarta');
        @endphp

        @if ($expiresAt && now('Asia/Jakarta')->lessThan($expiresAt))
            <p class="text-yellow-600 mb-3">
                Bayar sebelum <span class="font-semibold">{{ $expiresAt->format('d M Y H:i') }}</span>
            </p>

            <div class="flex gap-3">
                <!-- Tombol Lanjutkan Pembayaran -->
                <button wire:click="continuePayment"
                    class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80 transition-colors">
                    Lanjutkan Pembayaran
                </button>

                <!-- Tombol Batalkan Pesanan -->
                <button wire:click="cancelOrder"
                    class="bg-muted text-gray-800 px-4 py-2 rounded-lg hover:bg-muted/80 transition-colors">
                    Batalkan Pesanan
                </button>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-4 text-red-700 font-semibold bg-red-50 rounded-lg">
                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>Waktu pembayaran sudah habis.</p>
                <p class="text-sm">Pesanan telah dibatalkan secara otomatis.</p>
            </div>
        @endif
    @endif
</div>
