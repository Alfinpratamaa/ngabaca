@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-[#FFF8F1] flex justify-center">
    <div class="flex w-full max-w-7xl gap-8 py-10 px-4">
        <!-- Sidebar kiri -->
        <div class="hidden lg:block">
            @livewire('catalog-sidebar')
        </div>
        <!-- Sidebar sticky untuk mobile/tablet -->
        <div class="block lg:hidden mb-6">
            @livewire('catalog-sidebar')
        </div>
        <!-- Grid katalog kanan -->
        <div class="flex-1">
            @livewire('book-catalog')
        </div>
    </div>
</div>
@endsection 