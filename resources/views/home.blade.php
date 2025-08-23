{{-- resources/views/home.blade.php --}}
<x-layouts.main>
    <section class="relative  text-white text-center px-4 sm:px-6 md:px-10 lg:px-16 py-20">
        <img alt="Illustration of stacked books and a cup on a greenish background"
            class="absolute inset-0 w-full h-full object-cover -z-10" height="400" src="/assets/images/hero-bg.png"
            style="opacity: 0.7" width="1200" />
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold max-w-4xl mx-auto leading-tight">
            Discover Your Next Great Read
        </h1>
        <p class="mt-2 max-w-xl mx-auto text-xs sm:text-sm md:text-base font-normal leading-snug">
            Explore our vast collection of books, from timeless classics to the latest releases. Find your next
            adventure today.
        </p>
        <form aria-label="Search for books, authors, or genres" action="{{ route('catalog') }}""
            class="mt-6 max-w-md mx-auto flex p-[6px] rounded-md overflow-hidden bg-white" role="search">
            <label class="sr-only" for="search-input">
                Search for books, authors, or genres
            </label>
            <input class="flex-grow px-4 py-2 text-gray-700 text-sm focus:outline-none" id="search-input"
                value="{{ request('search') }}" name="search" placeholder="Search for books, authors, or genres"
                type="search" />
            <button
                class="bg-primary text-secondary px-4 py-2 text-sm font-semibold hover:bg-primary/75 transition-colors rounded-md"
                type="submit">
                Search
            </button>
        </form>
    </section>
    <main class="px-4 sm:px-6 md:px-10 lg:px-16 py-12 max-w-7xl mx-auto">
        <section class="mb-14">
            <h2 class="font-semibold text-gray-900 mb-6">
                Featured Books
            </h2>
            <ul aria-label="Featured Books" class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-8" role="list">
                @foreach ($featuredBooks as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </ul>
        </section>

        <section class="mb-14">
            <h2 class="font-semibold text-gray-900 mb-6">
                New Arrivals
            </h2>
            <ul aria-label="New Arrivals" class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-8" role="list">
                @foreach ($newArrivals as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </ul>
        </section>

        <section>
            <h2 class="font-semibold text-gray-900 mb-6">
                Bestsellers
            </h2>
            <ul aria-label="Bestsellers" class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-8" role="list">
                @foreach ($bestsellers as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </ul>
        </section>
    </main>

</x-layouts.main>
