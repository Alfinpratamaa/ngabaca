<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AvatarUpload extends Component
{
    use WithFileUploads;

    public $avatar;
    public $currentAvatarPath;
    protected $listeners = ['confirm-delete-avatar' => 'deleteAvatar'];

    public function mount()
    {
        $this->currentAvatarPath = Auth::user()->avatar;
    }

    public function getAvatarUrlProperty()
    {
        if ($this->avatar) {
            return $this->avatar->temporaryUrl();
        }

        if ($this->currentAvatarPath) {
            return Str::startsWith($this->currentAvatarPath, 'http')
                ? $this->currentAvatarPath
                : Storage::url($this->currentAvatarPath);
        }

        return null;
    }

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'nullable|image|max:10368', // 10MB
        ]);

        $user = Auth::user();

        if ($user->avatar && !Str::startsWith($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $this->avatar->store('avatars', 'public');
        $user->save();

        $this->currentAvatarPath = $user->avatar;
        $this->dispatch('avatar-updated');
        $this->reset('avatar');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && !Str::startsWith($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);
        $this->currentAvatarPath = null;

        $this->dispatch('avatar-updated');
    }

    public function confirmDeleteAvatar()
    {
        $this->dispatch('confirm-delete-avatar');
    }

    public function render()
    {
        return view('livewire.settings.avatar-upload');
    }
}
