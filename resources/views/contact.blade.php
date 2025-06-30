<x-layouts.main>
    {{-- Jika Anda belum menggunakan Font Awesome, tambahkan link ini di dalam tag <head> layout utama Anda --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <div class="py-12 px-6 max-w-4xl mx-auto"> {{-- Max-width disesuaikan agar lebih fokus di layout satu kolom --}}
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" wire:navigate class="hover:underline">Home</a> /
            <span class="text-gray-800">Contact</span>
        </nav>

        <div class="flex flex-col gap-10">
            <div class="bg-white rounded-lg shadow p-8"> {{-- Padding ditambah agar lebih lega --}}
                
                {{-- Call To Us --}}
                <div class="flex items-start space-x-4 mb-8">
                    <div class="text-red-500 text-3xl mt-1">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-xl">Call To Us</h4>
                        <p class="text-gray-600 mt-1">We are available 24/7, 7 days a week.</p>
                        <p class="text-gray-800 mt-2 font-medium">Phone: +62855512331</p>
                    </div>
                </div>

                <hr class="my-6">

                {{-- Write To Us --}}
                <div class="flex items-start space-x-4">
                    <div class="text-red-500 text-3xl mt-1">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-xl">Write To US</h4>
                        <p class="text-gray-600 mt-1">Fill out our form and we will contact you within 24 hours.</p>
                        <p class="text-gray-800 mt-2 font-medium">Emails: noufalzidan7@gmail.com</p>
                        <p class="text-gray-800 mt-1 font-medium">Support: support@ngabaca.com</p>
                    </div>
                </div>

                <hr class="my-6">

                {{-- Social Media --}}
                <div class="flex items-start space-x-4">
                    <div class="text-red-500 text-3xl mt-1">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-xl">Follow Us</h4>
                        <p class="text-gray-600 mt-1">Connect with us on social media.</p>
                        <div class="flex space-x-4 mt-3">
                            <a href="facebook.com/WatashiNaufalZidanDesu" class="text-gray-500 hover:text-blue-600 text-2xl"><i class="fab fa-facebook-f"></i></a>
                            <a href="instagram.com/boulleva" class="text-gray-500 hover:text-pink-500 text-2xl"><i class="fab fa-instagram"></i></a>
                            <a href="x.com/nflzdn_" class="text-gray-500 hover:text-blue-400 text-2xl"><i class="fab fa-twitter"></i></a>
                            <a href="linkedin.com/in/noufal-zaidaan" class="text-gray-500 hover:text-blue-700 text-2xl"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>

            </div>

            <form wire:submit.prevent="submitForm" class="bg-white rounded-lg shadow p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <input type="text" wire:model="name" placeholder="Your Name *"
                           class="input input-bordered w-full">
                    <input type="email" wire:model="email" placeholder="Your Email *"
                           class="input input-bordered w-full">
                    <input type="text" wire:model="phone" placeholder="Your Phone *"
                           class="input input-bordered w-full">
                </div>
                <textarea wire:model="message" rows="6" placeholder="Your Message" class="textarea textarea-bordered w-full"></textarea>

                <div class="text-right"> {{-- Tombol di sebelah kanan --}}
                    <button type="submit" class="bg-red-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-600 transition duration-300">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.main>