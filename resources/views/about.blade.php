<x-layouts.main title="Tentang Kami" description="Tentang Ngabaca, tujuan, dan tim di baliknya.">

    <div class="px-6 py-12 mx-auto max-w-7xl">
        <div class="grid items-center grid-cols-1 gap-10 mb-16 md:grid-cols-2">
            <div>
                <h2 class="mb-6 text-4xl font-bold text-black">Cerita Kami</h2>
                <p class="mb-4 text-black">
                    Ngabaca berdiri pada 2025 sebagai toko e‑commerce buku digital yang berfokus pada kebutuhan
                    pembaca di Indonesia. Kami menjadi jembatan bagi penulis dan penerbit untuk menjual buku mereka
                    secara lebih luas melalui etalase yang modern, cepat, dan mudah digunakan.
                </p>
                <p class="mb-4 text-black">
                    Kami mengutamakan pengalaman belanja yang sederhana: pencarian yang relevan, pratinjau konten,
                    rekomendasi personal, pembayaran aman, dan distribusi instan untuk format digital. Untuk buku
                    cetak, kami bekerja sama dengan mitra logistik tepercaya agar pesanan sampai dengan cepat.
                </p>
                <p class="text-black">
                    Koleksi kami mencakup berbagai kategori: fiksi, nonfiksi, pendidikan, klasik, hingga karya
                    kontemporer. Misi kami jelas: membuat proses menemukan dan membeli buku menjadi lebih mudah,
                    transparan, dan menyenangkan bagi semua orang.
                </p>
            </div>

            <div class="flex justify-center">
                <img src="{{ asset('assets/images/gatau-nanya-aja.jpeg') }}" alt="Tentang Ngabaca"
                    class="max-w-full rounded-lg shadow-lg">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-16 text-center md:grid-cols-4">
            @php
                $stats = [
                    ['icon' => '📚', 'value' => '10.5k', 'label' => 'Judul tersedia'],
                    ['icon' => '📝', 'value' => '10.5k+', 'label' => 'Penulis'],
                    ['icon' => '🛒', 'value' => '3M+', 'label' => 'Pelanggan'],
                    ['icon' => '🏷️', 'value' => '300+', 'label' => 'Penerbit & Mitra'],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="p-6 border border-black rounded-lg">
                    <div class="mb-2 text-3xl">{{ $stat['icon'] }}</div>
                    <div class="mb-1 text-2xl font-bold text-black">{{ $stat['value'] }}</div>
                    <div class="text-sm text-black">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="mb-10 text-center">
            <h3 class="mb-2 text-3xl font-bold text-black">Tim Kami</h3>
            <p class="text-black">Orang-orang di balik pengalaman belanja buku di Ngabaca</p>
        </div>

        <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
            @php
                $team = [
                    [
                        'name' => 'Ezra Ben Hanschel',
                        'title' => 'UI/UX Designer',
                        'image' => 'eben.jpg',
                        'social' => [
                            'instagram' => 'https://instagram.com/hnxzl',
                            'wa' => null,
                            'linkedin' => null,
                        ],
                    ],
                    [
                        'name' => 'Gilang Widyaputra',
                        'title' => 'Backend Developer',
                        'image' => 'gilang.jpg',
                        'social' => [
                            'instagram' => null,
                            'wa' => null,
                            'linkedin' => null,
                        ],
                    ],
                    [
                        'name' => 'Muhammad Alfin Pratama',
                        'title' => 'Tech Lead & FullStack Developer',
                        'image' => 'alpin.jpg',
                        'social' => [
                            'instagram' => 'https://instagram.com/visfiveor5',
                            'wa' => '628515369960',
                            'linkedin' => 'https://www.linkedin.com/in/alfinpr',
                        ],
                    ],
                    [
                        'name' => 'Noufal Zaidaan',
                        'title' => 'FrontEnd Developer',
                        'image' => 'nopal.jpg',
                        'social' => [
                            'instagram' => 'https://instagram.com/boulleva',
                            'wa' => null,
                            'linkedin' => null,
                        ],
                    ],
                ];
            @endphp

            @foreach ($team as $member)
                <div class="p-6 text-center bg-[#fff8f0] shadow">
                    <img src="{{ asset('assets/images/' . $member['image']) }}" alt="{{ $member['name'] }}"
                        class="object-cover w-40 h-40 mx-auto mb-4 rounded-full">
                    <h4 class="text-xl font-semibold text-black">{{ $member['name'] }}</h4>
                    <p class="mb-3 text-black">{{ $member['title'] }}</p>
                    <div class="flex justify-center space-x-4 text-lg text-black">
                        @if (!empty($member['social']['instagram']))
                            <a href="{{ $member['social']['instagram'] }}" target="_blank" rel="noopener"
                                aria-label="Instagram {{ $member['name'] }}"
                                class="hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" role="img" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                                    </rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg>
                            </a>
                        @endif
                        @if (!empty($member['social']['wa']))
                            <a href="https://wa.me/{{ $member['social']['wa'] }}" target="_blank" rel="noopener"
                                aria-label="WhatsApp {{ $member['name'] }}"
                                class="hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" role="img" fill="currentColor">
                                    <path
                                        d="M20.52 3.48A11.77 11.77 0 0 0 12.01 0C5.4 0 .06 5.34.06 11.94c0 2.11.55 4.16 1.6 5.98L0 24l6.29-1.64a11.9 11.9 0 0 0 5.72 1.46h.01c6.61 0 11.95-5.34 11.95-11.94a11.87 11.87 0 0 0-3.45-8.4zM12 21.8a9.85 9.85 0 0 1-5.02-1.37l-.36-.21-3.73.97.99-3.64-.24-.37a9.9 9.9 0 0 1-1.52-5.26c0-5.45 4.44-9.89 9.88-9.89 2.64 0 5.12 1.03 6.98 2.9a9.84 9.84 0 0 1 2.9 6.99c0 5.45-4.44 9.88-9.88 9.88zm5.41-7.37c-.29-.15-1.72-.85-1.99-.94-.27-.1-.47-.15-.67.15-.2.29-.77.94-.94 1.13-.17.2-.35.22-.64.07-.29-.15-1.23-.45-2.34-1.44-.86-.77-1.44-1.72-1.61-2-.17-.29-.02-.45.13-.6.13-.13.29-.35.44-.52.15-.17.2-.29.3-.49.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.29-1.04 1.02-1.04 2.48s1.07 2.88 1.22 3.08c.15.2 2.11 3.23 5.11 4.53.71.31 1.27.5 1.7.64.71.23 1.36.2 1.87.12.57-.08 1.72-.7 1.96-1.38.24-.68.24-1.26.17-1.38-.07-.12-.26-.2-.55-.35z" />
                                </svg>
                            </a>
                        @endif
                        @if (!empty($member['social']['linkedin']))
                            <a href="{{ $member['social']['linkedin'] }}" target="_blank" rel="noopener"
                                aria-label="LinkedIn {{ $member['name'] }}"
                                class="hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" role="img" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="2" ry="2">
                                    </rect>
                                    <path d="M8 11v5M8 8v.01M12 16v-5a2 2 0 0 1 4 0v5"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.main>
