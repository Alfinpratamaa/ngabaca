<a href="{{ route('cart') }}" wire:navigate class="p-1 rounded-md bg-muted relative">
    <flux:button variant="ghost" size="sm" class=" cursor-pointer">
        <flux:icon.shopping-bag class="h-5 w-5 text-gray-600" />
    </flux:button>
    {{-- Tampilkan jumlah item di sini --}}
    @if ($itemCount > 0)
        <span
            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">
            {{ $itemCount }}
        </span>
    @endif
</a>
