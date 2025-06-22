<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="relative flex size-full min-h-screen flex-col bg-white  group/design-root overflow-x-hidden" style='font-family: "Plus Jakarta Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
        <div class="flex flex-1 justify-center py-5">
            <div class="layout-content-container flex flex-col w-[512px] max-w-[512px] py-5 max-w-[960px] flex-1">
                
                <!-- Header -->
                <h2 class="text-[#0C161B] tracking-light text-[24px] font-bold leading-tight px-4 text-center pb-3 pt-5"
                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Selamat Kembali Ke Ngabaca
                </h2>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="px-4 py-3 text-center text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Login Form -->
                <form wire:submit="login">
                    <!-- Email Input -->
                    <div class="flex max-w-[480px] flex-col flex-wrap gap-4 px-4 py-3">
                        
                        <input
                            wire:model="email"
                            type="email"
                            placeholder="Email"
                            required
                            autocomplete="username"
                            class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-black focus:outline-0 focus:ring-0 border-none bg-gray-200 focus:border-none h-14 placeholder:text-[#4c7b9a] p-4 text-base font-normal leading-normal @error('email') @enderror"
                        />
                    </div>

                    <!-- Password Input -->
                    <div class="flex max-w-[480px] flex-col flex-wrap gap-4 px-4 py-3">
                        
                        <input
                                wire:model="password"
                                type="password"
                                placeholder="Password"
                                required
                                autocomplete="current-password"
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-xl text-black focus:outline-0 focus:ring-0 border-none bg-gray-200 focus:border-none h-14 placeholder:text-[#4c7b9a] p-4 text-base font-normal leading-normal @error('password') bg-red-50 @enderror"
                            />
                            @error('password')
                                <span class="text-red-600 text-sm mt-1 px-4">{{ $message }}</span>
                            @enderror
                    </div>

                    <!-- Remember Me (Optional) -->
                    <div class="flex items-center px-4 py-2">
                        <label class="flex items-center">
                            <input 
                                wire:model="remember" 
                                type="checkbox" 
                                class="rounded border-gray-300 text-[#2a9fed] shadow-sm focus:ring-[#2a9fed]"
                            >
                            <span class="ml-2 text-sm text-[#4c7b9a]">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- Login Button -->
                    <div class="flex px-4 py-3">
                        <button
                            type="submit"
                            class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 flex-1 bg-[#2a9fed] text-slate-50 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove class="truncate">Login</span>
                            <span wire:loading class="truncate">Logging in...</span>
                        </button>
                    </div>
                </form>

                <!-- Forgot Password Link (Optional) -->
                @if (Route::has('password.request'))
                    <div class="px-4 py-1 text-center">
                        <a href="{{ route('password.request') }}" 
                           wire:navigate 
                           class="text-[#4c7b9a] text-sm font-normal leading-normal underline hover:text-blue-600">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif

                <!-- Register Link -->
                @if (Route::has('register'))
                    <div class="px-4 py-3 text-center">
                        <span class="text-[#4c7b9a] text-sm font-normal leading-normal">Don't have an account? </span>
                        <a href="{{ route('register') }}" 
                           wire:navigate 
                           class="text-[#4c7b9a] text-sm font-normal leading-normal underline hover:text-blue-600">
                            Register
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>