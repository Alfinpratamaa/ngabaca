<div class="flex-1 px-2 py-3">
        
        <h1 class="text-3xl font-bold text-secondary mb-6">Katalog Buku</h1>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" wire:model.debounce.500ms="search" placeholder="Cari buku..."
                    class="w-full text-secondary pl-12 pr-4 py-3 rounded-lg border border-[#E2C9A0] bg-white text-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent placeholder:text-gray-400" />
                <flux:icon.magnifying-glass class="absolute text-secondary left-4 top-1/2 transform -translate-y-1/2 " size="20" />
            </div>
        </div>

        <!-- Books Grid -->
        <div class="w-fit mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 md:grid-cols-2 justify-items-center justify-center gap-y-20 gap-x-14 mt-10 mb-5">
            @forelse($books as $book)
                <!-- Book Card: Image rounded inside the card -->
                <div class="bg-white h-[28rem] w-sm max-w-[14rem] cursor-pointer rounded-xl shadow-md overflow-hidden flex flex-col" wire:key="book-{{ $book->id }}">
                    <!-- Image Container with padding -->
                    <div class="px-4 pb-4 pt-3">
                        <a href="{{ route('book.show', $book->slug) }}" wire:navigate>
                            <img src="{{ $book->cover_image_url ?? "/public/assets/images/cover-image-notfound.jpg" }}" alt="{{ $book->title }}"
                                class="w-full h-64 object-cover rounded-lg"
                                onerror="this.onerror=null;this.src='/public/assets/images/cover-image-notfound.jpg';" />
                        </a>
                    </div>

                    <!-- Content Container -->
                    <div class="px-4 flex flex-col flex-1">
                        <a href="{{ route('book.show', $book->slug) }}" wire:navigate>
                            <h3 class="font-bold text-md text-secondary mb-1 line-clamp-1">{{ $book->title }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $book->author }}</p>
                            <span class="text-secondary font-bold text-lg my-2">Rp.
                                {{ number_format($book->price, 0, ',', '.') }}</span>
                        </a>

                        <livewire:add-to-cart-button :book="$book" :key="'add-to-cart-' . $book->id" />
                    </div>
                </div>
                @empty
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
    
