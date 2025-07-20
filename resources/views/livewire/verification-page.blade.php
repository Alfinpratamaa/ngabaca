<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">

        <!-- Step 2: Input OTP -->
        <div>
            <h2 class="text-2xl font-bold text-center text-gray-800">Masukkan Kode OTP</h2>
            @if (session('success'))
                <p class="mt-2 text-sm text-center text-green-600">
                    {{ session('success') }}
                </p>
            @endif
        </div>

        <div x-data="{
            otp: @entangle('otp'),
            handleInput(index, event) {
                let value = event.target.value;
                if (value.match(/^[0-9]$/)) {
                    if (index < 5) {
                        this.$refs['otp-input-' + (index + 1)].focus();
                    }
                } else {
                    event.target.value = '';
                }
            },
            handlePaste(event) {
                let paste = (event.clipboardData || window.clipboardData).getData('text').slice(0, 6);
                if (paste.match(/^[0-9]{6}$/)) {
                    paste.split('').forEach((char, index) => {
                        this.otp[index] = char;
                        this.$refs['otp-input-' + index].value = char;
                    });
                    this.$refs['otp-input-5'].focus();
                    event.preventDefault();
                }
            },
            handleBackspace(index, event) {
                if (event.target.value === '' && index > 0) {
                    this.$refs['otp-input-' + (index - 1)].focus();
                }
            }
        }" class="space-y-6">
            <div class="flex justify-center gap-2" @paste="handlePaste($event)">
                @foreach ($otp as $index => $digit)
                    <input type="text" x-ref="otp-input-{{ $index }}" wire:model.defer="otp.{{ $index }}"
                        @input="handleInput({{ $index }}, $event)"
                        @keydown.backspace="handleBackspace({{ $index }}, $event)" maxlength="1"
                        class="w-12 h-14 text-2xl font-semibold text-center text-gray-800 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                @endforeach
            </div>

            @error('otp.*')
                <span class="block mt-1 text-xs text-center text-red-500">Kode OTP harus 6 digit.</span>
            @enderror

            <div>
                <button wire:click="verifyOtp" wire:loading.attr="disabled"
                    class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <span wire:loading.remove wire:target="verifyOtp">Verifikasi</span>
                    <span wire:loading wire:target="verifyOtp">Memverifikasi...</span>
                </button>
            </div>
        </div>

        <div class="text-sm text-center text-gray-600">
            Tidak menerima kode?
            <button wire:click="sendOtp" class="font-medium text-indigo-600 hover:text-indigo-500">
                Kirim ulang
            </button>
        </div>

        @if ($errorMessage)
            <p class="text-sm text-center text-red-600">{{ $errorMessage }}</p>
        @endif

    </div>
</div>
