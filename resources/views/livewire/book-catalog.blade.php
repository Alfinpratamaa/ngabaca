    <div class="flex-1 px-2 py-3">
        <h1 class="text-3xl font-bold text-secondary mb-6">Katalog Buku</h1>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" wire:model.debounce.500ms="search" placeholder="Cari buku..."
                    class="w-full text-secondary pl-12 pr-4 py-3 rounded-lg border border-[#E2C9A0] bg-white text-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent placeholder:text-gray-400" />
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 h-6 w-6 text-secondary" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                </svg>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($books as $book)
                <!-- Book Card: Image rounded inside the card -->
                <div class="bg-white cursor-pointer rounded-xl shadow-md overflow-hidden flex flex-col transform hover:-translate-y-1 transition-transform duration-300">
                    <!-- Image Container with padding -->
                    <div class="p-4">
                        <a href="{{ route('book.show', $book->slug) }}" wire:navigate>
                            <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}"
                                class="w-full h-64 object-cover rounded-lg"
                                onerror="this.onerror=null;this.src='https://placehold.co/400x600/e2c9a0/6B3F13?text=Cover+Not+Found';" />
                        </a>
                    </div>

                    <!-- Content Container -->
                    <div class="px-4 pb-4 flex flex-col flex-1">
                        <a href="{{ route('book.show', $book->slug) }}" wire:navigate>
                            <h3 class="font-bold text-lg text-secondary mb-1">{{ $book->title }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $book->author }}</p>
                            <span class="text-secondary font-semibold text-xl mb-3">Rp.
                                {{ number_format($book->price, 0, ',', '.') }}</span>
                        </a>

                        <!-- Button is pushed to the bottom of the card -->
                        <button
                            wire:click="addToCart({{ $book->id }})"
                            class="w-full cursor-pointer bg-primary hover:bg-primary text-secondary font-bold py-2 rounded-md mt-auto transition-colors">Add
                            To Cart</button>
                    </div>
                </div>    @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">Buku tidak ditemukan.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $books->links() }}
        </div>
    </div>
