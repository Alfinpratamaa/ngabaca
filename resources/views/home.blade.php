{{-- resources/views/home.blade.php --}}
<x-layouts.main>
    <section class="relative  text-white text-center px-4 sm:px-6 md:px-10 lg:px-16 py-20">
        <img alt="Illustration of stacked books and a cup on a greenish background" class="absolute inset-0 w-full h-full object-cover -z-10" height="400" src="/assets/images/hero-bg.png" style="opacity: 0.7" width="1200"/>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold max-w-4xl mx-auto leading-tight">
            Discover Your Next Great Read
        </h1>
        <p class="mt-2 max-w-xl mx-auto text-xs sm:text-sm md:text-base font-normal leading-snug">
            Explore our vast collection of books, from timeless classics to the latest releases. Find your next adventure today.
        </p>
        <form aria-label="Search for books, authors, or genres" class="mt-6 max-w-md mx-auto flex p-[6px] rounded-md overflow-hidden bg-white" role="search">
            <label class="sr-only" for="search-input">
                Search for books, authors, or genres
            </label>
            <input class="flex-grow px-4 py-2 text-gray-700 text-sm focus:outline-none" id="search-input" placeholder="Search for books, authors, or genres" type="search"/>
            <button class="bg-blue-600 text-white px-4 py-2 text-sm font-semibold hover:bg-blue-700 transition-colors rounded-md" type="submit">
                Search
            </button>
        </form>
    </section>
    <main class="px-4 sm:px-6 md:px-10 lg:px-16 py-12 max-w-7xl mx-auto">
        @php
            $featuredBooks = [
                [
                    'title' => 'The Enchanted Forests',
                    'author' => 'A tale of magic and adventure.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/35b5a0ba-1198-4c09-fa1d-e106c12e5b58.jpg',
                    'background_color' => 'bg-[#6a8a7f]',
                ],
                [
                    'title' => 'The Secret of the Old Mill',
                    'author' => 'A thrilling mystery in a small town.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/b4a2d738-b67f-41ad-3365-dbd4fdb265ad.jpg',
                    'background_color' => 'bg-[#f3f4f6]',
                ],
                [
                    'title' => 'Beyond the Stars',
                    'author' => 'Exploring the unknown reaches of space.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/504b21d1-aa6c-4f94-8dd9-f3ea9313739d.jpg',
                    'background_color' => 'bg-[#0f121b]',
                ],
                [
                    'title' => 'Love in the Time of Cholera',
                    'author' => 'A timeless love story.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/d25707e4-4dff-49d8-738c-ab9014701c56.jpg',
                    'background_color' => 'bg-[#f9fafb]',
                ],
            ];

            $newArrivals = [
                [
                    'title' => 'The Last Chronicle',
                    'author' => 'A new epic fantasy saga begins.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/730a046a-b367-4a90-2427-8d308bcdd6e7.jpg',
                    'background_color' => 'bg-gray-100', // Contoh tanpa warna spesifik
                ],
                [
                    'title' => 'Echoes of the Past',
                    'author' => 'Uncover the secrets of a forgotten era.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/8fb3c357-b1b5-4f35-8715-8fb7519f30e6.jpg',
                    'background_color' => 'bg-gray-100',
                ],
                [
                    'title' => 'The Silent Witness',
                    'author' => 'A gripping thriller that will keep you guessing.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/89b0a9c0-ae68-4143-0f61-ef8aaf077cb8.jpg',
                    'background_color' => 'bg-gray-100',
                ],
                [
                    'title' => 'Whispers of the Wind',
                    'author' => 'A heartwarming story of love and loss.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/f05c117b-6a07-4e98-5a7e-01f587800603.jpg',
                    'background_color' => 'bg-[#6a8a7f]',
                ],
            ];

            $bestsellers = [
                [
                    'title' => 'The Quantum Paradox',
                    'author' => 'A mind-bending science fiction adventure.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/0d5fb803-81e7-4568-411f-32ed9e51c4fc.jpg',
                    'background_color' => 'bg-gray-100',
                ],
                [
                    'title' => 'The Lost City',
                    'author' => 'An archaeologist\'s quest for a hidden civilization.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/06a28a67-8c5a-4004-ef53-bf72b31cdf20.jpg',
                    'background_color' => 'bg-[#8ab0b3]',
                ],
                [
                    'title' => 'Shadows of the Empire',
                    'author' => 'A tale of intrigue and power in a vast empire.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/c72306f8-e954-4f8e-f37c-d1613c201b88.jpg',
                    'background_color' => 'bg-gray-100',
                ],
                [
                    'title' => 'The Alchemist\'s Secret',
                    'author' => 'A young apprentice\'s journey to master the arcane arts.',
                    'cover_image' => 'https://storage.googleapis.com/a1aa/image/dbc93998-6a0d-4b81-bb22-e3da62b55390.jpg',
                    'background_color' => 'bg-gray-100',
                ],
            ];
        @endphp

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