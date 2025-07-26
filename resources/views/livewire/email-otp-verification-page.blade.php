<div class="flex items-center justify-center min-h-screen bg-surface">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
        <div>
            <h2 class="text-2xl font-bold text-center text-gray-800">Verifikasi Email Anda</h2>
            <p class="mt-2 text-sm text-center text-gray-600">
                Kami telah mengirimkan kode verifikasi 6 digit ke alamat email <strong>{{ $email }}</strong>.
            </p>
        </div>

        @if ($successMessage)
            <div id="success-message"
                class="relative p-4 text-sm text-green-700 bg-green-100 border border-green-400 rounded-md">
                <button type="button" onclick="closeMessage('success-message')"
                    class="absolute top-2 right-2 text-green-700 hover:text-green-900">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                {{ $successMessage }}
            </div>
        @endif
        @if ($errorMessage)
            <div id="error-message"
                class="relative p-4 text-sm text-red-700 bg-red-100 border border-red-400 rounded-md">
                <button type="button" onclick="closeMessage('error-message')"
                    class="absolute top-2 right-2 text-red-700 hover:text-red-900">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
                {{ $errorMessage }}
            </div>
        @endif




        {{-- Container untuk OTP Input & Logic --}}
        <div id="otp-form">

            {{-- Hidden input untuk menampung nilai akhir OTP (string 6 digit) untuk Livewire --}}
            <input type="hidden" wire:model.defer="otp">

            <div class="flex justify-center gap-2">
                {{-- Render 6 input box untuk OTP --}}
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                        class="otp-input w-12 h-14 text-2xl font-semibold text-center text-gray-800 bg-gray-100 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primring-primary"
                        required>
                @endfor
            </div>

            @error('otp')
                <span class="block mt-4 text-xs text-center text-red-500">{{ $message }}</span>
            @enderror

            <div class="mt-6">
                <button wire:click="verifyOtp" wire:loading.attr="disabled"
                    class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-md shadow-sm hover:bg-primary/70 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <span wire:loading.remove wire:target="verifyOtp">Verifikasi</span>
                    <span wire:loading wire:target="verifyOtp">Memverifikasi...</span>
                </button>
            </div>
        </div>


        <div id="resend-container" class="text-sm text-center text-gray-600">
            Tidak menerima kode?
            <button id="resend-btn" wire:click="sendOtp" wire:loading.attr="disabled"
                class="font-medium text-indigo-600 hover:text-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <span>Kirim ulang</span>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- LOGIC UNTUK INPUT OTP ---
        const otpForm = document.getElementById('otp-form');
        if (otpForm) {
            const inputs = otpForm.querySelectorAll('.otp-input');
            const hiddenInput = otpForm.querySelector('input[type="hidden"]');

            const updateHiddenInput = () => {
                const otpValue = Array.from(inputs).map(input => input.value).join('');
                hiddenInput.value = otpValue;
                hiddenInput.dispatchEvent(new Event('input'));
            };

            otpForm.addEventListener('input', (e) => {
                if (!e.target.classList.contains('otp-input')) return;
                const input = e.target;
                const nextInput = input.nextElementSibling;
                if (/^[0-9]$/.test(input.value) && nextInput) {
                    nextInput.focus();
                }
                updateHiddenInput();
            });

            otpForm.addEventListener('keydown', (e) => {
                if (!e.target.classList.contains('otp-input')) return;
                if (e.key === 'Backspace' && e.target.value === '') {
                    const prevInput = e.target.previousElementSibling;
                    if (prevInput) {
                        prevInput.focus();
                    }
                }
            });

            inputs[0].addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                if (/^[0-9]{6}$/.test(pasteData)) {
                    inputs.forEach((input, index) => {
                        input.value = pasteData[index];
                    });
                    updateHiddenInput();
                    inputs[5].focus();
                }
            });

            if (inputs.length > 0) {
                inputs[0].focus();
            }
        }

        // --- LOGIC UNTUK RESEND OTP TIMER ---
        const resendContainer = document.getElementById('resend-container');
        if (resendContainer) {
            const resendButton = resendContainer.querySelector('#resend-btn');
            const buttonText = resendButton.querySelector('span');
            const TIMER_DURATION = 3 * 60; // 3 menit dalam detik
            let timerInterval;

            const formatTime = (seconds) => {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
            };

            const stopTimer = () => {
                clearInterval(timerInterval);
                localStorage.removeItem('otpTimerEndTime');
                resendButton.disabled = false;
                buttonText.textContent = 'Kirim ulang';
            };

            const runCountdown = (endTime) => {
                resendButton.disabled = true;
                timerInterval = setInterval(() => {
                    const now = Math.floor(Date.now() / 1000);
                    const remainingSeconds = endTime - now;

                    if (remainingSeconds <= 0) {
                        stopTimer();
                    } else {
                        buttonText.textContent =
                            `Kirim ulang dalam ${formatTime(remainingSeconds)}`;
                    }
                }, 1000);
            };

            const startTimer = () => {
                clearInterval(timerInterval); // Hentikan timer lama jika ada
                const now = Math.floor(Date.now() / 1000);
                const endTime = now + TIMER_DURATION;
                localStorage.setItem('otpTimerEndTime', endTime); // Simpan waktu selesai di localStorage
                runCountdown(endTime);
            };

            // Cek jika ada timer yang tersimpan di localStorage saat halaman dimuat
            const savedEndTime = localStorage.getItem('otpTimerEndTime');
            if (savedEndTime) {
                const now = Math.floor(Date.now() / 1000);
                if (savedEndTime > now) {
                    runCountdown(parseInt(savedEndTime)); // Lanjutkan countdown
                } else {
                    stopTimer(); // Hapus timer yang sudah kedaluwarsa
                }
            }

            // Tambahkan event listener ke tombol resend
            resendButton.addEventListener('click', () => {
                if (!resendButton.disabled) {
                    startTimer();
                }
            });
        }
    });

    function closeMessage(messageId) {
        const element = document.getElementById(messageId);
        if (element) {
            element.style.display = 'none';
        }
    }

    // Auto hide messages after 5 seconds
    document.addEventListener('DOMContentLoaded', () => {
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');

        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }

        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 5000);
        }
    });
</script>
