<x-layouts.main title="{{ $book->title }}" description="{{ $book->description }}">
    <div class="min-h-screen bg-surface flex justify-center">
        <div class="max-w-4xl w-full p-6">

            {{-- breadcrumb navigation --}}
            <div class="text-sm mb-5">
                <ol class="list-none flex space-x-2">
                    <li class="text-gray-500"><a href="{{ route('catalog') }}">Katalog</a></li>
                    <li>/</li>
                    <li class="text-black">{{ $book->title }}</li>
                </ol>
            </div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/3 p-6">
                        <img src="{{ $book->cover_image_url ?? '/images/default-book-cover.jpg' }}" alt="{{ $book->title }}"
                            class="w-full h-auto rounded-lg shadow-md">
                    </div>
                    <div class="md:w-2/3 p-6">
                        <h1 class="text-3xl font-bold text-secondary mb-2">{{ $book->title }}</h1> {{-- Judul Buku --}}

                        <div class="mb-1"> {{-- nama penulis --}}
                            <span class="text-sm text-secondary font-medium">Penulis:</span>
                            <span class="text-sm text-secondary">{{ $book->author }}</span>
                        </div>

                        <div class="flex items-center mb-1"> {{-- Rating Buku, menggunakan shadow custom (x_y_blurRad_Warna) --}}
                            {{--
                            Icon Bintang Masih Belum Sesuai dengan Jumlah rating
                            Alternatif nya menggunakan 1 bintang saja agar memberikan konteks implisit bahwa angka disebelahnya adalah rating //Gilang
                            --}}
                            <svg class="w-4 h-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                            </svg>
                            <svg class="w-4 h-4 text-yellow-300 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                            </svg>
                            <svg class="w-4 h-4 ms-1 text-gray-300 dark:text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20">
                                <path d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z"/>
                            </svg>
                            <span class="w-1 h-1 mx-1.5 bg-gray-500 rounded-full dark:bg-gray-400"></span>
                            <p class="text-sm font-bold text-gray-900 dark:text-white"> {{ $book->rating }} </p> {{-- Rating Mengambil dari Database Books --}}
                            <span class="ms-1 text-gray-500 ">|</span>
                            <span class="text-sm ms-1 {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $book->stock > 0 ? 'Tersedia' : 'Tidak Tersedia' }}</span> {{-- Ketersediaan Stok --}}
                        </div>

                        <div class="mb-1"> {{-- harga buku --}}
                            <p class="text-lg text-secondary">Rp. {{ number_format($book->price, 0, ',', '.') }}</p>
                        </div>

                        <div class="mb-1"> {{-- deskripsi buku --}}
                            <p class="text-sm text-secondary mt-2 leading-relaxed">{{ $book->description }}</p>
                        </div>

                        <div class="mb-1">
                            <span class="text-sm text-secondary font-medium">Category:</span> {{-- kategori buku --}}
                            <span class="text-sm text-secondary">{{ $book->category->name ?? 'Tidak ada kategori' }}</span>
                        </div>

                        <div class="mb-6 shadow-[0_4px_2px_-1px_rgba(0,0,0,0.1)]">
                            <span class="text-sm text-secondary font-medium">Tahun Terbit:</span> {{-- tahun terbit buku --}}
                            <span class="text-sm text-secondary">{{ $book->published_year }}</span>
                        </div>

                        <div class="flex gap-3">
                            <!-- Tombol Tambah ke Keranjang dan Favorit
                                 Warnanya kurang cocok, mungkin bisa disamakan menggunakan text-white -->
                            @livewire('add-to-cart-button', ['book' => $book], key('add-to-cart-button-' . $book->id))

                            <button
                                class="border border-primary text-primary px-6 py-2 rounded-lg hover:bg-primary hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
