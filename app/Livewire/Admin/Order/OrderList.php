<?php

namespace App\Livewire\Admin\Order;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;


class OrderList extends Component
{
    protected $updatesQueryString = ['page'];
    use WithPagination;

    // Properti untuk menyimpan status asli dan yang sedang diubah
    public $originalStatuses = [];
    public $currentStatuses = [];
    public $hasChanges = false;

    // Hapus metode mount(), karena inisialisasi akan dilakukan di render()

    // Mapping status dari view ke database (sudah benar)
    private function mapStatusToDatabase($status)
    {
        return match ($status) {
            'Diproses' => 'diproses',
            'Terpenuhi' => 'terpenuhi',
            'Batal' => 'batal',
            default => strtolower($status)
        };
    }

    // Mapping status dari database ke view (sudah benar)
    private function mapStatusToView($status)
    {
        return match ($status) {
            'diproses' => 'Diproses',
            'terpenuhi' => 'Terpenuhi',
            'batal' => 'Batal',
            default => ucfirst($status)
        };
    }

    public function updateOrderStatus($orderId, $status)
    {
        $databaseStatus = $this->mapStatusToDatabase($status);
        $this->currentStatuses[$orderId] = $databaseStatus;
        $this->checkForChanges();

        // TAMBAHKAN INI: Kirim event ke browser dengan data yang relevan
        $newDisplayStatus = $this->mapStatusToView($databaseStatus);
        $this->dispatch('status-updated', orderId: $orderId, newStatus: $newDisplayStatus);
    }

    private function checkForChanges()
    {
        $this->hasChanges = false;
        // Cukup bandingkan array original dan yang sekarang
        foreach ($this->currentStatuses as $orderId => $currentStatus) {
            if (
                isset($this->originalStatuses[$orderId]) &&
                $this->originalStatuses[$orderId] !== $currentStatus
            ) {
                $this->hasChanges = true;
                return; // Keluar dari loop jika sudah ditemukan satu perubahan
            }
        }
    }

    public function saveChanges()
    {
        try {
            // Simpan tanpa reset pagination
            foreach ($this->currentStatuses as $orderId => $currentStatus) {
                if ($this->originalStatuses[$orderId] !== $currentStatus) {
                    $order = Order::find($orderId);
                    if ($order) {
                        $order->status = $currentStatus;
                        $order->save(); // tetap akan update updated_at
                        $order->refresh();
                        $this->originalStatuses[$orderId] = $currentStatus;
                    }
                }
            }

            $this->hasChanges = false;
            session()->flash('message', 'Berhasil menyimpan perubahan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }


    public function resetChanges()
    {
        $this->currentStatuses = $this->originalStatuses;
        $this->hasChanges = false;
        session()->flash('message', 'Perubahan dibatalkan.');

        // TAMBAHKAN INI: Kirim event reset ke browser
        $this->dispatch('changes-reset');
    }

    public function render()
    {
        // Ambil data pesanan dengan paginasi berdasarkan created_at terbaru
        $orders = Order::with('user')->orderBy('id', 'asc')->paginate(10);


        // **PERBAIKAN UTAMA DI SINI**
        // Inisialisasi status untuk setiap pesanan di halaman saat ini
        // jika belum ada di dalam state komponen.
        foreach ($orders as $order) {
            if (!isset($this->currentStatuses[$order->id])) {
                $this->originalStatuses[$order->id] = $order->status;
                $this->currentStatuses[$order->id] = $order->status;
            }
        }

        // Tambahkan properti dinamis ke setiap item pesanan untuk ditampilkan di view
        $orders->getCollection()->transform(function ($order) {
            // Ambil status terbaru dari state komponen
            $currentStatus = $this->currentStatuses[$order->id] ?? $order->status;

            // Tambahkan properti untuk tampilan (misal: 'Diproses')
            $order->display_status = $this->mapStatusToView($currentStatus);

            // Tambahkan properti untuk menandai jika ada perubahan yang belum disimpan
            $order->has_pending_changes = ($this->originalStatuses[$order->id] ?? $order->status) !== $currentStatus;

            return $order;
        });

        // Kirim data ke view
        return view('livewire.admin.order.order-list', [
            'orders' => $orders,
        ]);
    }
}
