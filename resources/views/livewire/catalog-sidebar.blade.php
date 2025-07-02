<!-- File: resources/views/livewire/catalog-sidebar.blade.php -->
<section>
    <aside class="space-y-8">
        <div class="w-full bg-white rounded-xl p-8">
            <h2 class="text-xl font-bold text-[#92400E] mb-8">Filters</h2>

            <div class="mb-6">
                <label class="block text-[#92400E] text-sm font-medium mb-2">Category</label>
                <div class="relative">
                    <select wire:model.live="selectedCategory"
                        class="w-full border border-gray-300 text-[#92400E] rounded-lg py-2 px-3 text-sm bg-white appearance-none focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l-4 4 4m0 6l-4 4-4-4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-[#92400E] text-sm font-medium mb-2">Price Range</label>
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-xs text-[#92400E] mb-1">Min Price</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#92400E] text-sm">Rp</span>
                            <input type="text" wire:model.live="minPriceFormatted" value="{{ $minPriceFormatted }}"
                                placeholder="0" id="min-price-input"
                                class="w-full border border-gray-300 text-[#92400E] rounded-lg py-2 pl-8 pr-3 text-sm bg-white focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs text-[#92400E] mb-1">Max Price</label>
                        <div class="relative">
                            <span
                                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#92400E] text-sm">Rp</span>
                            <input type="text" wire:model.live="maxPriceFormatted" placeholder="999.999.999"
                                id="max-price-input" value="{{ $maxPriceFormatted }}"
                                class="w-full border border-gray-300  rounded-lg py-2 pl-8 pr-3 text-sm bg-white focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400">
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-xs text-[#92400E] mb-2">Quick Filters</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="setPriceRange(0, 100000)"
                            class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-yellow-100 border border-gray-300 hover:border-yellow-400 rounded-lg text-[#92400E] hover:text-[#92400E] transition-colors">
                            < 100rb </button>
                                <button type="button" wire:click="setPriceRange(100000, 500000)"
                                    class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-yellow-100 border border-gray-300 hover:border-yellow-400 rounded-lg text-[#92400E] hover:text-[#92400E] transition-colors">
                                    100rb - 500rb
                                </button>
                                <button type="button" wire:click="setPriceRange(500000, 1000000)"
                                    class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-yellow-100 border border-gray-300 hover:border-yellow-400 rounded-lg text-[#92400E] hover:text-[#92400E] transition-colors">
                                    500rb - 1jt
                                </button>
                                <button type="button" wire:click="setPriceRange(1000000, 999999999)"
                                    class="px-3 py-1.5 text-xs bg-gray-100 hover:bg-yellow-100 border border-gray-300 hover:border-yellow-400 rounded-lg text-[#92400E] hover:text-[#92400E] transition-colors">
                                    > 1jt
                                </button>
                    </div>
                </div>

            </div>

            <div class="flex flex-col gap-3">
                <button wire:click="resetFilters"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-[#92400E] font-semibold py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300">
                    Reset Filters
                </button>
                <button wire:click="applyFilters"
                    class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-[#92400E] font-semibold py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400">
                    Apply Filters
                </button>
            </div>
        </div>

        <div class="w-full bg-white rounded-xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-[#92400E]">Sort By</h2>
                @if ($sortBy !== 'featured')
                    <button wire:click="resetSort" onclick="handleResetSort()"
                        class="text-xs text-gray-500 hover:text-[#92400E] underline">
                        Reset
                    </button>
                @endif
            </div>

            <div class="flex flex-col gap-2" id="sort-container">
                <label
                    class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border transition-all
                    {{ $sortBy === 'featured' ? 'border-yellow-400 ring-1 ring-yellow-400 bg-yellow-50' : 'border-gray-300 hover:border-gray-400' }}">
                    <input type="radio" name="sortBy" wire:model.live="sortBy" value="featured" id="sort-featured"
                        class="form-radio text-yellow-500 focus:ring-yellow-400 h-4 w-4 border-gray-300">
                    <span class="font-medium text-sm text-[#92400E]">Featured</span>
                </label>

                <label
                    class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border transition-all
                    {{ $sortBy === 'price_asc' ? 'border-yellow-400 ring-1 ring-yellow-400 bg-yellow-50' : 'border-gray-300 hover:border-gray-400' }}">
                    <input type="radio" name="sortBy" wire:model.live="sortBy" value="price_asc" id="sort-price-asc"
                        class="form-radio text-yellow-500 focus:ring-yellow-400 h-4 w-4 border-gray-300">
                    <span class="font-medium text-sm text-[#92400E]">Price: Low to High</span>
                </label>

                <label
                    class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border transition-all
                    {{ $sortBy === 'price_desc' ? 'border-yellow-400 ring-1 ring-yellow-400 bg-yellow-50' : 'border-gray-300 hover:border-gray-400' }}">
                    <input type="radio" name="sortBy" wire:model.live="sortBy" value="price_desc"
                        id="sort-price-desc"
                        class="form-radio text-yellow-500 focus:ring-yellow-400 h-4 w-4 border-gray-300">
                    <span class="font-medium text-sm text-[#92400E]">Price: High to Low</span>
                </label>

                <label
                    class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border transition-all
                    {{ $sortBy === 'newest' ? 'border-yellow-400 ring-1 ring-yellow-400 bg-yellow-50' : 'border-gray-300 hover:border-gray-400' }}">
                    <input type="radio" name="sortBy" wire:model.live="sortBy" value="newest" id="sort-newest"
                        class="form-radio text-yellow-500 focus:ring-yellow-400 h-4 w-4 border-gray-300">
                    <span class="font-medium text-sm text-[#92400E]">Newest</span>
                </label>
            </div>
        </div>
    </aside>

    <style>
        /* Pastikan radio button styling konsisten */
        input[type="radio"]:checked {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
        }

        /* Custom styling untuk radio button yang checked */
        input[name="sortBy"]:checked+span {
            font-weight: 600;
        }
    </style>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isUpdating = false;

        // Simple function to sync radio buttons with Livewire state
        function syncRadioButtons() {
            if (isUpdating) return;
            
            isUpdating = true;
            const sortByValue = @this.get('sortBy') || 'featured';
            
            // Only update if current checked radio doesn't match sortBy value
            const currentChecked = document.querySelector('input[name="sortBy"]:checked');
            const shouldBeChecked = document.querySelector(`input[name="sortBy"][value="${sortByValue}"]`);
            
            if (!currentChecked || currentChecked.value !== sortByValue) {
                // Clear all first
                document.querySelectorAll('input[name="sortBy"]').forEach(radio => {
                    radio.checked = false;
                });
                
                // Set the correct one
                if (shouldBeChecked) {
                    shouldBeChecked.checked = true;
                }
            }
            
            setTimeout(() => {
                isUpdating = false;
            }, 100);
        }

        // Initial sync
        setTimeout(syncRadioButtons, 100);

        // Listen for Livewire updates
        Livewire.hook('morph.updated', () => {
            setTimeout(syncRadioButtons, 50);
        });

        // Listen for navigation
        window.addEventListener('livewire:navigated', () => {
            setTimeout(syncRadioButtons, 100);
        });
    });

    // Simple reset function
    window.handleResetSort = function() {
        @this.set('sortBy', 'featured');
    }
</script>
