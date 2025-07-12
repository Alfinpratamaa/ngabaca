{{-- resources/views/components/main-navbar.blade.php --}}
<nav class="bg-surface border-b"> {{-- x-data="{ open: false }" DIHAPUS DARI SINI --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('home') }}" wire:navigate class="flex items-center flex-shrink-0">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 w-auto mr-2" />
                <span class="text-lg sm:text-xl font-bold text-secondary">Ngabaca</span>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center space-x-6 xl:space-x-8">
                <a href="{{ route('catalog') }}" wire:navigate
                    class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">Katalog</a>
                <a href="{{ route('catalog', ['sort' => 'bestseller']) }}" wire:navigate
                    class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">Bestsellers</a>
                <a href="{{ route('catalog', ['sort' => 'newest']) }}" wire:navigate
                    class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">Buku Baru</a>
                <a href="{{ route('contact') }}" wire:navigate
                    class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">
                    Kontak Kami
                </a>
                <a href="{{ route('about') }}" wire:navigate
                    class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">Tentang Kami
                </a>
            </div>

            <div class="flex items-center space-x-1 sm:space-x-2 text-black">
                <!-- Desktop Search Bar -->
                <div class="hidden sm:block relative text-gray-400 bg-muted rounded-lg flex items-center">
                    <form method="GET" action="{{ route('catalog') }}" class="relative">
                        <input name="search" value="{{ request('search') }}"
                            class="pl-8 pr-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent w-32 md:w-40 lg:w-48 xl:w-56"
                            placeholder="Search books..." type="text" />
                        <flux:icon.magnifying-glass
                            class="absolute left-2 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                    </form>
                </div>


                @auth
                    <a href="/whislist" class="p-1 rounded-md bg-muted block">
                        <flux:button variant="ghost" size="sm" class="cursor-pointer">
                            <flux:icon.heart class="h-4 w-4 sm:h-5 sm:w-5 text-gray-600" />
                        </flux:button>
                    </a>

                    {{-- Ini seharusnya tetap berfungsi normal karena tidak ada x-data di nav lagi --}}
                    @livewire('cart-counter')

                    <flux:dropdown position="bottom" align="end">
                        <flux:profile icon-variant="micro" circle
                            avatar="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/images/avatar.jpg') }}"
                            class="h-8 w-8 sm:h-9 sm:w-9" />
                        <flux:navmenu>
                            <flux:navmenu.item icon="user" wire:navigate href="{{ route('settings.profile') }}">Profile
                            </flux:navmenu.item>
                            @if (auth()->user()->role !== 'admin')
                                <flux:navmenu.item icon="book-open" wire:navigate href="/my-book">My book
                                </flux:navmenu.item>
                            @endif
                            @if (auth()->user()->role === 'admin')
                                <flux:navmenu.separator />
                                <flux:navmenu.item icon="shield-check" wire:navigate href="{{ route('admin.dashboard') }}">
                                    Admin Panel
                                </flux:navmenu.item>
                            @endif

                            <flux:navmenu.separator />

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:navmenu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                    class="w-full">
                                    Sign out
                                </flux:navmenu.item>
                            </form>
                        </flux:navmenu>
                    </flux:dropdown>
                @else
                    <div class="flex items-center space-x-1 sm:space-x-2">
                        <a href="{{ route('login') }}">
                            <flux:button variant="outline" size="sm" class="text-xs sm:text-sm px-2 sm:px-3">
                                Login
                            </flux:button>
                        </a>
                    </div>
                @endauth

                {{-- Tambahkan id untuk JavaScript --}}
                <flux:button variant="ghost" size="sm" class="lg:hidden p-2 ml-1" id="mobile-menu-button">
                    <flux:icon.bars-3 class="h-5 w-5 sm:h-6 sm:w-6 text-gray-600" />
                </flux:button>
            </div>
        </div>

        {{-- Ganti x-show dengan class hidden secara default --}}
        <div class="lg:hidden border-t border-gray-200 mt-2 pt-2 pb-3 space-y-1 hidden" id="mobile-menu">
            <a href="{{ route('catalog') }}" wire:navigate
                class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Katalog</a>
            <a href="#"
                class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Bestsellers</a>
            <a href="#"
                class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Buku
                Baru</a>
            <a href="{{ route('contact') }}" wire:navigate
                class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Kontak
                Kami</a>
            <a href="{{ route('about') }}" wire:navigate
                class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Tentang
                Kami</a>
        </div>

        <!-- Mobile Search -->
        @unless (request()->routeIs('catalog'))
            <div class="sm:hidden pb-3 pt-2 border-t border-gray-200" x-show="mobileMenuOpen" x-transition>
                <form method="GET" action="{{ route('catalog') }}" class="relative">
                    <input name="search" value="{{ request('search') }}" placeholder="Search books..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent" />
                    <flux:icon.magnifying-glass
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                </form>
            </div>
        @endunless
    </div>
</nav>

{{-- Tambahkan JavaScript di bagian bawah file blade, atau di file JS terpisah --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                // Toggle the 'hidden' class
                mobileMenu.classList.toggle('hidden');
            });
        }
    });
</script>
