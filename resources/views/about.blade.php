<x-layouts.main title="About Us" description="Learn more about Ngabaca, our mission, and the team behind it.">

    {{-- Latar belakang sekarang diatur pada body, jadi div ini tidak lagi memerlukan warna bg --}}
    <div class="px-6 py-12 mx-auto max-w-7xl">

        <div class="grid items-center grid-cols-1 gap-10 mb-16 md:grid-cols-2">
            <div>
                <h2 class="mb-6 text-4xl font-bold text-black">Our Story</h2>
                <p class="mb-4 text-black">
                    Launched in 2025, Ngabaca is South Asia’s leading online book reading platform with an active
                    presence in Indonesia.
                    Supported by a wide range of customized marketing, data, and service solutions, Ngabaca hosts over
                    10,500 authors and 300 publishers, serving more than 3 million readers across the region.
                    Ngabaca offers access to over 1 million book titles and is growing rapidly.
                    The platform features a diverse collection of genres, ranging from fiction and non-fiction to
                    educational materials, classic literature, and contemporary works—catering to readers of all
                    interests.
                </p>
                <p class="text-black">
                    Ngabaca has more than 1 Million Books to offer, growing at a very fast. Ngabaca offers a diverse
                    assortment in categories ranging from consumer.
                </p>
            </div>

            <div class="flex justify-center">
                <img src="{{ asset('assets/images/gatau-nanya-aja.jpeg') }}" alt="Our Story"
                    class="max-w-full rounded-lg shadow-lg">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-16 text-center md:grid-cols-4">
            @php
                $stats = [
                    ['icon' => '🏪', 'value' => '10.5k', 'label' => 'Books active our site'],
                    ['icon' => '💰', 'value' => '33k', 'label' => 'Monthly Books Sale', 'highlight' => true],
                    ['icon' => '🛍️', 'value' => '45.5k', 'label' => 'Customer active in our site'],
                    ['icon' => '📈', 'value' => '25k', 'label' => 'Mostly People Come and Buy'],
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
            <h3 class="mb-2 text-3xl font-bold text-black">Meet Our Team</h3>
            <p class="text-black">Professionals behind our success</p>
        </div>

        <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
            @php
                $team = [
                    [
                        'name' => 'Ezra Ben Hanschel',
                        'title' => 'Founder & Chairman',
                        'image' => 'eben.jpg',
                        'social' => [
                            'instagram' => 'https://instagram.com/hnxzl',
                            'wa' => '#',
                            'linkedin' => '#',
                        ],
                    ],
                    [
                        'name' => 'Gilang Widyaputra',
                        'title' => 'Managing Director',
                        'image' => 'gilang.jpg',
                        'social' => [
                            'instagram' => '#',
                            'wa' => '#',
                            'linkedin' => '#',
                        ],
                    ],
                    [
                        'name' => 'Muhammad Alfin Pratama',
                        'title' => 'CEO MANAGER',
                        'image' => 'alpin.jpg',
                        'social' => [
                            'instagram' => 'https://instagram.com/visfiveor5',
                            'wa' => '#',
                            'linkedin' => '#',
                        ],
                    ],
                    [
                        'name' => 'Noufal Zaidaan',
                        'title' => 'FrontEnd Dev',
                        'image' => 'nopal.jpg',
                        'social' => [
                            'instagram' => 'https://instagram.com/boulleva',
                            'wa' => '#',
                            'linkedin' => '#',
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
                        <a href="{{ $member['social']['instagram'] }}" target="_blank" class="hover:text-gray-600"><i
                                class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/{{ $member['social']['wa'] }}" target="_blank"
                            class="hover:text-gray-600"><i class="fab fa-whatsapp"></i></a>
                        <a href="{{ $member['social']['linkedin'] }}" target="_blank" class="hover:text-gray-600"><i
                                class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.main>
