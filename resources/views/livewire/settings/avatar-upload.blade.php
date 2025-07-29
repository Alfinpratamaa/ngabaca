<div wire:key="avatar-upload">
    <label class="block text-sm font-medium text-gray-700">Avatar</label>
    <div class="mt-2 flex items-center space-x-4">
        <div class="relative group">
            <label for="avatar-upload" class="cursor-pointer">
                <div class="h-24 w-24 rounded-full overflow-hidden border-2 border-gray-300 shadow-sm">
                    @if ($this->avatar_url)
                        <img class="h-full w-full object-cover" src="{{ $this->avatar_url }}" alt="Avatar">
                    @else
                        <div class="h-full w-full bg-gray-100 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div
                    class="absolute inset-0 bg-black bg-opacity-60 flex flex-col items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                    <span class="text-white text-xs font-bold mt-1">Edit Foto</span>
                </div>
            </label>
            <input id="avatar-upload" type="file" wire:model.live="avatar" accept="image/*" class="hidden"
                wire:key="avatar-upload-input">
        </div>

        @if ($currentAvatarPath)
            <button type="button" onclick="confirmDeleteAvatar()"
                class="p-2 rounded-full text-gray-400 hover:bg-red-100 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                <span class="sr-only">Hapus Avatar</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </button>
        @endif
    </div>
    @error('avatar')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
<script>
    function confirmDeleteAvatar() {
        Swal.fire({
            title: 'Hapus Avatar?',
            text: "Avatar Anda akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('confirm-delete-avatar');
            }
        });

        Livewire.on('avatar-updated', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Avatar berhasil diperbarui',
                showConfirmButton: false,
                timer: 2000
            });
        });

    }
</script>
