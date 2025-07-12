{{-- resources/views/components/main-navbar.blade.php --}}
<nav class="bg-surface border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" wire:navigate class="flex items-center flex-shrink-0">
                <flux:icon.book-open class="h-6 w-6 sm:h-8 sm:w-8 text-blue-600 mr-1 sm:mr-2" />
                <span class="text-lg sm:text-xl font-bold text-secondary">Ngabaca</span>
            </a>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex items-center space-x-6 xl:space-x-8">
                <a href="{{ route('catalog') }}" wire:navigate class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">Katalog</a>
                <a href="#" class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">Bestsellers</a>
                <a href="#" class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">Buku Baru</a>
                <a href="{{ route('contact') }}" wire:navigate class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">
                    Kontak Kami
                </a>
                <a href="{{ route('about') }}" wire:navigate
                    class="text-secondary hover:text-secondary/60 font-medium whitespace-nowrap">Tentang Kami</a>
            </div>

            <!-- Right side icons -->
            <div class="flex items-center space-x-1 sm:space-x-2 text-black">
                <!-- Desktop Search Bar -->
                <div class="hidden sm:block relative text-gray-400 bg-muted rounded-lg flex items-center">
                    <input
                        class="pl-8 pr-3 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent w-32 md:w-40 lg:w-48 xl:w-56"
                        placeholder="Search" type="text" />
                    <flux:icon.magnifying-glass
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                </div>


                @auth
                    <!-- Wishlist -->
                    <a href="/whislist" class="p-1 rounded-md bg-muted block">
                        <flux:button variant="ghost" size="sm" class="cursor-pointer">
                            <flux:icon.heart class="h-4 w-4 sm:h-5 sm:w-5 text-gray-600" />
                        </flux:button>
                    </a>

                    <!-- Shopping Cart -->
                    @livewire('cart-counter')

                    <!-- Profile -->
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
                    <!-- Login/Register buttons for guests -->
                    <div class="flex items-center space-x-1 sm:space-x-2">
                        <a href="{{ route('login') }}">
                            <flux:button variant="outline" size="sm" class="text-xs sm:text-sm px-2 sm:px-3">
                                <span class="hidden xs:inline">Login</span>
                                <span class="xs:hidden">In</span>
                            </flux:button>
                        </a>
                        <a href="{{ route('register') }}">
                            <flux:button variant="primary" size="sm" class="text-xs sm:text-sm px-2 sm:px-3">
                                <span class="hidden xs:inline">Register</span>
                                <span class="xs:hidden">Up</span>
                            </flux:button>
                        </a>
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <flux:button variant="ghost" size="sm" class="lg:hidden p-2 ml-1">
                    <flux:icon.bars-3 class="h-5 w-5 sm:h-6 sm:w-6 text-gray-600" />
                </flux:button>
            </div>
        </div>

        <!-- Mobile Navigation Menu (initially hidden, toggle with mobile menu button) -->
        <div class="lg:hidden border-t border-gray-200 mt-2 pt-2 pb-3 space-y-1" x-data="{ open: false }" x-show="open">
            <a href="{{ route('catalog') }}" wire:navigate class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Katalog</a>
            <a href="#" class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Bestsellers</a>
            <a href="#" class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Buku Baru</a>
            <a href="{{ route('contact') }}" wire:navigate class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Kontak Kami</a>
            <a href="{{ route('about') }}" wire:navigate class="block px-3 py-2 text-base font-medium text-secondary hover:text-secondary/60 hover:bg-gray-50 rounded-md">Tentang Kami</a>
        </div>

        <!-- Mobile Search -->
        @unless(request()->routeIs('catalog'))
        <div class="sm:hidden pb-3 pt-2 border-t border-gray-200">
            <div class="relative">
            <flux:input placeholder="Search books..." class="w-full pl-10 pr-4 text-sm" />
            <flux:icon.magnifying-glass
                class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
            </div>
        </div>
        @endunless
    </div>
</nav>
