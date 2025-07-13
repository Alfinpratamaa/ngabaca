<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;

class OrderList extends Component
{

    use WithPagination;

    // Mapping status dari view ke database
    private function mapStatusToDatabase($status)
    {
        return match($status) {
            'Diproses' => 'diproses',
            'Terpenuhi' => 'terpenuhi',
            'Batal' => 'batal',
            default => strtolower($status)
        };
    }

    // Mapping status dari database ke view
    private function mapStatusToView($status)
    {
        return match($status) {
            'diproses' => 'Diproses',
            'terpenuhi' => 'Terpenuhi',
            'batal' => 'Batal',
            default => ucfirst($status)
        };
    }

    public function updateOrderStatus($orderId, $status)
    {
        try {
            $order = Order::findOrFail($orderId);

            // Convert status ke format database
            $databaseStatus = $this->mapStatusToDatabase($status);

            $order->update([
                'status' => $databaseStatus
            ]);

            session()->flash('message', 'Status order berhasil diperbarui menjadi ' . $status);

            // Refresh halaman untuk menampilkan perubahan
            $this->render();

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui status order');
        }
    }

    public function render()
    {
        $orders = Order::with('user')->paginate(10);

        // Map status untuk setiap order
        $orders->getCollection()->transform(function ($order) {
            $order->display_status = $this->mapStatusToView($order->status);
            return $order;
        });

        return view('livewire.admin.order.order-list', compact('orders'));
    }
}
