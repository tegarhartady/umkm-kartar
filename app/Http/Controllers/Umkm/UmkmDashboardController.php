<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class UmkmDashboardController extends Controller
{
    public function index()
    {
        $umkm = Auth::guard('umkm')->user();
        $products = $umkm->products()->latest()->paginate(10);
        $totalProducts = $umkm->products()->count();
        $activeProducts = $umkm->products()->where('status', 'aktif')->count();
        
        // Fetch transactions for this UMKM
        $umkmProductIds = $umkm->products()->pluck('id');
        $recentTransactions = Transaction::whereIn('product_id', $umkmProductIds)
            ->with('product')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
            
        $totalTransaksi = Transaction::whereIn('product_id', $umkmProductIds)->count();
        $totalRevenue = Transaction::whereIn('product_id', $umkmProductIds)
            ->where('status', 'completed')
            ->sum('total_price');

        return view('umkm.dashboard', compact(
            'umkm', 
            'products', 
            'totalProducts', 
            'activeProducts', 
            'recentTransactions', 
            'totalTransaksi', 
            'totalRevenue'
        ));
    }
}
