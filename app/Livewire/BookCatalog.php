<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class BookCatalog extends Component
{
    use WithPagination;

    #[Url(as: 'category')]
    public $selectedCategory = '';

    #[Url(as: 'min_price')]
    public $minPrice = 0;

    #[Url(as: 'max_price')]
    public $maxPrice = 999999999;

    #[Url(as: 'rating')]
    public $selectedRating = '';

    #[Url(as: 'search')]
    public $search = '';

    #[Url(as: 'sort')]
    public $sortBy = 'featured';

    public $cartItems = [];

    protected $listeners = [
        'categoryChanged' => 'updateCategory',
        'priceChanged' => 'updatePrice',
        'ratingChanged' => 'updateRating',
        'filtersApplied' => 'applyFilters',
        'sortChanged' => 'updateSort',
        'cartUpdated' => 'loadCartItems',
    ];

    public function mount()
    {
        // Sync dengan query parameters
        $this->selectedCategory = request('category', '');
        $this->minPrice = request('min_price', 0);
        $this->maxPrice = request('max_price', 999999999);
        $this->selectedRating = request('rating', '');
        $this->search = request('search', '');
        $this->sortBy = request('sort', 'featured');

        $this->resetPage();
        $this->loadCartItems();

        // Dispatch ke sidebar untuk sync
        $this->dispatch('syncFromParams', [
            'category' => $this->selectedCategory,
            'minPrice' => $this->minPrice,
            'maxPrice' => $this->maxPrice,
            'rating' => $this->selectedRating,
            'search' => $this->search,
            'sort' => $this->sortBy,
        ]);
    }

    public function loadCartItems()
    {
        $this->cartItems = session()->get('cart', []);
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

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }

    public function updatedSelectedRating()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->resetPage();
        $this->dispatch('searchCleared');

        $currentParams = request()->query();
        unset($currentParams['search']);

        // Redirect ke URL tanpa search parameter
        return redirect()->route('catalog', $currentParams);
    }

    public function addToCart($bookId)
    {
        $book = Book::find($bookId);

        if (!$book) {
            session()->flash('error', 'Buku tidak ditemukan!');
            return;
        }

        $cart = session()->get('cart', []);

        // Periksa apakah buku sudah ada di keranjang
        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity']++; // Tambah kuantitas
        } else {
            // Tambahkan buku baru ke keranjang
            $cart[$bookId] = [
                "id" => $book->id, // Penting untuk menyimpan ID
                "title" => $book->title,
                "quantity" => 1,
                "price" => $book->price,
                "cover_image_url" => $book->cover_image_url ?? "/public/assets/images/cover-book-nofound.jpg", // Tambahkan gambar untuk tampilan keranjang
            ];
        }

        session()->put('cart', $cart); // Simpan keranjang ke sesi
        $this->loadCartItems(); // Reload cart items

        // Dispatch event ke komponen lain (CartCounter) untuk memperbarui tampilan keranjang
        $this->dispatch('cartUpdated');

        session()->flash('success', 'Buku berhasil ditambahkan ke keranjang!');
    }

    public function removeFromCart($bookId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$bookId])) {
            unset($cart[$bookId]);
            session()->put('cart', $cart);
            $this->loadCartItems();
            $this->dispatch('cartUpdated');
            session()->flash('success', 'Buku berhasil dihapus dari keranjang!');
        } else {
            session()->flash('error', 'Buku tidak ditemukan di keranjang!');
        }
    }

    public function increaseQuantity($bookId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity']++;
            session()->put('cart', $cart);
            $this->loadCartItems();
            $this->dispatch('cartUpdated');
        }
    }

    public function decreaseQuantity($bookId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity']--;

            // Remove item if quantity becomes 0
            if ($cart[$bookId]['quantity'] <= 0) {
                unset($cart[$bookId]);
                session()->flash('success', 'Buku berhasil dihapus dari keranjang!');
            }

            session()->put('cart', $cart);
            $this->loadCartItems();
            $this->dispatch('cartUpdated');
        }
    }

    public function render()
    {
        $query = Book::query();

        // Apply search filter
        if ($this->search) {
            $searchTerm = strtolower($this->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(title) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(author) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $searchTerm . '%']);
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
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('published_year', 'desc')
                    ->orderBy('created_at', 'desc');
                break;
            case 'bestseller':
                // Asumsi ada kolom sold_count atau bisa join dengan order items
                $query->orderByDesc('sold_count');
                break;
            case 'rating_high':
                $query->orderBy('rating', 'desc');
                break;
            case 'alphabetical':
                $query->orderBy('title', 'asc');
                break;
            default: // featured
                $query->orderBy('is_featured', 'desc')
                    ->orderBy('created_at', 'desc');
                break;
        }

        $books = $query->paginate(20);

        return view('livewire.book-catalog', [
            'books' => $books,
        ]);
    }
}
