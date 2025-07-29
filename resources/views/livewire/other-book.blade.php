<div class="mt-16 relative">
    <h2 class="text-2xl font-bold text-secondary mb-6">Buku Terkait</h2>

    {{-- Tombol Navigasi --}}
    <button id="scrollLeft"
        class="hidden absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow-md rounded-full p-2"
        aria-label="Scroll Kiri">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <div id="bookScrollContainer"
        class="flex overflow-x-auto gap-4 scroll-smooth px-2 pb-2 no-scrollbar">
        @forelse ($books as $book)
            <div class="min-w-[200px] max-w-[200px] bg-white rounded-lg shadow-md overflow-hidden flex-shrink-0">
                <a href="{{ route('book.show', $book->slug) }}" class="block">
                    <img src="{{ $book->cover_image_url ?? '/images/default-book-cover.jpg' }}"
                         alt="{{ $book->title }}"
                         class="w-full h-48 object-cover" />
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-secondary line-clamp-2">{{ $book->title }}</h3>
                        <p class="text-xs text-gray-500">{{ $book->author }}</p>
                        <p class="text-primary font-bold mt-1 text-sm">Rp. {{ number_format($book->price, 0, ',', '.') }}</p>
                    </div>
                </a>
            </div>
        @empty
            <p class="text-gray-500">Tidak ada buku terkait.</p>
        @endforelse
    </div>

    <button id="scrollRight"
        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow-md rounded-full p-2"
        aria-label="Scroll Kanan">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5l7 7-7 7" />
        </svg>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('bookScrollContainer');
        const leftBtn = document.getElementById('scrollLeft');
        const rightBtn = document.getElementById('scrollRight');

        const scrollAmount = 220;

        function updateButtons() {
            const maxScrollLeft = container.scrollWidth - container.clientWidth;
            leftBtn.classList.toggle('hidden', container.scrollLeft <= 0);
            rightBtn.classList.toggle('hidden', container.scrollLeft >= maxScrollLeft - 5);
        }

        rightBtn.addEventListener('click', () => {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });

        leftBtn.addEventListener('click', () => {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        container.addEventListener('scroll', updateButtons);
        window.addEventListener('resize', updateButtons);

        updateButtons(); // Initial check
    });
</script>
