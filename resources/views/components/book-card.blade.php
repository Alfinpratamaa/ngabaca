<li {{ $attributes->merge(['class' => '']) }}>
    {{-- Kontainer dengan padding-top untuk menjaga rasio aspek 3:4 (tinggi = 4/3 * lebar) --}}
    <div class="relative rounded-lg p-2 flex justify-center {{ $book['background_color'] ?? 'bg-gray-100' }}">
        {{-- Ini adalah elemen spacer yang menciptakan rasio aspek --}}
        <div class="block w-full" style="padding-top: calc(4 / 3 * 100%);"></div>
        
        {{-- Gambar buku yang mengisi penuh kontainer dengan rasio aspek tetap --}}
        <img
            alt="{{ $book['title'] ?? 'Book cover' }}"
            class="absolute inset-0 w-full h-full object-cover rounded-md shadow-lg"
            src="{{ $book['cover_image'] ?? 'https://via.placeholder.com/225x300?text=No+Image' }}"
        />
    </div>
    <h3 class="mt-3 text-sm font-semibold text-gray-900">
        {{ $book['title'] ?? 'Judul Buku Tidak Diketahui' }}
    </h3>
    <p class="text-xs text-gray-500 mt-1">
        {{ $book['author'] ?? 'Penulis Tidak Diketahui' }}
    </p>
</li>