<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
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

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Plus Jakarta Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
        <div class="flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col w-[512px] max-w-[512px] py-5 max-w-[960px] flex-1">
                
                <!-- Header -->
                <h2 class="text-[#0C161B] tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-8 pt-5 font-sans">
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
                    <!-- Name Input -->
                    <div class="flex max-w-[480px] flex-col flex-wrap gap-4 px-4 py-3">
                        <input
                            wire:model="name"
                            type="text"
                            placeholder="Name"
                            required
                            autofocus
                            autocomplete="name"
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-black focus:outline-0 focus:ring-0 border-none bg-gray-200 focus:border-none h-14 placeholder:text-[#4c7b9a] p-4 text-base font-normal leading-normal @error('name') bg-red-50 @enderror"
                        />
                        @error('name')
                            <span class="text-red-600 text-sm mt-1 px-4">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="flex max-w-[480px] flex-col flex-wrap gap-4 px-4 py-3">
                        <input
                            wire:model="email"
                            type="email"
                            placeholder="Email"
                            required
                            autocomplete="email"
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-black focus:outline-0 focus:ring-0 border-none bg-gray-200 focus:border-none h-14 placeholder:text-[#4c7b9a] p-4 text-base font-normal leading-normal @error('email') bg-red-50 @enderror"
                        />
                        @error('email')
                            <span class="text-red-600 text-sm mt-1 px-4">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="flex max-w-[480px] flex-col flex-wrap gap-4 px-4 py-3">
                        <input
                            wire:model="password"
                            type="password"
                            placeholder="Password"
                            required
                            autocomplete="new-password"
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-black focus:outline-0 focus:ring-0 border-none bg-gray-200 focus:border-none h-14 placeholder:text-[#4c7b9a] p-4 text-base font-normal leading-normal @error('password') bg-red-50 @enderror"
                        />
                        @error('password')
                            <span class="text-red-600 text-sm mt-1 px-4">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password Input -->
                    <div class="flex max-w-[480px] flex-col flex-wrap gap-4 px-4 py-3">
                        <input
                            wire:model="password_confirmation"
                            type="password"
                            placeholder="Confirm Password"
                            required
                            autocomplete="new-password"
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-black focus:outline-0 focus:ring-0 border-none bg-gray-200 focus:border-none h-14 placeholder:text-[#4c7b9a] p-4 text-base font-normal leading-normal"
                        />
                    </div>

                    <!-- Register Button -->
                    <div class="flex px-4 py-6">
                        <button
                            type="submit"
                            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-12 px-4 flex-1 bg-[#2a9fed] text-white text-base font-semibold leading-normal tracking-[0.015em] hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove class="truncate">Daftar</span>
                            <span wire:loading class="truncate">Mendaftar...</span>
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <div class="px-4 py-3 text-center">
                    <span class="text-[#4c7b9a] text-sm font-normal leading-normal">Sudah Memiliki Akun? </span>
                    <a href="{{ route('login') }}" 
                       wire:navigate 
                       class="text-[#4c7b9a] text-sm font-normal leading-normal underline hover:text-blue-600">
                        Masuk
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>