<x-layouts.main title="Katalog Buku"
    description="Temukan buku favorit Anda di katalog kami. Jelajahi berbagai genre dan penulis untuk menemukan bacaan yang sempurna.">
    <div class="min-h-screen bg-surface flex justify-center">
        <div class="flex w-full px-[10rem] gap-8 py-10">
            <!-- Sidebar kiri -->
            <div class="hidden max-w-[27rem] lg:block">
                @livewire('catalog-sidebar')
            </div>
            <!-- Sidebar sticky untuk mobile/tablet -->
            {{-- <div class="block lg:hidden mb-6">
            @livewire('catalog-sidebar')
        </div> --}}
            <!-- Grid katalog kanan -->
            <div class="flex-1">
                @livewire('book-catalog')
            </div>
        </div>
    </div>

    <script>
        // Refresh halaman saat pertama kali dimuat
        if (performance.navigation.type !== 1) {
            location.reload();
        }
    </script>
    
</x-layouts.main>
