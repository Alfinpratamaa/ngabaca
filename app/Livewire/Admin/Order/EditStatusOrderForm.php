<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;
use App\Models\Order;

class EditStatusOrderForm extends Component
{
    public $isOpen = false;
    public $order;
    public $status = 'Diproses';

    public $statusOptions = [
        'Diproses' => 'Diproses',
        'Terpenuhi' => 'Terpenuhi',
        'Batal' => 'Batal',
    ];

    protected $listeners = ['openEditModal'];

    public function openEditModal($orderId)
    {
        $this->order = Order::find($orderId);
        $this->status = $this->order->status ?? 'Diproses';
        $this->isOpen = true;
    }

    public function closeModal(){
        $this->isOpen = false;
        $this->reset(['order', 'status']);
    }

    public function updateStatus(){
        $this->validate([
            'status' => 'required|in:Diproses, Terpenuhi, Batal'
        ]);

        $this->order->update([
            'status' => $this->status
        ]);

        $this->closeModal();
        session()->flash('message', 'Status Order Berhasil Diperbarui');
        $this->dispatch('orderUpdated');
    }

    
    public function render()
    {
        return view('livewire.admin.order.edit-status-order-form');
    }
}
