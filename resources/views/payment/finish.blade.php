<x-layouts.main title="Pembayaran Selesai">
    <div class="container mx-auto py-12 px-4 text-center">

        @if (isset($status))
            @if ($status == 'capture' || $status == 'settlement')
                {{-- Status Sukses --}}
                <div
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-6 rounded-lg shadow-md max-w-2xl mx-auto">
                    <h1 class="text-3xl font-bold mb-2">✅ Pembayaran Berhasil!</h1>
                    <p class="text-lg">Terima kasih. Pesanan Anda dengan ID #{{ $order->id }} telah kami terima dan
                        akan segera diproses.</p>
                </div>
            @elseif($status == 'pending')
                {{-- Status Tertunda --}}
                <div
                    class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded-lg shadow-md max-w-2xl mx-auto">
                    <h1 class="text-3xl font-bold mb-2">⏳ Menunggu Pembayaran</h1>
                    <p class="text-lg">Pesanan Anda dengan ID #{{ $order->id }} sedang menunggu pembayaran. Silakan
                        selesaikan pembayaran Anda.</p>
                </div>
            @else
                {{-- Status Gagal (deny, cancel, expire) --}}
                <div
                    class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg shadow-md max-w-2xl mx-auto">
                    <h1 class="text-3xl font-bold mb-2">❌ Pembayaran Gagal</h1>
                    <p class="text-lg">
                        Maaf, terjadi masalah dengan pembayaran untuk pesanan ID #{{ $order->id }}.
                        @if ($status == 'expire')
                            Status: Waktu pembayaran telah habis.
                        @else
                            Status: {{ ucfirst($status) }}.
                        @endif
                    </p>
                    <p class="mt-2">Silakan coba lagi atau hubungi dukungan pelanggan kami.</p>
                </div>
            @endif

            <div class="mt-8">
                <a href="{{ route('home') }}"
                    class="bg-primary text-white font-bold py-3 px-6 rounded-md hover:bg-opacity-80 transition-colors">
                    Kembali ke Halaman Utama
                </a>
            </div>
        @else
            {{-- Pesan Error Umum jika status tidak ditemukan --}}
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg shadow-md max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold mb-2">Terjadi Kesalahan</h1>
                <p class="text-lg">
                    {{ $message ?? 'Tidak dapat menampilkan status pesanan. Silakan hubungi dukungan pelanggan.' }}</p>
            </div>
        @endif

    </div>
</x-layouts.main>
