<x-layouts.main :title="'Detail Pesanan #' . $order->id">
    <x-slot:header>
        <div class="bg-gradient-to-r from-blue-600 to-purple-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('orders') }}"
                        class="inline-flex items-center text-blue-100 hover:text-white transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali ke Riwayat Pesanan
                    </a>
                </div>
                <div class="mt-4">
                    <h1 class="text-2xl sm:text-3xl font-bold text-white">Detail Pesanan #{{ $order->id }}</h1>
                    <p class="text-blue-100 mt-1">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </x-slot:header>

    <div class="bg-surface min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status Pesanan
                        </h2>

                        @php
                            $currentStatus = $order->status;

                            $statuses = [
                                'pending' => ['label' => 'Menunggu Pembayaran', 'icon' => 'clock', 'color' => 'yellow'],
                                'diproses' => ['label' => 'Sedang Diproses', 'icon' => 'cog', 'color' => 'blue'],
                                'dikirim' => ['label' => 'Sedang Dikirim', 'icon' => 'truck', 'color' => 'purple'],
                                'selesai' => ['label' => 'Telah Diterima', 'icon' => 'check', 'color' => 'green'],
                            ];
                            $statusOrder = array_keys($statuses);
                            $currentIndex = array_search($currentStatus, $statusOrder);
                        @endphp

                        @if ($currentStatus === 'batal')
                            <div class="flex flex-col items-center justify-center py-8">
                                <svg class="w-16 h-16 text-red-500 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xl font-semibold text-red-700">Pesanan Dibatalkan</p>
                                <p class="text-gray-600 mt-2">Pesanan ini telah dibatalkan.</p>
                            </div>
                        @else
                            <div class="relative">
                                <div class="flex items-center justify-between">
                                    @foreach ($statusOrder as $index => $status)
                                        @php
                                            $isActive = $index <= $currentIndex;
                                            $isCurrent = $status === $currentStatus;
                                            $statusInfo = $statuses[$status];
                                            $color = $statusInfo['color'];
                                        @endphp

                                        <div
                                            class="flex flex-col items-center relative {{ $index < count($statusOrder) - 1 ? 'flex-1' : '' }}">
                                            <div
                                                class="w-12 h-12 rounded-full flex items-center justify-center mb-3 border-2 transition-all duration-300 
                                                {{ $isActive ? 'bg-' . $color . '-100 border-' . $color . '-500' : 'bg-gray-100 border-gray-300' }}">

                                                @if ($statusInfo['icon'] === 'clock')
                                                    <svg class="w-6 h-6 {{ $isActive ? 'text-' . $color . '-600' : 'text-gray-400' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @elseif($statusInfo['icon'] === 'cog')
                                                    <svg class="w-6 h-6 {{ $isActive ? 'text-' . $color . '-600' : 'text-gray-400' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                @elseif($statusInfo['icon'] === 'truck')
                                                    <svg class="w-6 h-6 {{ $isActive ? 'text-' . $color . '-600' : 'text-gray-400' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                                    </svg>
                                                @elseif($statusInfo['icon'] === 'x-circle')
                                                    {{-- New icon for cancelled --}}
                                                    <svg class="w-6 h-6 {{ $isActive ? 'text-' . $color . '-600' : 'text-gray-400' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @else
                                                    <svg class="w-6 h-6 {{ $isActive ? 'text-' . $color . '-600' : 'text-gray-400' }}"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                @endif

                                                @if ($isCurrent)
                                                    <div
                                                        class="absolute -inset-1 rounded-full bg-{{ $color }}-200 animate-pulse">
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="text-center">
                                                <p
                                                    class="text-sm font-medium {{ $isActive ? 'text-gray-900' : 'text-gray-500' }}">
                                                    {{ $statusInfo['label'] }}
                                                </p>
                                                @if ($isCurrent)
                                                    <p class="text-xs text-{{ $color }}-600 font-medium mt-1">
                                                        Saat
                                                        ini</p>
                                                @endif
                                            </div>

                                            @if ($index < count($statusOrder) - 1 && $status !== 'batal')
                                                {{-- Don't show connecting line for 'batal' --}}
                                                <div class="absolute top-6 left-1/2 w-full h-0.5 {{ $index < $currentIndex ? 'bg-' . $color . '-400' : 'bg-gray-200' }}"
                                                    style="transform: translateX(50%); z-index: -1;"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($currentStatus === 'shipped')
                            <div class="mt-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                                <p class="text-sm font-medium text-purple-900">Estimasi Pengiriman</p>
                                <p class="text-sm text-purple-700">
                                    {{ $order->created_at->addDays(3)->format('d F Y') }} -
                                    {{ $order->created_at->addDays(5)->format('d F Y') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Item Pesanan ({{ $order->orderItems->count() }} item)
                            </h2>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach ($order->orderItems as $item)
                                <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                                    <div class="flex items-start space-x-4">
                                        <div
                                            class="w-20 h-28 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg overflow-hidden shadow-lg">
                                            @if ($item->book && $item->book->cover_image_url)
                                                <img src="{{ $item->book->cover_image_url }}"
                                                    alt="{{ $item->book->title }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-white">
                                                    No Cover</div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $item->book->title ?? 'Unknown Book' }}</h3>
                                            <p class="text-sm text-gray-600">Oleh
                                                {{ $item->book->author ?? 'Unknown Author' }}</p>
                                            @if ($item->book->isbn)
                                                <p class="text-xs text-gray-500 mt-1">ISBN: {{ $item->book->isbn }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">Jumlah: <span
                                                    class="font-medium">{{ $item->quantity }}</span></p>
                                            <p class="text-lg font-bold text-secondary mt-1">
                                                Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Alamat Pengiriman
                        </h2>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="font-semibold text-gray-900">{{ $order->user->name ?? 'Customer' }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $order->user->phone ?? '-' }}</p>
                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                {{ $order->shipping_address ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>


                <div class="space-y-6">

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-secondary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            Ringkasan Pesanan
                        </h2>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center pb-2">
                                <span class="text-sm text-gray-600">ID Pesanan</span>
                                <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2">
                                <span class="text-sm text-gray-600">Tanggal Pesanan</span>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-2">
                                <span class="text-sm text-gray-600">Status</span>
                                @php
                                    $currentStatus = $order->status;
                                    $statuses = [
                                        'pending' => [
                                            'label' => 'Menunggu Pembayaran',
                                            'icon' => 'clock',
                                            'color' => 'yellow',
                                        ],
                                        'diproses' => [
                                            'label' => 'Sedang Diproses',
                                            'icon' => 'cog',
                                            'color' => 'blue',
                                        ],
                                        'dikirim' => [
                                            'label' => 'Sedang Dikirim',
                                            'icon' => 'truck',
                                            'color' => 'purple',
                                        ],
                                        'selesai' => [
                                            'label' => 'Telah Diterima',
                                            'icon' => 'check',
                                            'color' => 'green',
                                        ],
                                        'batal' => [
                                            // Add cancelled status here as well
                                            'label' => 'Dibatalkan',
                                            'icon' => 'x-circle',
                                            'color' => 'red',
                                        ],
                                    ];
                                    $status = $currentStatus;
                                    $colorClass = isset($statuses[$status])
                                        ? 'bg-' .
                                            $statuses[$status]['color'] .
                                            '-100 text-' .
                                            $statuses[$status]['color'] .
                                            '-800 border-' .
                                            $statuses[$status]['color'] .
                                            '-200'
                                        : 'bg-blue-100 text-blue-800 border-blue-200'; // Fallback for unknown status
                                    $statusLabel = $statuses[$status]['label'] ?? ucfirst($status);
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $colorClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            @php
                                $subtotal = collect($order->orderItems ?? [])->sum(function ($item) {
                                    return $item->price * $item->quantity;
                                });
                                $shipping = 15000; // Example shipping cost - consider using $order->shipping_cost
                                $tax = $subtotal * 0.11; // 11% tax - consider using $order->taxes
                                $total = $order->total_price ?? $subtotal + $shipping + $tax;
                            @endphp

                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Subtotal
                                        ({{ count($order->orderItems ?? []) }} item)</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Ongkos Kirim</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Pajak 10%</span>
                                    <span
                                        class="text-sm font-medium text-gray-900">Rp{{ number_format($order->taxes, 0, ',', '.') }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-base font-semibold text-gray-900">Total Pembayaran</span>
                                        <span
                                            class="text-xl font-bold text-secondary">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-900">Metode Pembayaran</span>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                                    <flux:icon.wallet class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $order->payment->payment_method ?? 'Tidak diketahui' }}</p>
                                </div>
                            </div>
                        </div>

                        <livewire:payment-action-button :orderId="$order->id" class="mt-6" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
