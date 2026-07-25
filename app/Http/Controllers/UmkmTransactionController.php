<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UmkmTransactionController extends Controller
{
    public function index()
    {
        $umkm = Auth::guard('umkm')->user();
        
        $transactions = Transaction::where('umkm_id', $umkm->id)
            ->with(['product', 'items', 'review'])
            ->orderByDesc('created_at')
            ->paginate(15);
            
        // Get all related transactions for these checkout groups to show all products
        $checkoutCodes = $transactions->pluck('checkout_code')->filter()->unique();
        $groupData = [];
        $grandTotals = [];
        if ($checkoutCodes->count() > 0) {
            $related = Transaction::whereIn('checkout_code', $checkoutCodes)
                ->with(['product', 'items', 'umkm'])
                ->get()
                ->groupBy('checkout_code');
            
            foreach ($related as $code => $items) {
                $groupData[$code] = $items;
                $grandTotals[$code] = $items->sum('total_price');
            }
        }
            
        return view('umkm.transactions.index', compact('transactions', 'groupData', 'grandTotals'));
    }

    public function income(Request $request)
    {
        $umkm = Auth::guard('umkm')->user();
        $umkmProductIds = $umkm->products()->pluck('id');
        
        // Filter by month/year if provided
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        
        $query = Transaction::where('umkm_id', $umkm->id)
            ->where('status', 'completed')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);
            
        $transactions = $query->with(['product', 'items', 'review'])->orderBy('created_at', 'desc')->get();
        
        // Hitung total pendapatan dari harga * qty agar terpisah dari ongkir (jika ongkir masuk total_price)
        // Atau kita gunakan total_price sesuai dengan dashboard
        $totalIncome = $transactions->sum('total_price');
        $totalTransactions = $transactions->count();
        
        // Group by day for the chart
        $dailyIncome = [];
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        
        // Initialize all days to 0
        for($i=1; $i<=$daysInMonth; $i++) {
            $dailyIncome[str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
        }
        
        foreach ($transactions as $t) {
            $day = Carbon::parse($t->created_at)->format('d');
            $dailyIncome[$day] += $t->total_price;
        }
        
        return view('umkm.transactions.income', compact(
            'transactions', 
            'totalIncome', 
            'totalTransactions',
            'month',
            'year',
            'dailyIncome'
        ));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $umkm = Auth::guard('umkm')->user();
        
        // Ensure this transaction belongs to this UMKM
        if ($transaction->product->umkm_id !== $umkm->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|string',
            'delivery_status' => 'nullable|string',
        ]);

        // Logic pemotongan stok sekarang sudah ditangani secara otomatis
        // oleh model event di App\Models\Transaction (updating/created)

        $updateData = ['status' => $validated['status']];
        if (isset($validated['delivery_status'])) {
            $updateData['delivery_status'] = $validated['delivery_status'];
        }

        $transaction->update($updateData);

        return back()->with('success', 'Status transaksi berhasil diperbarui dan stok telah disesuaikan.');
    }

    public function uploadOrderPhoto(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $umkm = Auth::guard('umkm')->user();
        
        // Ensure this transaction belongs to this UMKM
        if ($transaction->product->umkm_id !== $umkm->id) {
            abort(403);
        }

        $request->validate([
            'order_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('order_photo')) {
            // Delete old photo if exists
            if ($transaction->order_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($transaction->order_photo);
            }

            $path = $request->file('order_photo')->store('order_photos', 'public');
            $transaction->update(['order_photo' => $path]);
        }

        return back()->with('success', 'Foto pesanan berhasil diunggah.');
    }
}
