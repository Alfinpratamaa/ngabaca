<!-- filepath: /home/alfin/Desktop/ngabaca/resources/views/livewire/edit-book-form.blade.php -->
<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <h1 class="text-2xl font-bold mb-4">{{ __('Edit Book') }}</h1>

    <form wire:submit="save" class="space-y-6" enctype="multipart/form-data">
        <!-- Basic Information Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Basic Information') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field class="w-full">
                    <flux:label>{{ __('Title') }}</flux:label>
                    <flux:input wire:model="title" type="text" class="w-full" required placeholder="Title of book"
                        value="{{ $title }}" />
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </flux:field>

                <flux:field class="w-full">
                    <flux:label>{{ __('Author') }}</flux:label>
                    <flux:input wire:model="author" type="text" class="w-full" required placeholder="Author of book"
                        value="{{ $author }}" />
                    @error('author')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </flux:field>

                <flux:field class="w-full">
                    <flux:label>{{ __('Published Year') }}</flux:label>
                    <flux:input wire:model="published_year" type="number" class="w-full" required
                        placeholder="Year published" value="{{ $published_year }}" />
                    @error('published_year')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </flux:field>

                <flux:field class="w-full">
                    <flux:label>{{ __('Category') }}</flux:label>
                    <flux:select wire:model="category_id" class="w-full" required>
                        <option value="" disabled>Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" wire:key="{{ $category->id }}"
                                {{ $category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </flux:select>
                    @error('category_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </flux:field>
            </div>

            <flux:field class="w-full mt-4">
                <flux:label>{{ __('Description') }} <span class="text-gray-500 text-sm">({{ __('Optional') }})</span>
                </flux:label>
                <flux:textarea wire:model="description" class="w-full" rows="4" placeholder="Book description">
                    {{ $description }}</flux:textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </flux:field>
        </div>

        <!-- Pricing & Stock Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Pricing & Stock') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field class="w-full">
                    <flux:label>{{ __('Price') }}</flux:label>
                    <flux:input wire:model="price" type="number" step="0.01" class="w-full" required
                        placeholder="0.00" value="{{ $price }}" />
                    @error('price')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </flux:field>

                <flux:field class="w-full">
                    <flux:label>{{ __('Stock') }}</flux:label>
                    <flux:input wire:model="stock" type="number" class="w-full" placeholder="Stock quantity"
                        value="{{ $stock }}" />
                    @error('stock')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </flux:field>
            </div>
        </div>

        <!-- Media Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Media') }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Show Current Cover Image if exists -->
                @if ($current_cover_image && !$cover_image)
                    <div class="mb-4">
                        <flux:label>{{ __('Current Cover Image') }}</flux:label>
                        <div class="mt-2 p-4 border rounded-lg bg-gray-50 max-h-[350px] overflow-hidden">
                            <div class="flex flex-col items-center justify-center w-full text-center">
                                <img src="{{ $current_cover_image }}" alt="Current cover"
                                    class="w-32 h-48 object-cover rounded-lg mb-3">
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-3">{{ __('Current cover image') }}</p>
                                    <button type="button" wire:click="confirmDeleteCoverImage"
                                        class="inline-flex cursor-pointer items-center px-3 py-1 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-lg">
                                        <flux:icon.trash class="w-4 h-4 mr-2" />
                                        {{ __('Delete Current Image') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Cover Image Upload -->
                    <flux:field class="w-full">
                        <flux:label>{{ __('Cover Image') }} <span
                                class="text-gray-500 text-sm">({{ __('Optional - Leave empty to keep current') }})</span>
                        </flux:label>

                        <!-- Custom Upload Area with Bento Style -->
                        <div class="relative">
                            <!-- Hidden File Input -->
                            <input type="file" wire:model="cover_image" accept="image/*" id="cover-image-input"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />

                            <!-- Custom Upload UI -->
                            <div
                                class="relative min-h-[160px] border-2 border-dashed border-gray-300 rounded-xl bg-gradient-to-br from-gray-50 to-white hover:border-purple-400 hover:bg-gradient-to-br hover:from-purple-50 hover:to-white transition-all duration-300 ease-in-out group">

                                <!-- Upload Icon & Text (Default State) -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center p-6"
                                    wire:loading.remove wire:target="cover_image"
                                    style="display: {{ $cover_image ? 'none' : 'flex' }}">
                                    <div
                                        class="w-16 h-16 mb-4 rounded-2xl bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                        {{ __('Upload New Cover Image') }}
                                    </h4>
                                    <p class="text-sm text-gray-600 text-center mb-1">
                                        {{ __('Drag and drop your image here, or click to browse') }}</p>
                                    <p class="text-xs text-gray-500">{{ __('Supports JPG, PNG, WebP images') }}</p>
                                </div>

                                <!-- Loading State -->
                                <div wire:loading wire:target="cover_image"
                                    class="absolute inset-0 flex flex-col items-center justify-center p-6 bg-white bg-opacity-95">
                                    <div
                                        class="w-16 h-16 mb-4 rounded-2xl bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                                        <svg class="animate-spin w-8 h-8 text-purple-600" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Uploading...') }}</h4>
                                    <div class="w-48 bg-gray-200 rounded-full h-2 mb-2">
                                        <div
                                            class="bg-gradient-to-r from-purple-500 to-pink-600 h-2 rounded-full animate-pulse w-full">
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ __('Please wait while we process your image') }}
                                    </p>
                                </div>

                                <!-- Success State -->
                                @if ($cover_image)
                                    <div
                                        class="absolute inset-0 flex items-center justify-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 border-purple-300">
                                        <div class="text-center w-full max-w-sm">
                                            <div
                                                class="w-16 h-16 mb-4 mx-auto rounded-2xl bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-purple-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <h4 class="text-lg font-semibold text-gray-900 mb-3">
                                                {{ __('New Image Selected') }}</h4>

                                            <!-- File Info -->
                                            <div
                                                class="bg-white rounded-lg p-3 shadow-sm border border-purple-200 max-w-xs mx-auto mb-3">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-5 h-5 text-purple-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $cover_image->getClientOriginalName() }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ number_format($cover_image->getSize() / 1024, 1) }} KB
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Remove Button -->
                                            <button type="button" wire:click="$set('cover_image', null)"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                {{ __('Remove') }}
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <flux:description class="mt-2">
                            {{ __('Upload new cover image to replace current one (JPG, PNG, WebP) - Maximum size: 10MB') }}
                        </flux:description>
                        @error('cover_image')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </flux:field>

                @endif




                <!-- Book File Upload -->
                <div class="mb-4">
                    <flux:label>{{ __('Book File') }} 
                        <span class="text-gray-500 text-sm">
                            ({{ __('Optional - Leave empty to keep current') }})
                        </span>
                    </flux:label>

                    <!-- Show Current File Info if exists -->
                    @if ($current_book_file && !$book_file)
                        <div class="mt-2 p-4 border rounded-lg bg-gray-50 min-h-[285px] overflow-hidden">
                            <div class="flex flex-col items-center justify-center w-full text-center">
                                <img src="{{ asset('assets/images/book.png') }}" alt="Current cover"
                                    class="w-32 h-48 object-cover rounded-lg mb-3">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ __('Current file uploaded') }}</p>
                                    <p class="text-xs text-gray-600">{{ basename($current_book_file) }}</p>
                                </div>
                            </div>
                            <div class="mt-3 flex justify-center">
                                <button type="button" wire:click="confirmDeleteBookFile"
                                    class="inline-flex cursor-pointer items-center px-3 py-1 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-lg">
                                    <flux:icon.trash class="w-4 h-4 mr-2" />
                                    {{ __('Delete Current Book') }}
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="relative {{ $private_file_path ? 'pointer-events-none opacity-50' : '' }}">
                            <!-- Hidden File Input -->
                            <input type="file" wire:model="book_file" accept=".pdf,.epub,.mobi"
                                id="book-file-input"
                                class="absolute inset-0 w-full h-full opacity-0 z-10 {{ $private_file_path ? 'cursor-not-allowed' : 'cursor-pointer' }}"
                                {{ $private_file_path ? 'disabled' : '' }} />

                            <!-- Custom Upload UI -->
                            <div
                                class="relative min-h-[160px] border-2 border-dashed rounded-xl bg-gradient-to-br transition-all duration-300 ease-in-out group
                                {{ $private_file_path
                                ? 'border-gray-200 bg-gray-50 cursor-not-allowed'
                                : 'border-gray-300 from-gray-50 to-white hover:border-blue-400 hover:bg-gradient-to-br hover:from-blue-50 hover:to-white' }}">

                                <!-- Disabled Overlay -->
                                @if ($private_file_path)
                                    <div
                                        class="absolute inset-0 bg-gray-100 bg-opacity-75 rounded-xl flex items-center justify-center z-5">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                                </path>
                                            </svg>
                                            <p class="text-sm text-gray-500 font-medium">{{ __('Disabled') }}</p>
                                            <p class="text-xs text-gray-400">{{ __('URL input is active') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Upload Icon & Text (Default State) -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-6"
                                wire:loading.remove wire:target="book_file"
                                style="display: {{ $book_file ? 'none' : 'flex' }}">
                                <div
                                    class="w-16 h-16 mb-4 rounded-2xl bg-gradient-to-br flex items-center justify-center transition-transform duration-300
                                    {{ $private_file_path ? 'from-gray-100 to-gray-200' : 'from-blue-100 to-indigo-100 group-hover:scale-110' }}">
                                    <svg class="w-8 h-8 {{ $private_file_path ? 'text-gray-400' : 'text-blue-600' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <h4
                                    class="text-lg font-semibold mb-2 {{ $private_file_path ? 'text-gray-400' : 'text-gray-900' }}">
                                    {{ __('Upload New Book File') }}
                                </h4>
                                <p
                                    class="text-sm text-center mb-1 {{ $private_file_path ? 'text-gray-400' : 'text-gray-600' }}">
                                    {{ __('Drag and drop your file here, or click to browse') }}
                                </p>
                                <p class="text-xs {{ $private_file_path ? 'text-gray-300' : 'text-gray-500' }}">
                                    {{ __('Supports PDF, EPUB, MOBI files') }}
                                </p>
                            </div>

                            <!-- Loading State -->
                            <div wire:loading wire:target="book_file"
                                class="absolute inset-0 flex flex-col items-center justify-center p-6 bg-white bg-opacity-95">
                                <div
                                    class="w-16 h-16 mb-4 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                    <svg class="animate-spin w-8 h-8 text-blue-600" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ __('Uploading...') }}</h4>
                                <div class="w-48 bg-gray-200 rounded-full h-2 mb-2">
                                    <div
                                        class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full animate-pulse w-full">
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600">
                                    {{ __('Please wait while we process your file') }}</p>
                            </div>

                            <!-- Success State -->
                            @if ($book_file)
                                <div
                                    class="absolute inset-0 flex items-center justify-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border-blue-300">
                                    <div class="text-center w-full max-w-sm">
                                        <div
                                            class="w-16 h-16 mb-4 mx-auto rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-blue-600" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3">
                                            {{ __('New File Selected') }}</h4>

                                        <!-- File Info -->
                                        <div
                                            class="bg-white rounded-lg p-3 shadow-sm border border-blue-200 max-w-xs mx-auto mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $book_file->getClientOriginalName() }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ number_format($book_file->getSize() / 1024 / 1024, 2) }}
                                                        MB</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Remove Button -->
                                        <button type="button" wire:click="$set('book_file', null)"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            {{ __('Remove File') }}
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @error('book_file')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    @endif
                </div>
            </div>

            <!-- Alternative URL Input -->
            <div class="{{ $book_file || $private_file_path ? 'hidden' : 'flex'}} mt-4">
                <flux:field class="w-full {{ $book_file ? 'opacity-50 pointer-events-none' : '' }}">
                    <flux:label>{{ __('Or Book File URL') }} <span
                            class="text-gray-500 text-sm">({{ __('Alternative to file upload') }})</span></flux:label>

                    <div class="relative">
                        <flux:input type="url" wire:model="private_file_path"
                            class="w-full {{ $book_file || $private_file_path ? 'cursor-not-allowed bg-gray-100 text-gray-400' : '' }}"
                            placeholder="https://secure-storage.example.com/book-file"
                            value="{{ $private_file_path }}" />

                        @if ($book_file)
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <flux:description class="{{ $book_file ? 'text-gray-400' : '' }}">
                        {{ $book_file ? __('Disabled - File upload is active') : __('Update secure URL to book file') }}
                    </flux:description>

                    @error('private_file_path')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </flux:field>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex w-full mt-5 justify-between">
            <flux:button :href="route('admin.book.index')" variant="outline" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>

            <flux:button type="submit" variant="primary" wire:loading.attr="disabled"
                class="px-8 py-2 bg-blue-500 hover:bg-blue-600 text-white disabled:opacity-50">
                <span wire:loading.remove wire:target="save">{{ __('Update Book') }}</span>
                <span wire:loading wire:target="save">{{ __('Updating...') }}</span>
            </flux:button>
        </div>
    
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let loadingSwal = null;
    let hasUnsavedChanges = false;
    let listenersInitialized = false;

    function initializeListeners() {
        if (listenersInitialized) return;
        
        // Track if there are unsaved changes
        function trackChanges() {
            hasUnsavedChanges = true;
        }

        // Add event listeners to form inputs to track changes
        document.querySelectorAll('input, textarea, select').forEach(element => {
            element.addEventListener('input', trackChanges);
            element.addEventListener('change', trackChanges);
        });

        // Handle cancel with confirmation if there are unsaved changes
        window.handleCancelEdit = function() {
            // Check if cover image or book file was deleted but update wasn't saved
            const coverImageDeleted = {{ $cover_image_deleted ? 'true' : 'false' }};
            const bookFileDeleted = {{ $book_file_deleted ? 'true' : 'false' }};

            if (coverImageDeleted || bookFileDeleted || hasUnsavedChanges) {
                Swal.fire({
                    title: 'Unsaved Changes',
                    text: 'You have unsaved changes. Are you sure you want to leave without saving?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, discard changes',
                    cancelButtonText: 'Stay and continue editing'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If any files were deleted, restore them before leaving
                        if (coverImageDeleted || bookFileDeleted) {
                            // Refresh page to restore original state
                            window.location.reload();
                        } else {
                            // Just go back to index
                            window.location.href = '{{ route('admin.book.index') }}';
                        }
                    }
                });
            } else {
                // No changes, safe to leave
                window.location.href = '{{ route('admin.book.index') }}';
            }
        };

        // Prevent accidental page reload/close when there are unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        listenersInitialized = true;
    }

    // Initialize listeners immediately
    initializeListeners();

    // Also initialize when Livewire loads
    document.addEventListener('livewire:initialized', function() {
        initializeListeners();

        // Listen for specific book updating event
        Livewire.on('book-updating', () => {
            if (loadingSwal) {
                loadingSwal.close();
            }

            loadingSwal = Swal.fire({
                title: 'Updating Book...',
                text: 'Please wait while we update your book',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                showCancelButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        // Listen for book updated event
        Livewire.on('book-updated', () => {
            if (loadingSwal) {
                loadingSwal.close();
            }

            hasUnsavedChanges = false; // Reset unsaved changes flag

            Swal.fire({
                title: 'Success!',
                text: 'Book updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#22c55e'
            });
        });

        // Listen for book update error event
        Livewire.on('book-update-error', (data) => {
            if (loadingSwal) {
                loadingSwal.close();
            }

            Swal.fire({
                title: 'Error!',
                text: data[0].message || 'Failed to update book',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        });

        // Listen for validation stops
        Livewire.on('book-update-stopped', () => {
            if (loadingSwal) {
                loadingSwal.close();
            }
        });

        // COVER IMAGE EVENTS
        // Listen for delete cover image confirmation request
        Livewire.on('confirm-delete-cover-image', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will permanently delete the current cover image from the database and storage. This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Track that we have changes
                    hasUnsavedChanges = true;
                    // Dispatch delete event back to Livewire
                    Livewire.dispatch('delete-cover-image');
                }
            });
        });

        // Listen for cover image deleted event
        Livewire.on('cover-image-deleted', () => {
            Swal.fire({
                title: 'Deleted!',
                text: 'Cover image has been deleted successfully',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });

        // Listen for image delete error event
        Livewire.on('image-delete-error', (data) => {
            Swal.fire({
                title: 'Error!',
                text: data[0].message || 'Failed to delete image',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        });

        // BOOK FILE EVENTS
        // Listen for delete book file confirmation request
        Livewire.on('confirm-delete-book-file', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will permanently delete the current book file from the database and storage. This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Track that we have changes
                    hasUnsavedChanges = true;
                    // Dispatch delete event back to Livewire
                    Livewire.dispatch('delete-book-file');
                }
            });
        });

        // Listen for book file processing
        Livewire.on('book-file-processing', () => {
            if (loadingSwal) {
                loadingSwal.close();
            }

            loadingSwal = Swal.fire({
                title: 'Deleting Book File...',
                text: 'Please wait while we delete the book file',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                showCancelButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });

        // Listen for book file deleted event
        Livewire.on('book-file-deleted', () => {
            if (loadingSwal) {
                loadingSwal.close();
            }

            Swal.fire({
                title: 'Deleted!',
                text: 'Book file has been deleted successfully',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        });

        // Listen for book file delete error event
        Livewire.on('file-delete-error', (data) => {
            if (loadingSwal) {
                loadingSwal.close();
            }

            Swal.fire({
                title: 'Error!',
                text: data[0].message || 'Failed to delete book file',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        });
    });
});
</script>
