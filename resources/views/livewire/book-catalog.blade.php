<div class="flex-1 px-2 py-3">

    <h1 class="text-3xl font-bold text-secondary mb-6">Katalog Buku</h1>

    <!-- Search Bar -->
    <div class="mb-6">
        <div class="relative">
            <input type="text" id="search" wire:model.live.debounce.300ms="search" placeholder="Cari buku..."
                class="w-full text-secondary pl-12 pr-12 py-3 rounded-lg border border-[#E2C9A0] bg-white text-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent placeholder:text-gray-400" />
            <flux:icon.magnifying-glass class="absolute text-secondary left-4 top-1/2 transform -translate-y-1/2"
                size="20" />

            @if ($search)
                <button wire:click="clearSearch"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        @if ($search)
            <div class="mt-2">
                <span class="text-sm text-gray-600">Menampilkan hasil pencarian untuk:
                    "<strong>{{ $search }}</strong>"</span>
            </div>
        @endif
    </div>

    <!-- Books Grid -->
    <div
        class="w-fit mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 md:grid-cols-2 justify-items-cente gap-y-5 gap-x-7 mt-10 mb-5">
        @forelse($books as $book)
            <!-- Book Card: Image rounded inside the card -->
            <div class="bg-white h-[29rem] w-sm max-w-[14rem] cursor-pointer rounded-xl shadow-md overflow-hidden flex flex-col"
                wire:key="book-{{ $book->id }}">
                <!-- Image Container with padding -->
                <div class="px-4 pb-4 pt-3">
                    <a href="{{ route('book.show', $book->slug) }}" wire:navigate>
                        <img src="{{ $book->cover_image_url ?? '/public/assets/images/cover-image-notfound.jpg' }}"
                            alt="{{ $book->title }}" class="w-full h-64 object-cover rounded-lg"
                            onerror="this.onerror=null;this.src='/public/assets/images/cover-image-notfound.jpg';" />
                    </a>
                </div>

                <!-- Content Container -->
                <div class="px-4 pb-4 flex flex-col flex-1">
                    <a href="{{ route('book.show', $book->slug) }}" wire:navigate class="flex-1">
                        <h3 class="font-bold text-md text-secondary mb-1 line-clamp-1">{{ $book->title }}</h3>
                        <p class="text-gray-600 text-sm mb-2 line-clamp-1">{{ $book->author }}</p>
                        <span class="text-secondary font-bold text-lg mb-3 block">Rp.
                            {{ number_format($book->price, 0, ',', '.') }}</span>
                    </a>

                    <div class="mt-auto">
                        @livewire('add-to-cart-button', ['book' => $book], key('add-to-cart-' . $book->id))
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                @if ($search)
                    <p class="text-gray-500 text-lg mb-4">Tidak ada buku yang ditemukan untuk pencarian
                        "{{ $search }}"</p>
                    <button wire:click="clearSearch"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-yellow-500 transition-colors">
                        Hapus Pencarian
                    </button>
                @else
                    <p class="text-gray-500 text-lg">Buku tidak ditemukan.</p>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $books->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('searchCleared', function() {
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.value = '';
            }
        });
    });
</script>
