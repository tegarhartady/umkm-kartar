<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'product', 'items', 'umkm']);
        
        // Fetch all and group by checkout_code or transaction_code
        $allTransactions = $query->latest()->get();
        $groupedTransactions = $allTransactions->groupBy(function($item) {
            return $item->checkout_code ?: $item->transaction_code;
        });

        // Paginate the groups
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 15;
        $currentPageItems = $groupedTransactions->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems, 
            count($groupedTransactions), 
            $perPage, 
            $currentPage, 
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $users = User::all();

        return view('admin.transactions.create', compact('products', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'payment_method' => 'nullable|in:transfer,ewallet,cod',
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
            'buyer_address' => 'required|string',
            'buyer_city' => 'required|string|max:100',
            'buyer_postal_code' => 'nullable|string|max:10',
            'notes' => 'nullable|string',
        ]);

        $validated['transaction_code'] = 'TRX-' . date('YmdHis') . '-' . mt_rand(1000, 9999);
        $validated['total_price'] = $validated['price'] * $validated['quantity'];
        $validated['status'] = 'pending';

        Transaction::create($validated);

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'product', 'items.product', 'umkm'])->findOrFail($id);
        
        $groupTransactions = collect([$transaction]);
        if ($transaction->checkout_code) {
            $groupTransactions = Transaction::where('checkout_code', $transaction->checkout_code)
                ->with(['product', 'items.product', 'umkm'])
                ->get();
        }

        return view('admin.transactions.show', compact('transaction', 'groupTransactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $products = Product::all();
        $users = User::all();

        return view('admin.transactions.edit', compact('transaction', 'products', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($request->has('bulk_approve') && $transaction->checkout_code) {
            Transaction::where('checkout_code', $transaction->checkout_code)
                ->where('status', 'pending')
                ->update(['status' => 'paid', 'paid_at' => now()]);
            
            return redirect()->route('admin.transactions.index')
                ->with('success', 'Seluruh transaksi dalam grup checkout ini telah disetujui.');
        }

        $validated = $request->validate([
            'status' => 'required|string',
            'delivery_status' => 'nullable|string',
        ]);

        if ($validated['status'] === 'paid' && $transaction->status === 'pending') {
            $validated['paid_at'] = now();
        }

        if ($request->status === 'pending' || $request->status === 'failed') {
            $validated['paid_at'] = null;
            $validated['completed_at'] = null;
        }

        $transaction->update($validated);

        return redirect()->back()
            ->with('success', 'Transaksi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}
