<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">{{ __('Order List') }}</h1>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            @if ($hasChanges)
                <button wire:click="resetChanges"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Reset
                </button>
            @endif

            <button wire:click="saveChanges"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200
                   {{ $hasChanges ? 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500' : 'text-gray-400 bg-gray-200 border border-gray-200 cursor-not-allowed' }} 
                   ">

                <svg class="w-4
                h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>

                {{ $hasChanges ? __('Save Changes') : __('No Changes') }}
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <span class="block sm:inline">{{ session('message') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="show = false">
                <svg class="fill-current h-6 w-6 text-green-500 cursor-pointer" role="button"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <span class="block sm:inline">{{ session('error') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20">
                    <title>Close</title>
                    <path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Tanggal Pesanan') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Customer') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Harga Total') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Status Order') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Alamat Pengiriman') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ __('Updated-At') }}</th>

                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr wire:key="order-{{ $order->id }}"
                        class="hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex items-center">
                                {{ $order->id }}
                                @if ($order->has_pending_changes)
                                    <span
                                        class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $order->user->name ?? $order->user_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp{{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                        </td>

                        <!-- Status Order dengan Dropdown -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <flux:dropdown>
                                <flux:button id="status-button-{{ $order->id }}" icon:trailing="chevron-down"
                                    class="text-xs font-medium rounded-full border transition-all duration-200 
                                    {{ $order->display_status === 'Diproses'
                                        ? 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                        : ($order->display_status === 'Dikirim'
                                            ? 'bg-blue-100 text-blue-800 border-blue-200'
                                            : ($order->display_status === 'Selesai'
                                                ? 'bg-green-100 text-green-800 border-green-200'
                                                : 'bg-red-100 text-red-800 border-red-200')) }}">
                                    <div class="flex items-center gap-2">
                                        @if ($order->display_status === 'Diproses')
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Diproses
                                        @elseif($order->display_status === 'Dikirim')
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 3h14v14H3V3zm4 4h6v6H7V7z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Dikirim
                                        @elseif($order->display_status === 'Selesai')
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Selesai
                                        @else
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Batal
                                        @endif
                                    </div>
                                </flux:button>

                                <flux:menu>
                                    <flux:menu.radio.group wire:model.live="orderStatuses.{{ $order->id }}">
                                        <flux:menu.radio value="diproses"
                                            wire:click="updateOrderStatus({{ $order->id }}, 'diproses')">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3 h-3 text-yellow-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Diproses
                                            </div>
                                        </flux:menu.radio>

                                        <flux:menu.radio value="dikirim"
                                            wire:click="updateOrderStatus({{ $order->id }}, 'dikirim')">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3 h-3 text-blue-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 3h14v14H3V3zm4 4h6v6H7V7z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Dikirim
                                            </div>
                                        </flux:menu.radio>

                                        <flux:menu.radio value="selesai"
                                            wire:click="updateOrderStatus({{ $order->id }}, 'selesai')">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3 h-3 text-green-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Selesai
                                            </div>
                                        </flux:menu.radio>

                                        <flux:menu.radio value="batal"
                                            wire:click="updateOrderStatus({{ $order->id }}, 'batal')">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3 h-3 text-red-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Batal
                                            </div>
                                        </flux:menu.radio>

                                    </flux:menu.radio.group>
                                </flux:menu>
                            </flux:dropdown>
                        </td>



                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->shipping_address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $order->updated_at->format('d M Y, H:i') }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ __('No orders found') }}</td>
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
<script>
    document.addEventListener('livewire:init', function() {
        Livewire.on('status-updated', ({
            orderId,
            newStatus
        }) => {
            // Ganti tampilan flux:button sementara (realtime) — DOM only
            const button = document.getElementById(`status-button-${orderId}`);
            if (!button) return;

            // Reset warna
            button.classList.remove('bg-yellow-100', 'text-yellow-800', 'border-yellow-200');
            button.classList.remove('bg-green-100', 'text-green-800', 'border-green-200');
            button.classList.remove('bg-red-100', 'text-red-800', 'border-red-200');

            // Atur tampilan berdasarkan status baru
            switch (newStatus) {
                case 'Diproses':
                    button.classList.add('bg-yellow-100', 'text-yellow-800', 'border-yellow-200');
                    button.innerHTML = `<div class="flex items-center gap-2">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                  clip-rule="evenodd" />
                        </svg>
                        Diproses
                    </div>`;
                    break;
                case 'Terpenuhi':
                    button.classList.add('bg-green-100', 'text-green-800', 'border-green-200');
                    button.innerHTML = `<div class="flex items-center gap-2">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                  clip-rule="evenodd" />
                        </svg>
                        Terpenuhi
                    </div>`;
                    break;
                case 'Batal':
                    button.classList.add('bg-red-100', 'text-red-800', 'border-red-200');
                    button.innerHTML = `<div class="flex items-center gap-2">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                  clip-rule="evenodd" />
                        </svg>
                        Batal
                    </div>`;
                    break;
            }
        });

        // Jika reset, refresh halaman saja (opsional)
        Livewire.on('changes-reset', () => {
            window.location.reload(); // atau bisa manual reset DOM jika tidak ingin reload
        });
    });
</script>
