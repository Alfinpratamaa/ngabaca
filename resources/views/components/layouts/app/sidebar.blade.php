{{-- components/layouts/app/sidebar.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    {{-- @fluxAppearance --}}


</head>

<body class="min-h-screen bg-white text-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-white text-zinc-800">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('admin.dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse"
            wire:navigate>
            <h1 class="font-bold text-xl text-zinc-800">Ngabaca Admin</h1>
        </a>

        <flux:navlist variant="outline" class="text-zinc-800">
            <flux:navlist.group :heading="__('Management')" class="grid">
                <flux:navlist.item icon="home" :href="route('admin.dashboard')"
                    :current="request()->routeIs('admin.dashboard')" class="text-zinc-800 hover:text-zinc-900">
                    Dashboard</flux:navlist.item>
                <flux:navlist.item icon="tag" :href="route('admin.category.index')"
                    :current="request()->routeIs('admin.category.*')" class="text-zinc-800 hover:text-zinc-900">
                    Category</flux:navlist.item>
                <flux:navlist.item icon="book-open" :href="route('admin.book.index')"
                    :current="request()->routeIs('admin.book.*')" class="text-zinc-800 hover:text-zinc-900">
                    Book</flux:navlist.item>
                <flux:navlist.item icon="user-group" :href="route('admin.user.index')"
                    :current="request()->routeIs('admin.user.*')" class="text-zinc-800 hover:text-zinc-900">
                    User</flux:navlist.item>
                <flux:navlist.item icon="shopping-bag" :href="route('admin.order.index')"
                    :current="request()->routeIs('admin.order.*')" class="text-zinc-800 hover:text-zinc-900">
                    Order</flux:navlist.item>
                <flux:navlist.item icon="credit-card" :href="route('admin.payment.index')"
                    :current="request()->routeIs('admin.payment.*')" class="text-zinc-800 hover:text-zinc-900">
                    Payment</flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        Settings
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        Log Out
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon:trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                        Settings</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts

    <script>
        // Mendengarkan event 'show-alert' yang dikirim dari Livewire
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-alert', (data) => {
                Swal.fire({
                    icon: data[0].type, // 'success', 'error', 'warning', 'info'
                    title: data[0].type === 'success' ? 'Berhasil!' : 'Terjadi Kesalahan!',
                    text: data[0].message,
                    showConfirmButton: false,
                    timer: 3000 // Otomatis menutup setelah 3 detik
                });
            });
        });
    </script>
</body>

</html>
