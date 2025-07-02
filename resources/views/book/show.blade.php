<x-layouts.main title="{{ $book->title }}" description="{{ $book->description }}">
    <div class="min-h-screen bg-surface flex justify-center">
        <div class="max-w-4xl w-full p-6">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/3 p-6">
                        <img src="{{ $book->cover_image ?? '/images/default-book-cover.jpg' }}" alt="{{ $book->title }}"
                            class="w-full h-auto rounded-lg shadow-md">
                    </div>
                    <div class="md:w-2/3 p-6">
                        <h1 class="text-3xl font-bold text-primary mb-4">{{ $book->title }}</h1>
                        <div class="mb-4">
                            <span class="text-sm text-secondary font-medium">Penulis:</span>
                            <p class="text-lg text-secondary">{{ $book->author }}</p>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm text-secondary font-medium">Category:</span>
                            <p class="text-secondary">{{ $book->category->name ?? 'Tidak ada kategori' }}</p>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm text-secondary font-medium">Tahun Terbit:</span>
                            <p class="text-secondary">{{ $book->published_year }}</p>
                        </div>
                        <div class="mb-6">
                            <span class="text-sm text-secondary font-medium">Deskripsi:</span>
                            <p class="text-secondary mt-2 leading-relaxed">{{ $book->description }}</p>
                        </div>
                        <div class="flex gap-3">
                            <button
                                class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition-colors">
                                Baca Sekarang
                            </button>
                            <button
                                class="border border-primary text-primary px-6 py-2 rounded-lg hover:bg-primary hover:text-white transition-colors">
                                Tambah ke Favorit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.main>
