<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartPage extends Component
{
    public $cartItems = [];
    public $selectedItems = [];

    public $selectedSubtotal = 0;
    public $selectedShippingCost = 0;
    public $selectedTotal = 0;

    protected $listeners = ['cartUpdated' => 'loadCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = session()->get('cart', []);
        $this->selectedItems = array_intersect($this->selectedItems, array_keys($this->cartItems));
        $this->calculateTotals();
    }

    public function updatedSelectedItems()
    {
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->selectedSubtotal = 0;
        foreach ($this->selectedItems as $selectedId) {
            if (isset($this->cartItems[$selectedId])) {
                $item = $this->cartItems[$selectedId];
                $this->selectedSubtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            }
        }

        $this->selectedShippingCost = ($this->selectedSubtotal > 0) ? 20000 : 0;
        $this->selectedTotal = $this->selectedSubtotal + $this->selectedShippingCost;
    }

    public function increaseQuantity($bookId)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$bookId])) {
            $cart[$bookId]['quantity']++;
            Session::put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function decreaseQuantity($bookId)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$bookId])) {
            if ($cart[$bookId]['quantity'] <= 1) {
                unset($cart[$bookId]);
                session()->flash('success', 'Buku berhasil dihapus dari keranjang.');
            } else {
                $cart[$bookId]['quantity']--;
            }
            Session::put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cartUpdated');
        }
    }

    public function removeItem($bookId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$bookId])) {
            unset($cart[$bookId]);
            session()->put('cart', $cart);
            $this->loadCart();
            $this->dispatch('cartUpdated');
            session()->flash('success', 'Buku berhasil dihapus dari keranjang.');
        }
    }

    public function proceedToCheckout()
    {
        if (empty($this->selectedItems)) {
            session()->flash('error', 'Silakan ssss pilih item yang ingin di-checkout terlebih dahulu.');
            return;
        }

        // PERBAIKAN 1: Gunakan metode 'only()' untuk memfilter koleksi berdasarkan kunci.
        $itemsToCheckout = collect($this->cartItems)
            ->only($this->selectedItems)
            ->toArray();

        session()->put('checkout_cart', $itemsToCheckout);

        if (Auth::check()) {
            // Jika sudah login, langsung ke checkout
            return $this->redirect('/checkout', navigate: true);
        } else {
            // Jika BELUM login:
            // 1. Simpan tujuan di session
            session(['after_login_redirect_to' => 'checkout']);

            // 2. Arahkan ke halaman login
            session()->flash('info', 'Anda harus login terlebih dahulu untuk melanjutkan ke checkout.');
            return redirect()->route('login');
        }

        // PERBAIKAN 2: Gunakan nama route yang benar, yaitu 'checkout.page'.
        return redirect()->route('checkout');
    }

    public function formatPrice($value)
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    public function render()
    {
        $this->calculateTotals();
        return view('livewire.cart-page');
    }
}
