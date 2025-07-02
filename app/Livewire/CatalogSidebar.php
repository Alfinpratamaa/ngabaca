<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CatalogSidebar extends Component
{
    public $selectedCategory = '';
    public $minPrice = 0;
    public $maxPrice = 500000;
    public $selectedRating = '';
    public $sortBy = 'featured';

    public function applyFilters()
    {
        // Kirim semua filter sekaligus saat tombol ditekan
        $this->dispatch('filtersApplied', [
            'category' => $this->selectedCategory,
            'price' => [
                'min' => $this->minPrice,
                'max' => $this->maxPrice,
            ],
            'rating' => $this->selectedRating,
            'sort' => $this->sortBy,
        ]);
    }

    // Fungsi ini akan dijalankan setiap kali properti yang terhubung ke wire:model.live diubah
    public function updated($property)
    {
        // Untuk select dan radio button, kita bisa langsung dispatch event
        // agar lebih responsif tanpa harus menekan tombol "Apply Filters"
        if ($property === 'selectedCategory' || $property === 'selectedRating' || $property === 'sortBy') {
            $this->applyFilters();
        }
    }

    public function render()
    {
        // **PERBAIKAN ADA DI SINI**
        // Kita mengambil data kategori dan meneruskannya ke view
        // setiap kali komponen di-render.
        return view('livewire.catalog-sidebar', [
            'categories' => Category::all(),
        ]);
    }
}
