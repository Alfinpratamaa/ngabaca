<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[
    Layout('components.layouts.main', [
        'title' => 'Register',
        'description' => 'Buat akun baru untuk mulai menjelajah koleksi buku kami.',
    ]),
]
class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone_number = '';
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
            'phone_number' => ['nullable', 'string', 'max:17'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        // Redirect dengan delay kecil
        $user = Auth::user();
        if ($user->role === 'admin') {
            $this->redirect(route('admin.dashboard'));
        } else {
            $this->redirect(route('verification'));
        }
    }
}; ?>

<div class="relative flex size-full min-h-screen flex-col mt-20 bg-surface group/design-root overflow-x-hidden"
    style='font-family: "Plus Jakarta Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
        <div class="flex flex-1 justify-center">
            <div class="layout-content-container flex flex-col w-full max-w-md px-4">

                <h2 class="text-[#0C161B] tracking-light text-[24px] font-bold leading-tight text-center pb-3 pt-5"
                    style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Buat Akun Baru
                </h2>

                @if (session('status'))
                    <div class="px-4 py-3 text-center text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- wire:submit.prevent akan berfungsi setelah @livewireScripts ditambahkan --}}
                <form wire:submit="register">
                    <div class="flex flex-col gap-4 py-3">
                        <div>
                            <flux:input wire:model="name" label="Nama Lengkap"
                                class="bg-gray-200 rounded-md text-slate-900 [&_label]:text-slate-700" type="text"
                                required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
                            @error('name')
                                <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <flux:input wire:model="email" label="Email Address"
                                class="bg-gray-200 rounded-md text-slate-900 [&_label]:text-slate-700" type="email"
                                required autocomplete="email" placeholder="email@example.com" />
                            @error('email')
                                <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Menggunakan wire:ignore agar intl-tel-input tidak terganggu oleh update DOM Livewire --}}
                        <div class="flex flex-col" wire:ignore>
                            <label for="phone_number" class="text-sm font-medium text-gray-700 mb-1">Nomor
                                Telepon</label>
                            <input type="tel" id="phone_number"
                                class="form-control bg-white border border-gray-200 p-2 w-full rounded-md text-slate-900"
                                placeholder="823xxxxxxx" />
                        </div>
                        @error('phone_number')
                            <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                        @enderror

                        <div>
                            <flux:input wire:model="password" label="Password" type="password" placeholder="Password"
                                required viewable autocomplete="new-password"
                                class="bg-gray-200 rounded-md [&_label]:text-slate-700" />
                            @error('password')
                                <span class="text-sm text-red-600 mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <flux:input wire:model="password_confirmation" label="Konfirmasi Password" type="password"
                                placeholder="Konfirmasi Password" required viewable autocomplete="new-password"
                                class="bg-gray-200 rounded-md [&_label]:text-slate-700" />
                        </div>
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        <flux:button variant="primary" type="submit"
                            class="w-full cursor-pointer bg-primary hover:bg-primary/75 text-white font-semibold rounded-md px-4 py-2 transition-colors duration-200"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="register">Daftar</span>
                            <span wire:loading wire:target="register">Mendaftar...</span>
                        </flux:button>
                    </div>
                </form>

                @error('name')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                @error('email')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
                @error('password')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror


                <!-- Login Link -->
                <div class="py-3 text-center">
                    <span class="text-[#4c7b9a] text-sm font-normal leading-normal">Sudah punya akun? </span>
                    <a href="{{ route('login') }}" wire:navigate
                        class="text-[#4c7b9a] text-sm font-normal leading-normal underline hover:text-blue-600">
                        Masuk
                    </a>
                </div>

                <!-- Divider -->
                <div class="flex items-center py-4">
                    <div class="flex-grow border-t border-gray-300"></div>
                    <span class="flex-shrink mx-4 text-gray-500 text-sm">atau</span>
                    <div class="flex-grow border-t border-gray-300"></div>
                </div>

                <!-- Google Login Section -->
                <div class="py-2">
                    <a href="{{ route('auth.google') }}"
                        class="w-full flex items-center justify-center gap-3 bg-white border border-gray-300 rounded-md px-4 py-3 text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span>Daftar lewat Google</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Inisialisasi intl-tel-input
        const phoneInput = document.querySelector("#phone_number");
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "auto",
            geoIpLookup: callback => {
                fetch('https://ipinfo.io/json?token=4ea32d397b63f6')
                    .then(response => response.json())
                    .then(data => callback(data.country))
                    .catch(() => callback('id')); // default ke 'id' jika gagal
            },
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        // Kirim data ke Livewire saat input berubah
        phoneInput.addEventListener('change', function() {
            // @this mengacu pada komponen Livewire saat ini
            @this.set('phone_number', iti.getNumber());
        });
    </script>
@endpush
