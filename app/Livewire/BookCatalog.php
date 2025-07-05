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
    public $maxPrice = 999999999;
    public $selectedRating = '';
    public $search = '';
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
        $this->resetPage();
        $this->loadCartItems();
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
                "name" => $book->title,
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
