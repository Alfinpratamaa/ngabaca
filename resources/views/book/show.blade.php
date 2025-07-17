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
                        <h1 class="text-3xl font-bold text-primary mb-2">{{ $book->title }}</h1> {{-- Judul Buku --}}

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

                            <div class="relative flex items-center max-w-[8rem]">
                                <!-- Tombol minus -->
                                <button onclick="decrement()"
                                    class="hover:bg-primary hover:text-white transition-colors border border-primary rounded-s-lg p-3 h-11">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h16"/>
                                    </svg>
                                </button>

                                <!-- Input jumlah -->
                                <input type="text" id="quantity" name="quantity" value="1"
                                    class="h-11 text-center text-sm block w-full py-2.5 border-y border-primary text-gray-900" placeholder="999" required oninput="sanitizeInput(this)">

                                <!-- Tombol plus -->
                                <button onclick="increment()"
                                    class="bg-primary border border-primary hover:bg-primary/90 transition-colors rounded-e-lg p-3 h-11">
                                    <svg class="w-3 h-3 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Tombol Tambah ke Keranjang dan Favorit -->
                            <button
                                class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                                Beli Sekarang
                            </button>

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

    \<script>
        function increment() {
        let qty = document.getElementById('quantity');
        let current = parseInt(qty.value) || 0; // kalau kosong, mulai dari 0
        qty.value = current + 1;
    }

    function decrement() {
        let qty = document.getElementById('quantity');
        let current = parseInt(qty.value) || 0;
        if (current > 1) {
            qty.value = current - 1;
        } else {
            qty.value = ''; // kembali kosong jika di bawah 1
        }
    }

    function sanitizeInput(input) {
        // Hanya izinkan angka
        input.value = input.value.replace(/[^0-9]/g, '');
        }
    </script>
</x-layouts.main>
