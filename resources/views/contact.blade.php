<x-layouts.main>
    {{-- Latar belakang abu-abu muda untuk seluruh bagian --}}
    {{-- Padding ditambahkan di sini untuk memberi jarak dari tepi layar --}}
    <div class="bg-[#fff8f0] font-sans px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        
        {{-- Header: Dibuat terpusat dengan lebar maksimal agar tetap mudah dibaca --}}
        <div class="max-w-3xl mx-auto text-center mb-12">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900">Contact our team</h1>
            <p class="mt-4 text-lg text-gray-600">
                Got any questions about the books or scaling on our platform? We're here to help. Chat to our friendly team 24/7 and get onboard in less than 5 minutes.
            </p>
        </div>

        {{-- Box Putih Utama: Box ini sekarang mengisi ruang horizontal yang tersedia --}}
        
            {{-- Kontainer Internal: Ini memusatkan grid di dalam box putih --}}
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-16">

                    {{-- Kolom Kiri: Formulir Kontak --}}
                    <div class="lg:pr-8">
                        <form action="#" method="" class="space-y-6">
                            @csrf {{-- Token CSRF Laravel --}}

                            {{-- Baris Nama Depan & Nama Belakang --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="first-name" class="block text-sm font-medium text-gray-700">First name</label>
                                    <input type="text" name="first-name" id="first-name" placeholder="First name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#92400E] focus:border-[#92400E]">
                                </div>
                                <div>
                                    <label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
                                    <input type="text" name="last-name" id="last-name" placeholder="Last name" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#92400E] focus:border-[#92400E]">
                                </div>
                            </div>

                            {{-- Input Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" placeholder="you@company.com" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#92400E] focus:border-[#92400E]">
                            </div>

                            {{-- Input Nomor Telepon --}}
                            <div>
                                <label for="phone-number" class="block text-sm font-medium text-gray-700">Phone number</label>
                                <div class="mt-1 relative rounded-md shadow-sm bg-white">
                                    <div class="absolute inset-y-0 left-0 flex items-center">
                                        <select id="country" name="country" class="h-full py-0 pl-3 pr-7 border-transparent bg-transparent text-gray-500 focus:outline-none focus:ring-[#92400E] focus:border-[#92400E]">
                                            <option>ID</option>
                                            <option>US</option>
                                            <option>EU</option>
                                        </select>
                                    </div>
                                    <input type="tel" name="phone-number" id="phone-number" placeholder="+62 (000) 0000" class="block w-full pl-20 px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-[#92400E] focus:border-[#92400E]">
                                </div>
                            </div>

                            {{-- Input Pesan --}}
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea id="message" name="message" rows="4" placeholder="Leave us a message..." class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-[#92400E] focus:border-[#92400E]"></textarea>
                            </div>

                            {{-- Checkbox Layanan --}}
                            <div>
                                <h3 class="text-sm font-medium text-gray-700">Services</h3>
                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <input id="website-design" name="services[]" type="checkbox" value="Website design" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="website-design" class="ml-3 block text-sm text-gray-800">Website design</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="content-creation" name="services[]" type="checkbox" value="Content creation" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="content-creation" class="ml-3 block text-sm text-gray-800">Content creation</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="ux-design" name="services[]" type="checkbox" value="UX design" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="ux-design" class="ml-3 block text-sm text-gray-800">UX design</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="strategy" name="services[]" type="checkbox" value="Strategy & consulting" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="strategy" class="ml-3 block text-sm text-gray-800">Strategy & consulting</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="user-research" name="services[]" type="checkbox" value="User research" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="user-research" class="ml-3 block text-sm text-gray-800">User research</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="other" name="services[]" type="checkbox" value="Other" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="other" class="ml-3 block text-sm text-gray-800">Other</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Kirim --}}
                            <div>
                                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-secondary bg-primary hover:bg-primary/75 cursor-pointer ">
                                    Send message
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Kolom Kanan: Info Kontak Lainnya --}}
                    <div class="mt-12 lg:mt-0 space-y-10">
                        {{-- Chat with us --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Chat with us</h3>
                            <p class="mt-1 text-gray-600">Speak to our friendly team via live chat.</p>
                            <div class="mt-4 space-y-3">
                                <a href="#" class="flex items-center text-[#92400E] hover:underline">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    <span class="font-medium">Start a live chat</span>
                                </a>
                                <a href="mailto:email@example.com" class="flex items-center text-[#92400E] hover:underline">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <span class="font-medium">Shoot us an email</span>
                                </a>
                                <a href="https://x.com/nflzdn_" class="flex items-center text-[#92400E] hover:underline">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 16 16"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-1.78 13h2.683L4.08 2.16H1.31l9.51 11.59Z"/></svg>
                                    <span class="font-medium">Message us on X</span>
                                </a>
                            </div>
                        </div>
                        
                        {{-- Call us --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Call us</h3>
                            <p class="mt-1 text-gray-600">Call our team Mon-Fri from 8am to 5pm.</p>
                            <div class="mt-4">
                                <a href="tel:+15550000000" class="flex items-center text-[#92400E] hover:underline">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span class="font-medium">+62 (555) 908-3168</span>
                                </a>
                            </div>
                        </div>

                        {{-- Visit us --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Visit us</h3>
                            <p class="mt-1 text-gray-600">Chat to us in person at our Bandung HQ.</p>
                            <div class="mt-4">
                                <a href="https://maps.google.com/?q=100+Smith+Street,+Collingwood+VIC+3066" target="_blank" rel="noopener noreferrer" class="flex items-start text-[#92400E] hover:underline">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="font-medium">Bandung, Kopo Regency Absolute Cinema</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-layouts.main>