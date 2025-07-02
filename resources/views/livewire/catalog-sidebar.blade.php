<!-- File: resources/views/livewire/catalog-sidebar.blade.php -->
<div>
    <!-- Aset untuk noUiSlider harus ada di layout utama agar slider muncul -->
    <aside class="space-y-8">
        <!-- Filters Section -->
        <div class="w-full bg-white rounded-xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-8">Filters</h2>
            
            <!-- Category Filter -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2">Category</label>
                <div class="relative">
                     <select wire:model.live="selectedCategory" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm bg-white appearance-none focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Price Filter -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-4">Price</label>
                <div x-data="{
                        min: @entangle('minPrice').live,
                        max: @entangle('maxPrice').live,
                        slider: null,
                        init() {
                            if (typeof noUiSlider === 'undefined') return;
                            const self = this;
                            self.slider = noUiSlider.create(self.$refs.slider, {
                                start: [self.min, self.max],
                                connect: true,
                                step: 1000,
                                range: { 'min': 0, 'max': 500000 },
                                format: { to: v => Math.round(v), from: v => Math.round(v) }
                            });
                            self.slider.on('update', (values) => {
                                self.min = Number(values[0]);
                                self.max = Number(values[1]);
                            });
                        }
                    }" class="w-full pt-2">
                    <div x-ref="slider"></div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span x-text="'Rp' + new Intl.NumberFormat('id-ID').format(min)"></span>
                        <span x-text="'Rp' + new Intl.NumberFormat('id-ID').format(max)"></span>
                    </div>
                </div>
            </div>

            <!-- Rating Filter -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2">Rating</label>
                 <div class="relative">
                    <select wire:model.live="selectedRating" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm bg-white appearance-none focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400">
                        <option value="">Select Rating</option>
                        <option value="5">5 Stars</option>
                        <option value="4">4+ Stars</option>
                        <option value="3">3+ Stars</option>
                        <option value="2">2+ Stars</option>
                        <option value="1">1+ Stars</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <button wire:click="applyFilters" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold py-2.5 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400">Apply Filters</button>
        </div>

        <!-- Sort By Section -->
        <div class="w-full bg-white rounded-xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Sort By</h2>
            <div class="flex flex-col gap-2">
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-300 has-[:checked]:border-yellow-400 has-[:checked]:ring-1 has-[:checked]:ring-yellow-400">
                    <input type="radio" wire:model.live="sortBy" value="featured" class="form-radio text-yellow-500 focus:ring-yellow-400 h-4 w-4 border-gray-300">
                    <span class="font-medium text-sm text-gray-700">Featured</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-300 has-[:checked]:border-yellow-400 has-[:checked]:ring-1 has-[:checked]:ring-yellow-400">
                    <input type="radio" wire:model.live="sortBy" value="price_asc" class="form-radio text-yellow-500 focus:ring-yellow-400 h-4 w-4 border-gray-300">
                    <span class="font-medium text-sm text-gray-700">Price: Low to High</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-300 has-[:checked]:border-yellow-400 has-[:checked]:ring-1 has-[:checked]:ring-yellow-400">
                    <input type="radio" wire:model.live="sortBy" value="price_desc" class="form-radio text-yellow-500 focus:ring-yellow-400 h-4 w-4 border-gray-300">
                    <span class="font-medium text-sm text-gray-700">Price: High to Low</span>
                </label>
                 <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg border border-gray-300 has-[:checked]:border-yellow-400 has-[:checked]:ring-1 has-[:checked]:ring-yellow-400">
                    <input type="radio" wire:model.live="sortBy" value="newest" class="form-radio text-yellow-500 focus:ring-yellow-400 h-4 w-4 border-gray-300">
                    <span class="font-medium text-sm text-gray-700">Newest</span>
                </label>
            </div>
        </div>
    </aside>

    <!-- Style untuk menyesuaikan noUiSlider -->
    <style>
        .noUi-target {
            background: #F3F4F6; /* gray-200 */
            border-radius: 9999px;
            border: 1px solid #E5E7EB; /* gray-200 */
            height: 12px; /* Increased from 6px to 12px */
            box-shadow: none;
        }
        .noUi-connect {
            background: #FBBF24; /* yellow-400 */
        }
        .noUi-handle {
            border: 2px solid #ffffff;
            border-radius: 9999px;
            background: #FBBF24; /* yellow-400 */
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            cursor: pointer;
            height: 28px; /* Increased from 16px to 28px */
            width: 28px;  /* Increased from 16px to 28px */
            top: -9px;    /* Adjusted for new size */
            right: -14px; /* Adjusted for new size */
        }
        .noUi-handle:after,
        .noUi-handle:before {
            display: none;
        }
    </style>
</div>
