<div class="">
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

<!-- ALTERNATIF DESAIN UNTUK INPUT JUMLAH

<div class="relative flex items-center max-w-[8rem]">
    # Tombol minus
    <button onclick="decrement()"
        class="hover:bg-primary hover:text-white transition-colors border border-primary rounded-s-lg p-3 h-11">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
        </svg>
    </button>

    # Input jumlah
    <input type="text" id="quantity" name="quantity" value="1"
        class="h-11 text-center text-sm block w-full py-2.5 border-y border-primary text-gray-900" placeholder="999" required oninput="sanitizeInput(this)">

    # Tombol plus
    <button onclick="increment()"
        class="bg-primary border border-primary hover:bg-primary/90 transition-colors rounded-e-lg p-3 h-11">
        <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
        </svg>
    </button>
</div>


\<script>
    function increment() {
    let qty = document.getElementById('quantity');
    let current = parseInt(qty.value) || 0; // kalau kosong, mulai dari 0
    qty.value = current + 1;
}

function decrement() {
    let qty = document.getElementById('quantity');
    let current = parseInt(qty.value) || 0;
    if (current > 1) {
        qty.value = current - 1;
    } else {
        qty.value = ''; // kembali kosong jika di bawah 1
    }
}

function sanitizeInput(input) {
    // Hanya izinkan angka
    input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>

-->
