<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    @fluxAppearance
</head>

<body class="bg-gray-50 antialiased">
    <x-main-navbar />

    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <flux:icon.book-open class="h-8 w-8 text-blue-600 mr-2" />
                        <span class="text-xl font-bold text-gray-900">Ngabaca</span>
                    </div>
                    <p class="text-gray-600 mb-4">
                        Explore our vast collection of books, from timeless classics to the latest releases.
                        Find your next adventure today.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">
                        Quick Links
                    </h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Kontak</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Terms of Service</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-4">
                        Categories
                    </h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Fiction</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Non-Fiction</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Science</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-gray-900">Romance</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8">
                <p class="text-center text-gray-600">
                    © 2025 Ngabaca. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @fluxScripts
</body>

</html>
