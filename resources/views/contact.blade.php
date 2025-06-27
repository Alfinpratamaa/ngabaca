<x-layouts.main>
    <div class="py-12 px-6 max-w-7xl mx-auto">
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ route('home') }}" wire:navigate class="hover:underline">Home</a> /
            <span class="text-gray-800">Contact</span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Info Sidebar -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start space-x-4 mb-6">
                    <div class="text-red-500 text-2xl">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg">Call To Us</h4>
                        <p class="text-gray-600 text-sm">We are available 24/7, 7 days a week.</p>
                        <p class="text-gray-800 mt-1 font-medium">Phone: +62855512331</p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="flex items-start space-x-4">
                    <div class="text-red-500 text-2xl">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-lg">Write To US</h4>
                        <p class="text-gray-600 text-sm">Fill out our form and we will contact you within 24 hours.</p>
                        <p class="text-gray-800 mt-2">Emails: noufalzidan7@gmail.com</p>
                        <p class="text-gray-800">support@ngabaca.com</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <form wire:submit.prevent="submitForm" class="bg-white rounded-lg shadow p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" wire:model="name" placeholder="Your Name *"
                        class="input input-bordered w-full">
                    <input type="email" wire:model="email" placeholder="Your Email *"
                        class="input input-bordered w-full">
                    <input type="text" wire:model="phone" placeholder="Your Phone *"
                        class="input input-bordered w-full">
                </div>
                <textarea wire:model="message" rows="5" placeholder="Your Message" class="textarea textarea-bordered w-full"></textarea>

                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</x-layouts.main>
