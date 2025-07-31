<x-layouts.main title="Riwayat Pesanan">
    <x-slot:header>
        <div class="bg-gradient-to-r from-blue-600 to-purple-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 class="text-3xl font-bold text-white">Riwayat Pesanan</h1>
                <p class="text-blue-100 mt-2">Kelola dan lacak semua pesanan Anda</p>
            </div>
        </div>
    </x-slot:header>

    @if (session()->has('info'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Info</strong>
            <span class="block sm:inline">{{ session('info') }}</span>
        </div>
    @endif

    <div class="bg-surface min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if ($orders->isEmpty() && !request()->has('search') && !request()->has('status'))
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Pesanan</h3>
                        <p class="text-gray-500 mb-8">Anda belum memiliki riwayat pesanan. Mulai berbelanja sekarang dan
                            temukan produk favorit Anda!</p>
                        <a href="{{ route('catalog') }}"
                            class="inline-flex items-center px-6 py-3 bg-primary text-white font-medium rounded-xl hover:bg-primary/80 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Mulai Belanja
                        </a>
                    </div>
                </div>
            @else
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Total</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $stats['total'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Pending</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Diproses</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $stats['diproses'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Dikirim</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $stats['dikirim'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Selesai</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $stats['selesai'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                    <form method="GET" action="{{ route('orders') }}" class="space-y-6">
                        <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                            <!-- Search Input -->
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cari Pesanan
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="search" name="search"
                                        value="{{ request('search') }}"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Cari berdasarkan ID pesanan, judul buku, atau penulis...">
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="min-w-0 flex-shrink-0 lg:w-48">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status
                                </label>
                                <select id="status" name="status"
                                    class="block w-full py-3 px-3 border border-gray-300 bg-white rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua
                                        Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        Menunggu Pembayaran</option>
                                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>
                                        Diproses</option>
                                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>
                                        Dikirim</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                        Selesai</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div class="min-w-0 flex-shrink-0 lg:w-40">
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">
                                    Dari Tanggal
                                </label>
                                <input type="date" id="date_from" name="date_from"
                                    value="{{ request('date_from') }}"
                                    class="block w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <!-- Date To -->
                            <div class="min-w-0 flex-shrink-0 lg:w-40">
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sampai Tanggal
                                </label>
                                <input type="date" id="date_to" name="date_to"
                                    value="{{ request('date_to') }}"
                                    class="block w-full py-3 px-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Cari
                                </button>
                                <a href="{{ route('orders') }}"
                                    class="inline-flex items-center px-4 py-3 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Quick Filter Tabs -->
                <div class="mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1">
                        <div class="flex space-x-1 overflow-x-auto">
                            <a href="{{ route('orders', ['status' => 'all'] + request()->except('status')) }}"
                                class="flex-1 min-w-0 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ !request('status') || request('status') == 'all' ? 'text-white bg-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                                Semua Pesanan
                            </a>
                            <a href="{{ route('orders', ['status' => 'pending'] + request()->except('status')) }}"
                                class="flex-1 min-w-0 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') == 'pending' ? 'text-white bg-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                                Menunggu Pembayaran
                            </a>
                            <a href="{{ route('orders', ['status' => 'diproses'] + request()->except('status')) }}"
                                class="flex-1 min-w-0 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') == 'diproses' ? 'text-white bg-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                                Diproses
                            </a>
                            <a href="{{ route('orders', ['status' => 'dikirim'] + request()->except('status')) }}"
                                class="flex-1 min-w-0 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') == 'dikirim' ? 'text-white bg-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                                Dikirim
                            </a>
                            <a href="{{ route('orders', ['status' => 'selesai'] + request()->except('status')) }}"
                                class="flex-1 min-w-0 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request('status') == 'selesai' ? 'text-white bg-blue-600' : 'text-gray-600 hover:text-blue-600 hover:bg-blue-50' }}">
                                Selesai
                            </a>
                        </div>
                    </div>
                </div>

                @if ($orders->isEmpty())
                    <!-- No Results State -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 text-center py-16">
                        <div class="max-w-md mx-auto">
                            <div
                                class="w-20 h-20 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Pesanan Ditemukan</h3>
                            <p class="text-gray-500 mb-6">Coba ubah filter pencarian atau kata kunci yang Anda gunakan.
                            </p>
                            <a href="{{ route('orders') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Lihat Semua Pesanan
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Orders Grid -->
                    <div class="space-y-6">
                        @foreach ($orders as $order)
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 hover:border-blue-200">
                                <!-- Order Header -->
                                <div
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                                <span class="text-sm font-medium text-gray-600">Pesanan</span>
                                                <span
                                                    class="text-sm font-bold text-gray-900">#{{ $order->id }}</span>
                                            </div>
                                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-3">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                    'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                    'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                    'delivered' => 'bg-green-100 text-green-800 border-green-200',
                                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Menunggu Pembayaran',
                                                    'processing' => 'Diproses',
                                                    'shipped' => 'Dikirim',
                                                    'delivered' => 'Selesai',
                                                    'cancelled' => 'Dibatalkan',
                                                ];
                                                $status = $order->status ?? 'processing';
                                                $colorClass = $statusColors[$status] ?? $statusColors['processing'];
                                                $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $colorClass }}">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full mr-2 {{ str_replace(['text-', 'border-'], 'bg-', $colorClass) }}"></span>
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Content -->
                                <div class="p-6">
                                    <!-- Items List -->
                                    <div class="space-y-4 mb-6">
                                        @foreach ($order->orderItems ?? [] as $index => $orderItem)
                                            <div
                                                class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl {{ $index >= 2 ? 'hidden order-item-hidden' : '' }}">
                                                <div
                                                    class="w-16 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    @if ($orderItem->book && $orderItem->book->cover_image_url)
                                                        <img src="{{ $orderItem->book->cover_image_url }}"
                                                            alt="{{ $orderItem->book->title }}"
                                                            class="w-full h-full object-cover rounded-lg">
                                                    @else
                                                        <svg class="w-8 h-8 text-white" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-1 line-clamp-2">
                                                        {{ $orderItem->book->title ?? 'Unknown Book' }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500 mb-2">
                                                        {{ $orderItem->book->author ?? 'Unknown Author' }}
                                                    </p>
                                                    <div class="flex items-center justify-between">
                                                        <span
                                                            class="text-xs text-gray-600 bg-white px-2 py-1 rounded-md border">
                                                            Qty: {{ $orderItem->quantity }}
                                                        </span>
                                                        <span class="text-sm font-semibold text-gray-900">
                                                            Rp{{ number_format($orderItem->price * $orderItem->quantity, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if (count($order->orderItems ?? []) > 2)
                                            <button
                                                class="w-full text-center py-3 text-sm text-blue-600 hover:text-blue-700 font-medium hover:bg-blue-50 rounded-xl transition-all duration-200 toggle-items">
                                                Lihat {{ count($order->orderItems) - 2 }} item lainnya
                                                <svg class="w-4 h-4 inline ml-1 transition-transform duration-200"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="border-t border-gray-200 pt-6">
                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                            <div class="flex items-center space-x-6">
                                                <div class="text-sm">
                                                    <span class="text-gray-600">Total Item:</span>
                                                    <span
                                                        class="font-semibold text-gray-900 ml-1">{{ count($order->orderItems ?? []) }}</span>
                                                </div>
                                                <div class="text-lg">
                                                    <span class="text-gray-600">Total Bayar:</span>
                                                    <span class="font-bold text-blue-600 ml-2">
                                                        Rp{{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-3">
                                                @if ($status === 'delivered')
                                                    <button
                                                        class="px-4 py-2 text-sm font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition-all duration-200">
                                                        Beli Lagi
                                                    </button>
                                                    <button
                                                        class="px-4 py-2 text-sm font-medium text-green-600 border border-green-200 rounded-lg hover:bg-green-50 transition-all duration-200">
                                                        Beri Ulasan
                                                    </button>
                                                @endif
                                                <a href="{{ route('orders.show', $order->id) }}"
                                                    class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if (method_exists($orders, 'links'))
                        <div class="mt-8">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle items functionality
            document.querySelectorAll('.toggle-items').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.bg-white');
                    const hiddenItems = card.querySelectorAll('.order-item-hidden');
                    const icon = this.querySelector('svg');

                    hiddenItems.forEach(item => {
                        item.classList.toggle('hidden');
                    });

                    if (hiddenItems[0].classList.contains('hidden')) {
                        this.innerHTML =
                            `Lihat ${hiddenItems.length} item lainnya <svg class="w-4 h-4 inline ml-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>`;
                    } else {
                        this.innerHTML =
                            `Sembunyikan item <svg class="w-4 h-4 inline ml-1 transition-transform duration-200 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>`;
                    }
                });
            });

            // Auto-submit form on filter change (optional)
            const statusSelect = document.getElementById('status');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    // Uncomment the line below if you want auto-submit on status change
                    // this.form.submit();
                });
            }

            // Date validation
            const dateFrom = document.getElementById('date_from');
            const dateTo = document.getElementById('date_to');

            if (dateFrom && dateTo) {
                dateFrom.addEventListener('change', function() {
                    dateTo.min = this.value;
                });

                dateTo.addEventListener('change', function() {
                    dateFrom.max = this.value;
                });
            }
        });
    </script>
</x-layouts.main>
