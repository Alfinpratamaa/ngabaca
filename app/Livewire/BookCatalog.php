<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;

class BookCatalog extends Component
{
    use WithPagination;

    public $selectedCategory = '';
    public $minPrice = 0;
    public $maxPrice = 500000;
    public $selectedRating = '';
    public $search = '';
    public $sortBy = 'featured';

    protected $listeners = [
        'categoryChanged' => 'updateCategory',
        'priceChanged' => 'updatePrice',
        'ratingChanged' => 'updateRating',
        'filtersApplied' => 'applyFilters',
        'sortChanged' => 'updateSort',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updateCategory($category)
    {
        $this->selectedCategory = $category;
        $this->resetPage();
    }

    public function updatePrice($price)
    {
        $this->minPrice = $price['min'];
        $this->maxPrice = $price['max'];
        $this->resetPage();
    }

    public function updateRating($rating)
    {
        $this->selectedRating = $rating;
        $this->resetPage();
    }

    public function updateSort($sort)
    {
        $this->sortBy = $sort;
        $this->resetPage();
    }

    public function applyFilters($filters)
    {
        $this->selectedCategory = $filters['category'];
        $this->minPrice = $filters['price']['min'];
        $this->maxPrice = $filters['price']['max'];
        $this->selectedRating = $filters['rating'];
        $this->sortBy = $filters['sort'] ?? 'featured';
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Book::query();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('author', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply category filter
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // Apply price filter
        $query->whereBetween('price', [$this->minPrice, $this->maxPrice]);

        // Apply rating filter
        if ($this->selectedRating) {
            $query->where('rating', '>=', floatval($this->selectedRating));
        }

        // Apply sort
        if ($this->sortBy === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($this->sortBy === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($this->sortBy === 'newest') {
            $query->orderBy('published_year', 'desc');
        } else {
            // featured or default
            $query->orderBy('created_at', 'desc');
        }

        // Get books with pagination
        $books = $query->paginate(20);

        return view('livewire.book-catalog', [
            'books' => $books,
        ]);
    }
}
