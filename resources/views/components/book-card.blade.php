@php
    $title = $book['title'] ?? 'Judul Buku Tidak Diketahui';
    $author = $book['author'] ?? 'Penulis Tidak Diketahui';
    $cover = $book['cover_image_url'] ?? 'https://via.placeholder.com/225x300?text=No+Image';
    $bg = $book['background_color'] ?? 'bg-gray-100';
    $slug = $book['slug'] ?? null;
    $price = $book['price'] ?? null;
    $original = $book['original_price'] ?? null;
    $discountPct = $price && $original && $original > $price ? round(100 - ($price / $original) * 100) : null;
    $rating = $book['rating'] ?? null; // 0..5 (bisa float)
    $ratingCount = $book['rating_count'] ?? null;
@endphp

<li {{ $attributes->merge(['class' => 'group list-none']) }}>
    <div
        class="relative rounded-xl overflow-hidden shadow-sm ring-1 ring-gray-200/70 dark:ring-gray-700 bg-white dark:bg-gray-900 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        {{-- Link (fallback div jika tidak ada slug) --}}
        @if ($slug)
            <a href="{{ route('book.show', $slug) }}" class="absolute inset-0 z-10"
                aria-label="Buka detail {{ $title }}"></a>
        @endif

        {{-- Rasio aspek 3:4 --}}
        <div class="relative w-full aspect-[3/4] {{ $bg }}">
            <img src="{{ $cover }}" alt="Sampul buku: {{ $title }}" loading="lazy"
                class="w-full h-full object-cover transition duration-500 ease-out group-hover:scale-[1.04] group-hover:brightness-105"
                onerror="this.src='https://via.placeholder.com/225x300?text=No+Image';" />

            {{-- Overlay gradient bawah --}}
            <div
                class="pointer-events-none absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/70 via-black/20 to-transparent">
            </div>

            {{-- Badge diskon --}}
            @if ($discountPct)
                <div class="absolute top-2 left-2">
                    <span
                        class="inline-block rounded-md bg-rose-600 text-white text-[10px] font-semibold px-2 py-1 shadow-md">
                        -{{ $discountPct }}%
                    </span>
                </div>
            @endif


            {{-- Rating chip --}}
            @if ($rating)
                <div
                    class="absolute bottom-2 left-2 flex items-center gap-1 rounded-md bg-black/65 backdrop-blur px-2 py-1 text-[11px] font-medium text-amber-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3 fill-amber-300" viewBox="0 0 24 24">
                        <path d="M12 17.3 5 21l1.9-7.4L1 8.9l7.6-.6L12 1l3.4 7.3 7.6.6-5.9 4.7L19 21z" />
                    </svg>
                    <span>{{ number_format($rating, 1) }}</span>
                    @if ($ratingCount)
                        <span class="text-[10px] text-gray-200">({{ $ratingCount }})</span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="p-3">
            <h3
                class="text-sm font-semibold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2 group-hover:text-amber-600 transition">
                {{ $title }}
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 line-clamp-1">
                {{ $author }}
            </p>

            {{-- Harga --}}
            @if ($price)
                <div class="mt-2 flex items-end gap-2">
                    <span class="text-sm font-bold text-amber-600">
                        Rp{{ number_format($price, 0, ',', '.') }}
                    </span>
                    @if ($original && $original > $price)
                        <span class="text-[11px] text-gray-400 line-through">
                            Rp{{ number_format($original, 0, ',', '.') }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Fokus visible --}}
        <div
            class="absolute inset-0 rounded-xl ring-0 ring-amber-500/0 group-focus-within:ring-2 group-focus-within:ring-amber-500/70 pointer-events-none transition">
        </div>
    </div>
</li>
