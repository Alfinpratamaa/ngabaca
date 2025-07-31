<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.order.index');
    }

    public function customerOrders(Request $request)
    {
        $userId = Auth::id();

        $query = Order::where('user_id', $userId)
            ->with(['orderItems.book', 'user', 'payment']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status !== 'all') {
            $status = $request->status;

            switch ($status) {
                case 'pending':
                    // Pesanan dengan status di order yang masih pending
                    $query->where('status', 'pending');
                    break;

                case 'diproses':
                    // Pesanan yang sedang diproses 
                    $query->where('status', 'diproses');
                    break;

                case 'dikirim':
                    // Pesanan yang sudah dikirim
                    $query->where('status', 'dikirim');
                    break;

                case 'selesai':
                    // Pesanan yang sudah selesai
                    $query->where('status', 'selesai');
                    break;

                default:
                    $query->where('status', $status);
                    break;
            }
        }

        // Filter berdasarkan pencarian
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('orderItems.book', function ($bookQuery) use ($searchTerm) {
                        $bookQuery->where('title', 'like', '%' . $searchTerm . '%')
                            ->orWhere('author', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Filter berdasarkan tanggal
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        // Hitung statistik dengan logika yang benar
        $allOrders = Order::where('user_id', $userId)
            ->with(['payment', 'orderItems.book'])
            ->get();

        Log::debug('Order Statuses:', $allOrders->toArray());

        $stats = [
            'total' => $allOrders->count(),
            'pending' => $allOrders->where('status', 'pending')->count(),
            'diproses' => $allOrders->where('status', 'diproses')->count(),
            'dikirim' => $allOrders->where('status', 'dikirim')->count(),
            'selesai' => $allOrders->where('status', 'selesai')->count(),
        ];

        // debug isi order status

        return view('orders.index', compact('orders', 'stats'));
    }

    public function show(string $id)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['orderItems.book', 'user', 'payment'])
            ->findOrFail($id);



        Log::debug('Order Details:', ['status' => $order->status]);

        return view('orders.show', compact('order'));
    }
}
