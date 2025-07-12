<x-layouts.main>
    @livewire('cart-page')
    <script>
        // Refresh halaman saat pertama kali dimuat
        if (performance.navigation.type !== 1) {
            location.reload();
        }
    </script>
</x-layouts.main>