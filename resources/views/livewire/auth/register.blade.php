<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.main')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->dispatch('$refresh');

        // Redirect dengan delay kecil
        $user = Auth::user();
        if ($user->role === 'admin') {
            $this->redirect(route('admin.dashboard'));
        } else {
            $this->redirect(route('home'));
        }
    }
}; ?>

<div class="relative flex size-full min-h-screen flex-col mt-8 bg-white group/design-root overflow-x-hidden"
    style='font-family: "Plus Jakarta Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
        <div class="flex flex-1 justify-center">
            <div class="layout-content-container flex flex-col w-full max-w-md px-4">

                <!-- Header -->
                <h2
                    class="text-[#0C161B] tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-8 pt-5 font-sans">
                    Buat Akun
                </h2>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="px-4 py-3 text-center text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Registration Form -->
                <form wire:submit="register">
                    <!-- Email Input -->
                    <div class="flex flex-col gap-2 py-3">
                        <flux:input wire:model="name" label="Nama Lengkap"
                            class="bg-gray-200 rounded-md text-slate-900 [&_label]:text-slate-700" type="text"
                            required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
                        <flux:input wire:model="email" label="Email Address"
                            class="bg-gray-200 rounded-md text-slate-900 [&_label]:text-slate-700" type="email"
                            required autocomplete="email" placeholder="email@example.com" />
                        <flux:input wire:model="password" :label="__('Password')" type="password" placeholder="Password"
                            required viewable autocomplete="new-password"
                            class="bg-gray-200 rounded-md [&_label]:text-slate-700" />
                        <flux:input wire:model="password_confirmation" label="Konfirmasi Password" type="password"
                            placeholder="Konfirmasi Password" required viewable autocomplete="new-password"
                            class="bg-gray-200 rounded-md [&_label]:text-slate-700" />
                    </div>

                    <!-- Register Button -->
                    <div class="flex px-4 py-6">
                        <button type="submit"
                            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-12 px-4 flex-1 bg-[#2a9fed] text-white text-base font-semibold leading-normal tracking-[0.015em] hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove class="truncate">Daftar</span>
                            <span wire:loading class="truncate">Mendaftar...</span>
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="px-4 py-3 text-center">
                    <span class="text-[#4c7b9a] text-sm font-normal leading-normal">Sudah Memiliki Akun? </span>
                    <a href="{{ route('login') }}" wire:navigate
                        class="text-[#4c7b9a] text-sm font-normal leading-normal underline hover:text-blue-600">
                        Masuk
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
