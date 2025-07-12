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
    public $sortBy = 'featured';

    public $minPriceFormatted;
    public $maxPriceFormatted;

    protected $listeners = [
        'syncFromParams' => 'syncFromParams'
    ];

    public function mount()
    {
        // Sync dengan query parameters
        $this->selectedCategory = request('category', '');
        $this->minPrice = request('min_price', 0);
        $this->maxPrice = request('max_price', 999999999);
        $this->selectedRating = request('rating', '');
        $this->sortBy = request('sort', 'featured');

        $this->minPriceFormatted = $this->formatPrice($this->minPrice);
        $this->maxPriceFormatted = $this->formatPrice($this->maxPrice);
    }

    public function syncFromParams($data)
    {
        $this->selectedCategory = $data['category'];
        $this->minPrice = $data['minPrice'];
        $this->maxPrice = $data['maxPrice'];
        $this->selectedRating = $data['rating'];
        $this->sortBy = $data['sort'];

        $this->minPriceFormatted = $this->formatPrice($this->minPrice);
        $this->maxPriceFormatted = $this->formatPrice($this->maxPrice);
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

    public function resetFilters()
    {
        $this->selectedCategory = '';
        $this->minPrice = 0;
        $this->maxPrice = 999999999;
        $this->selectedRating = '';
        $this->sortBy = 'featured';

        $this->minPriceFormatted = $this->formatPrice($this->minPrice);
        $this->maxPriceFormatted = $this->formatPrice($this->maxPrice);

        $this->dispatch('filtersReset');
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

    public function resetSort()
    {
        $this->sortBy = 'featured';
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

        if ($property === 'sortBy') {
            $this->dispatch('sortBy-updated');
        }

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
