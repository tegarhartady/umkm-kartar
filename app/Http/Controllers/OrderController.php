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
        
        $query = Transaction::with(['product.umkm', 'items.product', 'umkm'])
            ->where('user_id', auth()->id());

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

        // Get all transactions for the user
        $allTransactions = $query->latest()->get();
        
        // Group by checkout_code or transaction_code if checkout_code is null
        $groupedTransactions = $allTransactions->groupBy(function($item) {
            return $item->checkout_code ?: $item->transaction_code;
        });

        // Paginate the grouped results
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 10;
        $currentPageItems = $groupedTransactions->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems, 
            count($groupedTransactions), 
            $perPage, 
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        $settings = Setting::whereIn('group', ['payment'])->get()->keyBy('key');

        return view('orders.index', compact('transactions', 'settings'));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['product.umkm', 'user', 'items.product', 'umkm'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        $transactions = collect([$transaction]);
        if ($transaction->checkout_code) {
            $transactions = Transaction::with(['product.umkm', 'items.product', 'umkm'])
                ->where('checkout_code', $transaction->checkout_code)
                ->get();
        }
            
        return view('orders.show', compact('transaction', 'transactions'));
    }
}
