<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ __('Order List') }}</h1>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tanggal Pesanan') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Customer') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Harga Total') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status Order')}}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Alamat Pengiriman') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Updated-At') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr wire:key="order-{{$order->id}}" class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $orders->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->user->name ?? $order->user_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp{{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>

                        <!-- Status Order dengan Dropdown -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative inline-block" x-data="{ open: false }" @click.away="open = false">
                                <!-- Status Badge (Clickable) -->
                                <button
                                    @click="open = !open"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium rounded-full transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 group
                                        @if($order->display_status === 'Diproses')
                                            bg-yellow-100 text-yellow-800 border border-yellow-200 hover:bg-yellow-200 focus:ring-yellow-500
                                        @elseif($order->display_status === 'Terpenuhi')
                                            bg-green-100 text-green-800 border border-green-200 hover:bg-green-200 focus:ring-green-500
                                        @elseif($order->display_status === 'Batal')
                                            bg-red-100 text-red-800 border border-red-200 hover:bg-red-200 focus:ring-red-500
                                        @else
                                            bg-gray-100 text-gray-800 border border-gray-200 hover:bg-gray-200 focus:ring-gray-500
                                        @endif">

                                    <!-- Status Icon -->
                                    @if($order->display_status === 'Diproses')
                                        <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($order->display_status === 'Terpenuhi')
                                        <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($order->display_status === 'Batal')
                                        <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif

                                    <span class="mr-1">{{ $order->display_status }}</span>

                                    <!-- Dropdown Arrow -->
                                    <svg class="w-3 h-3 transition-transform duration-200 transform group-hover:scale-110" :class="{ 'rotate-180': open }" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                                    style="display: none;">

                                    <div class="py-1">
                                        <!-- Current Status (Disabled) -->
                                        <div class="px-4 py-2 text-xs font-medium text-gray-400 bg-gray-50 border-b border-gray-100">
                                            Status Saat Ini
                                        </div>

                                        <!-- Diproses Option -->
                                        @if($order->display_status !== 'Diproses')
                                            <button
                                                wire:click="updateOrderStatus({{ $order->id }}, 'Diproses')"
                                                @click="open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-800 flex items-center transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Diproses
                                            </button>
                                        @endif

                                        <!-- Terpenuhi Option -->
                                        @if($order->display_status !== 'Terpenuhi')
                                            <button
                                                wire:click="updateOrderStatus({{ $order->id }}, 'Terpenuhi')"
                                                @click="open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 flex items-center transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                Terpenuhi
                                            </button>
                                        @endif

                                        <!-- Batal Option -->
                                        @if($order->display_status !== 'Batal')
                                            <button
                                                wire:click="updateOrderStatus({{ $order->id }}, 'Batal')"
                                                @click="open = false"
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-800 flex items-center transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                                Batal
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->shipping_address}}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ __('No orders found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
