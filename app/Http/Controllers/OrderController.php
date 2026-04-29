<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Setting;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer orders.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = Transaction::with('product.umkm')
            ->where('user_id', auth()->id())
            ->latest();

        if ($status) {
            if ($status === 'pending') {
                $query->where('status', 'pending');
            } elseif ($status === 'proses') {
                $query->where('status', 'proses');
            } elseif ($status === 'packing') {
                $query->where('status', 'ready');
            } elseif ($status === 'pengiriman') {
                $query->where('status', 'shipping');
            } elseif ($status === 'delivered') {
                $query->where('status', 'delivered');
            } elseif ($status === 'selesai') {
                $query->where('status', 'completed');
            } elseif ($status === 'gagal' || $status === 'failed') {
                $query->whereIn('status', ['failed', 'cancelled']);
            }
        }

        $transactions = $query->paginate(10);
        $settings = Setting::whereIn('group', ['payment'])->get()->keyBy('key');

        return view('orders.index', compact('transactions', 'settings'));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['product.umkm', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('orders.show', compact('transaction'));
    }
}
