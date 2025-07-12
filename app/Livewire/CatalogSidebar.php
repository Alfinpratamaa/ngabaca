<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CatalogSidebar extends Component
{
    public $selectedCategory = '';
    public $minPrice = 0;
    public $maxPrice = 999999999;
    public $selectedRating = '';
    public $sortBy = 'featured'; // Nilai default untuk sorting

    public $minPriceFormatted;
    public $maxPriceFormatted;

    public function mount()
    {
        // Pastikan sortBy selalu terisi dengan nilai default
        $this->sortBy = 'featured';

        $this->minPriceFormatted = $this->formatPrice($this->minPrice);
        $this->maxPriceFormatted = $this->formatPrice($this->maxPrice);

        // Emit filters pertama kali saat komponen dimuat
        $this->applyFilters();
    }

    private function formatPrice($value)
    {
        return number_format($value, 0, ',', '.');
    }

    private function parsePrice($formattedValue)
    {
        $cleanedValue = preg_replace('/[^0-9-]/', '', $formattedValue);
        return (int) $cleanedValue;
    }

    public function applyFilters()
    {
        // Event ini yang akan didengarkan oleh komponen daftar produk
        $this->dispatch('filtersApplied', [
            'category' => $this->selectedCategory,
            'price' => [
                'min' => $this->minPrice,
                'max' => $this->maxPrice,
            ],
            'rating' => $this->selectedRating,
            'sort' => $this->sortBy, // Nilai sorting dikirimkan di sini
        ]);
    }

    public function resetFilters()
    {
        // Reset semua filter ke nilai default
        $this->selectedCategory = '';
        $this->minPrice = 0;
        $this->maxPrice = 999999999;
        $this->selectedRating = '';
        $this->sortBy = 'featured';

        // Format ulang harga
        $this->minPriceFormatted = $this->formatPrice($this->minPrice);
        $this->maxPriceFormatted = $this->formatPrice($this->maxPrice);

        // Emit event untuk JavaScript
        $this->dispatch('filtersReset');

        // Terapkan filter setelah reset
        $this->applyFilters();
    }

    public function setPriceRange($min, $max)
    {
        $this->minPrice = (int) $min;
        $this->maxPrice = (int) $max;

        $this->minPriceFormatted = $this->formatPrice($this->minPrice);
        $this->maxPriceFormatted = $this->formatPrice($this->maxPrice);

        $this->applyFilters();
    }

    // Method khusus untuk mengatur sort dengan opsi reset
    public function setSortBy($value)
    {
        // Jika value yang sama diklik lagi, reset ke default
        if ($this->sortBy === $value && $value !== 'featured') {
            $this->sortBy = 'featured';
        } else {
            $this->sortBy = $value;
        }

        // Emit event untuk JavaScript
        $this->dispatch('sortBy-updated');
        $this->applyFilters();
    }

    // Method untuk reset sorting
    public function resetSort()
    {
        $this->sortBy = 'featured';

        // Emit event untuk JavaScript
        $this->dispatch('sortBy-updated');
        $this->applyFilters();

        $this->dispatch('sort-was-reset');
    }

    public function updated($property)
    {
        if ($property === 'minPriceFormatted') {
            $parsedValue = $this->parsePrice($this->minPriceFormatted);
            if ($this->minPrice !== $parsedValue) {
                $this->minPrice = $parsedValue;
                if ($this->minPrice > $this->maxPrice) {
                    $this->maxPrice = $this->minPrice;
                    $this->maxPriceFormatted = $this->formatPrice($this->maxPrice);
                }
            }
        } elseif ($property === 'maxPriceFormatted') {
            $parsedValue = $this->parsePrice($this->maxPriceFormatted);
            if ($this->maxPrice !== $parsedValue) {
                $this->maxPrice = $parsedValue;
                if ($this->maxPrice < $this->minPrice) {
                    $this->minPrice = $this->maxPrice;
                    $this->minPriceFormatted = $this->formatPrice($this->minPrice);
                }
            }
        }

        // Handle sortBy update
        if ($property === 'sortBy') {
            $this->dispatch('sortBy-updated');
        }

        // Auto-apply filters ketika properti berubah
        if (in_array($property, ['selectedCategory', 'selectedRating', 'sortBy', 'minPrice', 'maxPrice'])) {
            $this->applyFilters();
        }
    }

    public function render()
    {
        return view('livewire.catalog-sidebar', [
            'categories' => Category::all(),
        ]);
    }
}
