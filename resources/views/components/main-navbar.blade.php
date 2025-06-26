{{-- resources/views/components/main-navbar.blade.php --}}
<nav class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between gap-5 items-center h-16">
            <!-- Logo -->
            <a href="{{ route('home') }}" wire:navigate class="flex items-center">
                <flux:icon.book-open class="h-8 w-8 text-blue-600 mr-2" />
                <span class="text-xl font-bold text-gray-900">Ngabaca</span>
            </a>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Katalog</a>
                <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Kategori</a>
                <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Penulis</a>
                <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Buku Baru</a>
                <a href="#" class="text-gray-700 hover:text-gray-900 font-medium">Bestsellers</a>
            </div>

            <!-- Right side icons -->
            <div class="flex items-center space-x-2 text-black">
                <!-- Search Bar -->
                <div class="relative text-gray-400 bg-[#F0F2F5] rounded-lg flex items-center w-64">
                    <input
                        class="pl-9 pr-4 py-2 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent w-36 sm:w-48 md:w-56 lg:w-64"
                        placeholder="Search" type="text" />
                    <flux:icon.magnifying-glass
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                </div>

                @auth
                    <!-- Wishlist -->
                    <a href="/whislist" class="p-1 rounded-md bg-[#F0F2F5]">
                        <flux:button variant="ghost" size="sm" class=" cursor-pointer">
                            <flux:icon.heart class="h-5 w-5 text-gray-600" />
                        </flux:button>
                    </a>

                    <!-- Shopping Cart -->
                    <a href="/cart" class="p-1 rounded-md bg-[#F0F2F5]">
                        <flux:button variant="ghost" size="sm" class=" cursor-pointer">
                            <flux:icon.shopping-bag class="h-5 w-5 text-gray-600" />
                        </flux:button>
                    </a>

                    <!-- Profile -->
                    <flux:dropdown position="bottom" align="end" >
                        <flux:profile
                            icon-variant="micro"
                            circle
                            avatar="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/images/avatar.jpg') }}" />
                        <flux:navmenu>
                            <flux:navmenu.item icon="user" href="{{ route('settings.profile') }}">Profile
                            </flux:navmenu.item>
                            @if (auth()->user()->role !== 'admin')
                                <flux:navmenu.item icon="book-open" href="/my-book">My book</flux:navmenu.item>
                            @endif
                            @if (auth()->user()->role === 'admin')
                                <flux:navmenu.separator />
                                <flux:navmenu.item icon="shield-check" href="/admin/dashboard">Admin Panel
                                </flux:navmenu.item>
                            @endif
                            <flux:navmenu.item icon="cog-6-tooth" href="/settings">Settings</flux:navmenu.item>


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
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}">
                            <flux:button variant="outline" size="sm">
                                Login
                            </flux:button>
                        </a>
                        <a href="{{ route('register') }}">
                            <flux:button variant="primary" size="sm">
                                Register
                            </flux:button>
                        </a>
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <flux:button variant="ghost" size="sm" class="md:hidden p-2">
                    <flux:icon.bars-3 class="h-6 w-6 text-gray-600" />
                </flux:button>
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="lg:hidden pb-4">
            <div class="relative">
                <flux:input placeholder="Search for books, authors, or genres" class="w-full pl-10 pr-4" />
                <flux:icon.magnifying-glass
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
            </div>
        </div>
    </div>
</nav>
