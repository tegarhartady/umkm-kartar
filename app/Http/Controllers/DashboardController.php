<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Umkm;
use App\Models\Product;

class DashboardController extends Controller
{
    public function admin()
    {
        // Fetch real data from database
        $umkmCount = Umkm::count();
        $umkmBaru = Umkm::where('created_at', '>=', now()->subDays(7))->count();
        $produkCount = Product::where('status', 'published')->count();
        $produkMenunggu = Product::where('status', 'pending')->count();
        
        // Hitung total omzet dari UMKM
        $totalOmzet = Umkm::sum('omzet_bulanan') / 1000000; // konversi ke juta
        
        // Ambil data peningkatan per desa menggunakan groupBy
        $peningkatanDesa = Umkm::select('desa')
            ->selectRaw('COUNT(*) as transaksi')
            ->selectRaw('COALESCE(SUM(omzet_bulanan), 0) as total_omzet')
            ->groupBy('desa')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'nama' => $item->desa,
                    'transaksi' => $item->transaksi,
                    'omzet' => ($item->total_omzet ?? 0) / 1000000
                ];
            })
            ->toArray();
        
        // Ambil UMKM yang menunggu verifikasi (status pending)
        $verifikasiPending = Umkm::where('status', 'pending')
            ->select('id', 'nama_toko', 'pemilik', 'desa', 'kategori', 'created_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_toko' => $item->nama_toko,
                    'pemilik' => $item->pemilik,
                    'desa' => $item->desa,
                    'kategori' => $item->kategori,
                    'tanggal_daftar' => $item->created_at->format('Y-m-d')
                ];
            })
            ->toArray();
        
        // Ambil produk terbaru
        $produkTerbaru = Product::select('id', 'nama_produk', 'kategori', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->toArray();
        
        $data = [
            'total_omzet' => round($totalOmzet, 1),
            'umkm_terdaftar' => $umkmCount,
            'umkm_baru' => $umkmBaru,
            'total_produk' => $produkCount,
            'menunggu_verifikasi' => $produkMenunggu,
            'peningkatan_desa' => $peningkatanDesa,
            'verifikasi_pending' => $verifikasiPending,
            'produk_terbaru' => $produkTerbaru
        ];
        
        return view('pages.dashboard_admin', compact('data'));
    }

    public function umkm()
    {
        $user = auth()->user();
        
        // Get UMKM data for this user
        $umkms = $user->umkms()->get();
        
        if ($umkms->isEmpty()) {
            // User belum punya UMKM
            return view('pages.dashboard_umkm', [
                'data' => null,
                'umkms' => [],
                'hasUmkm' => false
            ]);
        }
        
        // Get first UMKM for this user (usually 1 UMKM per user)
        $umkm = $umkms->first();
        
        // Calculate statistics for this UMKM
        $totalProducts = $umkm->products()->count();
        $activeProducts = $umkm->products()->where('status', 'published')->count();
        $pendingProducts = $umkm->products()->where('status', 'pending')->count();
        
        // Get total sales (from products)
        $totalSales = $umkm->products()
            ->sum(\DB::raw('stok * harga')); // This is an estimate; real implementation might use order data
        
        // Get recent products
        $recentProducts = $umkm->products()
            ->select('id', 'nama_produk', 'kategori', 'harga', 'stok', 'status', 'image', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->toArray();
        
        // Get low stock products
        $lowStockProducts = $umkm->products()
            ->where('stok', '<', 5)
            ->select('id', 'nama_produk', 'stok', 'harga')
            ->limit(5)
            ->get()
            ->toArray();
        
        // Calculate monthly growth (this is mock, in real app would use order data)
        $monthlyGrowth = rand(-10, 30); // Mock data
        $shopRating = round(rand(40, 50) / 10, 1); // Mock rating 4.0-5.0
        $totalOrders = rand(50, 300); // Mock orders
        
        $data = [
            'nama_toko' => $umkm->nama_toko,
            'kategori' => $umkm->kategori,
            'desa' => $umkm->desa,
            'total_produk' => $totalProducts,
            'produk_aktif' => $activeProducts,
            'produk_pending' => $pendingProducts,
            'total_sales' => $totalSales,
            'monthly_growth' => $monthlyGrowth,
            'shop_rating' => $shopRating,
            'total_orders' => $totalOrders,
            'recent_products' => $recentProducts,
            'low_stock_products' => $lowStockProducts,
            'umkm_id' => $umkm->id,
            'umkm_status' => $umkm->status,
        ];
        
        return view('pages.dashboard_umkm', [
            'data' => $data,
            'umkms' => $umkms,
            'umkm' => $umkm,
            'hasUmkm' => true
        ]);
    }
}

