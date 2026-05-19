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

    public function profile()
    {
        $umkm = Auth::guard('umkm')->user();
        return view('umkm.profile', compact('umkm'));
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        $umkm = Auth::guard('umkm')->user();

        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'deskripsi' => 'nullable|string',
            'foto_qris' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto_qris')) {
            if ($umkm->foto_qris) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($umkm->foto_qris);
            }
            $validated['foto_qris'] = $request->file('foto_qris')->store('umkm/qris', 'public');
        }

        $umkm->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
