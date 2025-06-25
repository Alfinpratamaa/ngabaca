<x-layouts.main>
    <div class="py-12 px-6 max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ url('/') }}" class="hover:underline">Home</a> /
            <span class="text-gray-800">About</span>
        </nav>

        <!-- Our Story -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-16">
            <!-- Text Section -->
            <div>
                <h2 class="text-4xl font-bold mb-6">Our Story</h2>
                <p class="text-gray-700 mb-4">
                    Launched in 2015, Ngabaca is South Asia’s leading online book reading platform with an active presence in Indonesia. 
                    Supported by a wide range of customized marketing, data, and service solutions, Ngabaca hosts over 10,500 authors and 300 publishers, serving more than 3 million readers across the region.
                    Ngabaca offers access to over 1 million book titles and is growing rapidly.
                    The platform features a diverse collection of genres, ranging from fiction and non-fiction to educational materials, classic literature, and contemporary works—catering to readers of all interests.


                </p>
                <p class="text-gray-700">
                    Ngabaca has more than 1 Million Books to offer, growing at a very fast. Ngabaca offers a diverse assortment in categories ranging from consumer.
                </p>
            </div>

            <!-- Image Section -->
            <div class="flex justify-center">
                <img src="{{ asset('assets/images/gatau-nanya-aja.jpeg') }}" alt="Our Story" class="rounded-lg shadow-lg max-w-full">
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16 text-center">
            @php
                $stats = [
                    ['icon' => '🏪', 'value' => '10.5k', 'label' => 'Books active our site'],
                    ['icon' => '💰', 'value' => '33k', 'label' => 'Monthly Books Sale', 'highlight' => true],
                    ['icon' => '🛍️', 'value' => '45.5k', 'label' => 'Customer active in our site'],
                    ['icon' => '📈', 'value' => '25k', 'label' => 'Mostly People Come and Buy'],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="border rounded-lg p-6 @if($stat['highlight'] ?? false) bg-red-100 shadow-md @endif">
                    <div class="text-3xl mb-2">{{ $stat['icon'] }}</div>
                    <div class="text-2xl font-bold mb-1">{{ $stat['value'] }}</div>
                    <div class="text-sm text-gray-600">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Team Section -->
        <div class="text-center mb-10">
            <h3 class="text-3xl font-bold mb-2">Meet Our Team</h3>
            <p class="text-gray-600">Professionals behind our success</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @php
                $team = [
                    ['name' => 'Ezra Ben Hanschel', 'title' => 'Founder & Chairman', 'image' => 'tom.jpg'],
                    ['name' => 'Gilang Widyaputra', 'title' => 'Managing Director', 'image' => 'emma.jpg'],
                    ['name' => 'Muhammad Alfin Pratama', 'title' => 'CEO MANAGER', 'image' => 'will.jpg'],
                    ['name' => 'Noufal Zaidaan', 'title' => 'FrontEnd Dev', 'image' => 'tom.jpg'],

                ];
            @endphp

            @foreach ($team as $member)
                <div class="bg-gray-50 p-6 rounded-lg shadow text-center">
                    <img src="{{ asset('assets/images/' . $member['image']) }}" alt="{{ $member['name'] }}" class="w-40 h-40 mx-auto rounded-full object-cover mb-4">
                    <h4 class="text-xl font-semibold">{{ $member['name'] }}</h4>
                    <p class="text-gray-500 mb-3">{{ $member['title'] }}</p>
                    <div class="flex justify-center space-x-4 text-gray-600 text-lg">
                        <a href="https://x.com/nflzdn_"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/boulleva/"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.main>
