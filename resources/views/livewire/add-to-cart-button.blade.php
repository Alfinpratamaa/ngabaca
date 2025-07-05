<div>
    @if(isset($cartItems[$book->id]))
        <div class="flex items-center justify-center space-x-3" wire:key="cart-controls-{{ $book->id }}">
            <button
                wire:click="decreaseQuantity({{ $book->id }})"
                class="w-8 h-8 cursor-pointer bg-muted hover:bg-muted/75 text-secondary rounded-md flex items-center justify-center font-bold transition-colors">
                -
            </button>
            <span class="text-lg font-semibold text-secondary min-w-[2rem] text-center">
                {{ $cartItems[$book->id]['quantity'] }}
            </span>
            <button wire:click="increaseQuantity({{ $book->id }})"
                    class="w-8 h-8 cursor-pointer bg-primary hover:bg-primary/75 text-secondary rounded-md flex items-center justify-center font-bold transition-colors">
                +
            </button>
        </div>
    @else
        <button wire:click="addToCart({{ $book->id }})"
                class="w-full cursor-pointer bg-primary hover:bg-primary/75 text-secondary font-semibold py-2 px-4 rounded-lg transition-colors">
            Tambah ke Keranjang
        </button>
    @endif
</div>