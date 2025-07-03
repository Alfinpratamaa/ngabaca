<?php

namespace App\Livewire;

use Livewire\Component;

class CartPage extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $shippingCost = 0; // Anda bisa atur ini secara dinamis nanti
    public $total = 0;

    // Listener untuk event 'cartUpdated'
    // Berguna jika ada komponen lain yang memicu perubahan keranjang
    protected $listeners = ['cartUpdated' => 'loadCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = session()->get('cart', []);
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->cartItems as $item) {
            $this->subtotal += $item['price'] * $item['quantity'];
        }
        // Contoh sederhana, Anda bisa menyesuaikan logika shippingCost
        $this->shippingCost = ($this->subtotal > 0) ? 20000 : 0; // Contoh: biaya kirim Rp 20.000 jika ada item
        $this->total = $this->subtotal + $this->shippingCost;
    }

    public function updateQuantity($bookId, $action)
    {
        if (isset($this->cartItems[$bookId])) {
            if ($action === 'increase') {
                $this->cartItems[$bookId]['quantity']++;
            } elseif ($action === 'decrease') {
                $this->cartItems[$bookId]['quantity']--;
                // Hapus item jika kuantitas kurang dari 1
                if ($this->cartItems[$bookId]['quantity'] < 1) {
                    $this->removeItem($bookId);
                    return; // Keluar dari fungsi setelah menghapus
                }
            }
            session()->put('cart', $this->cartItems); // Simpan perubahan ke session
            $this->calculateTotals(); // Hitung ulang total
            $this->dispatch('cartUpdated'); // Beri tahu komponen lain (misal CartCounter)
        }
    }

    public function removeItem($bookId)
    {
        if (isset($this->cartItems[$bookId])) {
            unset($this->cartItems[$bookId]); // Hapus item dari array
            session()->put('cart', $this->cartItems); // Simpan perubahan ke session
            $this->calculateTotals(); // Hitung ulang total

            // Beri tahu komponen lain (misal CartCounter) bahwa keranjang telah berubah
            $this->dispatch('cartUpdated');

            session()->flash('success', 'Buku berhasil dihapus dari keranjang.');
        }
    }

    // Metode untuk memformat harga agar terlihat rapi di tampilan
    public function formatPrice($value)
    {
        return 'Rp. ' . number_format($value, 0, ',', '.');
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
