<!-- File: resources/views/livewire/catalog-sidebar.blade.php -->
<section>
    <aside class="space-y-8">
        <div class="w-full bg-white rounded-xl p-8">
            <h2 class="text-xl font-bold text-secondary mb-8">Filters</h2>

            <div class="mb-6">
                <label class="block text-secondary text-sm font-medium mb-2">Category</label>
                <div class="relative">
                    <select wire:model.live="selectedCategory"
                        class="w-full border border-muted text-secondary rounded-lg py-2 px-3 text-sm bg-white appearance-none focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l-4 4 4m0 6l-4 4-4-4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-secondary text-sm font-medium mb-2">Price Range</label>
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-xs text-secondary mb-1">Min Price</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary text-sm">Rp</span>
                            <input type="text" wire:model.live="minPriceFormatted" value="{{ $minPriceFormatted }}"
                                placeholder="0" id="min-price-input"
                                class="w-full border border-gray-300 text-secondary rounded-lg py-2 pl-8 pr-3 text-sm bg-white focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs text-secondary mb-1">Max Price</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-secondary text-sm">Rp</span>
                            <input type="text" wire:model.live="maxPriceFormatted" placeholder="999.999.999"
                                id="max-price-input" value="{{ $maxPriceFormatted }}"
                                class="w-full border text-secondary border-gray-300  rounded-lg py-2 pl-8 pr-3 text-sm bg-white focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary">
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-xs text-secondary mb-2">Quick Filters</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="setPriceRange(0, 100000)"
                            class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-yellow-100 border border-gray-300 hover:border-primary rounded-lg text-secondary hover:text-secondary transition-colors">
                            < 100rb </button>
                                <button type="button" wire:click="setPriceRange(100000, 500000)"
                                    class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-yellow-100 border border-gray-300 hover:border-primary rounded-lg text-secondary hover:text-secondary transition-colors">
                                    100rb - 500rb
                                </button>
                                <button type="button" wire:click="setPriceRange(500000, 1000000)"
                                    class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-yellow-100 border border-gray-300 hover:border-primary rounded-lg text-secondary hover:text-secondary transition-colors">
                                    500rb - 1jt
                                </button>
                                <button type="button" wire:click="setPriceRange(1000000, 999999999)"
                                    class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-yellow-100 border border-gray-300 hover:border-primary rounded-lg text-secondary hover:text-secondary transition-colors">
                                    > 1jt
                                </button>
                    </div>
                </div>

            </div>

            <div class="flex flex-col gap-3">
                <button wire:click="resetFilters"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-secondary font-semibold py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                    Reset Filters
                </button>
                <button wire:click="applyFilters"
                    class="flex-1 bg-primary hover:bg-yellow-500 text-secondary font-semibold py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Apply Filters
                </button>
            </div>
        </div>

        <div class="w-full bg-white rounded-xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-secondary">Sort By</h2>
                {{-- Tombol Reset menjadi lebih bersih dengan hanya wire:click --}}
                @if ($sortBy !== 'featured')
                    <button wire:click="resetSort" class="text-xs text-gray-500 hover:text-secondary underline">
                        Reset
                    </button>
                @endif
            </div>


            <flux:radio.group wire:model.live="sortBy" class="flex flex-col gap-2">

                <label for="sort-featured" class="flex items-center gap-3 p-3 rounded-lg border border-gray-300 transition-all duration-150 ease-in-out hover:border-primary cursor-pointer {{ $sortBy === 'featured' ? 'border-primary border-2 border-solid' : '' }}">
                    <flux:radio id="sort-featured" value="featured" class="" :checked="$sortBy === 'featured'" />
                    <span class="font-medium text-sm text-secondary">Featured</span>
                </label>

                <label for="sort-price-asc" class="flex items-center gap-3 p-3 rounded-lg border border-gray-300 transition-all duration-150 ease-in-out hover:border-primary cursor-pointer {{ $sortBy === 'price_asc' ? 'border-primary border-2 border-solid' : '' }}">
                    <flux:radio id="sort-price-asc" value="price_asc" :checked="$sortBy === 'price_asc'" />
                    <span class="font-medium text-sm text-secondary">Price: Low to High</span>
                </label>

                <label for="sort-price-desc" class="flex items-center gap-3 p-3 rounded-lg border border-gray-300 transition-all duration-150 ease-in-out hover:border-primary cursor-pointer {{ $sortBy === 'price_desc' ? 'border-primary border-2 border-solid' : '' }}">
                    <flux:radio id="sort-price-desc" value="price_desc" :checked="$sortBy === 'price_desc'" />
                    <span class="font-medium text-sm text-secondary">Price: High to Low</span>
                </label>

                <label for="sort-newest" class="flex items-center gap-3 p-3 rounded-lg border border-gray-300 transition-all duration-150 ease-in-out hover:border-primary cursor-pointer {{ $sortBy === 'newest' ? 'border-primary border-2 border-solid' : '' }}">
                    <flux:radio id="sort-newest" value="newest" :checked="$sortBy === 'newest'" />
                    <span class="font-medium text-sm text-secondary">Newest</span>
                </label>

            </flux:radio.group>
        </div>
    </aside>
    <style>
        /* Gaya default untuk setiap label opsi sorting */
        .sort-option-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            /* 12px */
            cursor: pointer;
            padding: 0.75rem;
            /* 12px */
            border-radius: 0.5rem;
            /* 8px */
            border: 1px solid #D1D5DB;
            /* border-gray-300 */
            transition: all 150ms ease-in-out;
        }

        .sort-option-label:hover {
            border-color: var(--color-primary);
            /* hover:border-gray-400 */
        }

        /* * KUNCI UTAMA:
     * Terapkan gaya "aktif" pada label yang di dalamnya memiliki
     * input radio yang sedang ":checked".
     */
    </style>
</section>
<script>
    // Pastikan skrip hanya berjalan setelah DOM siap
    document.addEventListener('livewire:navigated', () => {

        // Dengarkan sinyal 'sort-was-reset' yang dikirim dari server
        window.addEventListener('sort-was-reset', event => {
            // Temukan radio button 'featured' berdasarkan ID-nya
            const featuredRadio = document.getElementById('sort-featured');

            // Jika ditemukan, paksa statusnya menjadi 'checked'
            if (featuredRadio) {
                featuredRadio.checked = true;
            }
        });

    });
</script>
