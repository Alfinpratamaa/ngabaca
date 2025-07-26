<x-layouts.main title="Katalog Buku"
    description="Temukan buku favorit Anda di katalog kami. Jelajahi berbagai genre dan penulis untuk menemukan bacaan yang sempurna.">
    <div class="min-h-screen flex max-w-7xl justify-center mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row w-full gap-4 sm:gap-6 lg:gap-8 py-4 sm:py-6 lg:py-10">
            <!-- Sidebar untuk desktop -->
            <div class="hidden lg:block lg:w-80 flex-shrink-0">
                @livewire('catalog-sidebar')
            </div>

            <!-- Sidebar untuk mobile/tablet (collapsible) -->
            <div class="block lg:hidden w-full mb-4">
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <button type="button"
                        class="w-full flex items-center justify-between text-left font-medium text-gray-900"
                        onclick="toggleSidebar()">
                        <span>Filter & Kategori</span>
                        <svg id="sidebar-icon" class="w-5 h-5 transform transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div id="mobile-sidebar" class="hidden mt-4">
                        @livewire('catalog-sidebar')
                    </div>
                </div>
            </div>

            <!-- Grid katalog -->
            <div class="flex-1 w-full min-w-0">
                @livewire('book-catalog')
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar untuk mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const icon = document.getElementById('sidebar-icon');

            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                sidebar.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Refresh halaman saat pertama kali dimuat
        if (performance.navigation.type !== 1) {
            location.reload();
        }
    </script>

</x-layouts.main>
