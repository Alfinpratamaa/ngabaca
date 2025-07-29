<x-layouts.main title="Informasi Tambahan" :breadcrumbs="['Informasi Tambahan']">

    <div class="container mx-auto px-4 py-10">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Lengkapi Informasi Anda</h1>
                <p class="text-gray-500 mt-2">Silakan isi informasi yang diperlukan di bawah ini untuk menyelesaikan
                    pengaturan akun Anda.</p>
            </div>

            @if (session('info'))
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md mb-6" role="alert">
                    <p class="font-bold">Info</p>
                    <p>{{ session('info') }}</p>
                </div>
            @endif

            @if (empty(auth()->user()->phone_number) || empty(auth()->user()->password))
                <form action="{{ route('additional-info.store') }}" method="POST" class="space-y-6" id="info-form">
                    @csrf

                    @if (empty(auth()->user()->phone_number))
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                Telepon</label>
                            {{-- Input telepon yang telah dimodifikasi --}}
                            <input type="tel" name="phone_number" id="phone_number"
                                value="{{ old('phone_number') }}" autocomplete="off"
                                placeholder="Masukan nomor telepon anda"
                                class="iti w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                required>
                            @error('phone_number')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    @if (empty(auth()->user()->password))
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" id="password" autocomplete="off"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                placeholder="Masukan password anda" required>
                            @error('password')
                                <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                                placeholder="Confirmasi Password anda" required>
                        </div>
                    @endif

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-150 ease-in-out">
                            Simpan Informasi
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md text-center"
                    role="alert">
                    <p class="font-bold">Informasi Lengkap</p>
                    <p>Semua informasi tambahan Anda sudah lengkap. Terima kasih!</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Kustomisasi untuk menyatukan dengan Tailwind CSS */
        .iti {
            width: 100%;
        }

        .iti__country-list {
            z-index: 50;
            /* Pastikan dropdown di atas elemen lain */
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script>
        const phoneInputField = document.querySelector("#phone_number");
        if (phoneInputField) {
            // Inisialisasi intl-tel-input
            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: "id", // Atur negara awal ke Indonesia
                preferredCountries: ["id", "my", "sg", "us", "gb"],
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            });

            const infoForm = document.querySelector("#info-form");

            // Saat form disubmit, update value input dengan nomor internasional
            infoForm.addEventListener("submit", function() {
                const fullNumber = phoneInput.getNumber(); // Mendapatkan nomor lengkap (misal: +628123...)
                phoneInputField.value = fullNumber;
            });
        }
    </script>

</x-layouts.main>
