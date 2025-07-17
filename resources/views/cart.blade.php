<x-layouts.main title="Keranjang Belanja" description="Kelola item yang ingin Anda beli.">
    @livewire('cart-page')
    <script>
        // Refresh halaman saat pertama kali dimuat
        if (performance.navigation.type !== 1) {
            location.reload();
        }
    </script>
</x-layouts.main>
