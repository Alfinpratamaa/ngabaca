<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $avatar; // Properti untuk file upload baru
    public $currentAvatarPath = null; // Properti untuk menyimpan path avatar saat ini

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->currentAvatarPath = $user->avatar;
    }

    /**
     * Computed property untuk mendapatkan URL avatar yang benar.
     */
    public function getAvatarUrlProperty(): ?string
    {
        if ($this->avatar) {
            return $this->avatar->temporaryUrl();
        }
        if ($this->currentAvatarPath) {
            if (Str::startsWith($this->currentAvatarPath, 'http')) {
                return $this->currentAvatarPath;
            }
            return Storage::url($this->currentAvatarPath);
        }
        return null;
    }

    /**
     * Logika penyimpanan yang lebih andal untuk memperbaiki bug.
     */
    public function updateProfileInformation()
    {
        $user = Auth::user();

        $validatedData = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if ($this->avatar) {
            if ($user->avatar && !Str::startsWith($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $this->avatar->store('avatars', 'public');
            return redirect()->route('settings.profile')->with('status', 'avatar-updated');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Perbarui state komponen dari model yang baru disimpan
        $this->name = $user->name;
        $this->email = $user->email;
        $this->currentAvatarPath = $user->avatar;

        $this->reset('avatar');

        $this->dispatch('profile-updated', name: $user->name);

        return redirect()->route('settings.profile')->with('status', 'profile-updated');
    }

    /**
     * Delete the current avatar.
     */
    public function deleteAvatar()
    {
        $user = Auth::user();
        $currentAvatar = $user->avatar;

        if ($currentAvatar && !Str::startsWith($currentAvatar, 'http')) {
            Storage::disk('public')->delete($currentAvatar);
        }

        $user->update(['avatar' => null]);
        $this->currentAvatarPath = null;

        $this->dispatch('profile-updated', name: $user->name);

        return redirect()->route('settings.profile')->with('status', 'avatar-deleted');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }
        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full bg-surface">
    <x-settings.layout :heading="__('Profil')" :subheading="__('Perbarui nama, alamat email, dan avatar Anda')">
        {{-- [FIX UTAMA] Menambahkan wire:key ke form untuk membantu Livewire melacak elemen ini --}}
        <form wire:submit="updateProfileInformation" wire:key="profile-information-form" class="my-6 w-full space-y-8">

            <!-- Avatar Section -->
            <livewire:settings.avatar-upload />

            <!-- Name Input -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input wire:model="name" id="name" type="text" required autofocus autocomplete="name"
                    class="mt-1 block w-full p-2 text-black border border-slate-800 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input wire:model.live="email" id="email" type="email" readonly disabled
                    class="mt-1 block w-full p-2 text-gray-500 bg-muted border border-slate-300 rounded-md shadow-sm cursor-not-allowed sm:text-sm">
                <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah untuk keamanan akun</p>

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div class="mt-3 text-sm text-gray-600">
                        <p>
                            Alamat email Anda belum terverifikasi.
                            <button type="button" wire:click.prevent="resendVerificationNotification"
                                class="underline text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Kirim ulang email verifikasi.
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-green-600">
                                Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" wire:loading.attr="disabled"
                    class="inline-flex justify-center items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-secondary bg-primary hover:bg-primary/70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    {{-- Loading Indicator --}}
                    <svg wire:loading wire:target="updateProfileInformation"
                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span wire:loading.remove wire:target="updateProfileInformation">Simpan</span>
                    <span wire:loading wire:target="updateProfileInformation">Menyimpan...</span>
                </button>

                <x-action-message class="me-3" on="profile-updated">
                    <span class="text-sm font-medium text-green-600">Tersimpan.</span>
                </x-action-message>
            </div>
        </form>

        {{-- [FIX UTAMA] Menambahkan wire:key ke komponen anak --}}
        <livewire:settings.delete-user-form wire:key="delete-user-form" />
    </x-settings.layout>
</section>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('profile-updated', (event) => {
            // Handle profile updated event
            console.log('Profile updated:', event);

            // You can add additional logic here, such as:
            // - Show a toast notification
            // - Update other parts of the UI
            // - Trigger other components to refresh

            // Example: Update page title with new name if available
            if (event.name) {
                document.title = `${event.name} - Profile Settings`;
            }
        });
    });
</script>
