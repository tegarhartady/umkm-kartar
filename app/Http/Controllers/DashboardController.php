<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Umkm;
use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function admin()
    {
        \App\Models\Review::autoGenerateForCompletedTransactions();

        // Fetch real data from database
        $umkmCount = Umkm::count();
        $umkmBaru = Umkm::where('created_at', '>=', now()->subDays(7))->count();
        $produkCount = Product::where('status', 'published')->count();
        $produkMenunggu = Product::where('status', 'pending')->count();
        
        // Transaction statistics
        $totalTransaksi = Transaction::count();
        $transaksiBerhasil = Transaction::where('status', 'completed')->count();
        $totalRevenue = Transaction::where('status', 'completed')->sum('total_price');
        
        // Hitung total omzet dari UMKM
        $totalOmzet = Umkm::sum('omzet_bulanan') / 1000000; // konversi ke juta
        
        // Ambil data peningkatan per desa dari transaksi aktual 30 hari terakhir
        $peningkatanDesa = \App\Models\Transaction::join('umkms', 'transactions.umkm_id', '=', 'umkms.id')
            ->where('transactions.status', 'completed')
            ->where('transactions.created_at', '>=', now()->subDays(30))
            ->select('umkms.desa')
            ->selectRaw('COUNT(transactions.id) as transaksi')
            ->selectRaw('COALESCE(SUM(transactions.total_price), 0) as total_omzet')
            ->groupBy('umkms.desa')
            ->orderByRaw('total_omzet DESC')
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
        
        // Ambil transaksi terbaru
        $transaksiTerbaru = Transaction::with(['product', 'user'])
            ->select('id', 'transaction_code', 'product_id', 'user_id', 'buyer_name', 'status', 'total_price', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        
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
            'total_transaksi' => $totalTransaksi,
            'transaksi_berhasil' => $transaksiBerhasil,
            'total_revenue' => $totalRevenue,
            'peningkatan_desa' => $peningkatanDesa,
            'verifikasi_pending' => $verifikasiPending,
            'transaksi_terbaru' => $transaksiTerbaru,
            'produk_terbaru' => $produkTerbaru
        ];
        
        return view('pages.dashboard_admin', compact('data'));
    }

    public function umkm()
    {
        \App\Models\Review::autoGenerateForCompletedTransactions();

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
        
        // Get transactions for this UMKM's products
        $umkmProductIds = $umkm->products()->pluck('id');
        $totalTransaksi = Transaction::whereIn('product_id', $umkmProductIds)->count();
        $transaksiSelesai = Transaction::whereIn('product_id', $umkmProductIds)->where('status', 'completed')->count();
        $totalRevenue = Transaction::whereIn('product_id', $umkmProductIds)->where('status', 'completed')->sum('total_price');
        
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
        
        // Get recent transactions for this UMKM
        $transaksiTerbaru = Transaction::whereIn('product_id', $umkmProductIds)
            ->with(['product', 'user'])
            ->select('id', 'transaction_code', 'product_id', 'buyer_name', 'status', 'total_price', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        
        // Calculate monthly growth (this is mock, in real app would use order data)
        $monthlyGrowth = rand(-10, 30); // Mock data
        $shopRating = round(rand(40, 50) / 10, 1); // Mock rating 4.0-5.0
        
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
            'total_transaksi' => $totalTransaksi,
            'transaksi_selesai' => $transaksiSelesai,
            'total_revenue' => $totalRevenue,
            'recent_products' => $recentProducts,
            'low_stock_products' => $lowStockProducts,
            'transaksi_terbaru' => $transaksiTerbaru,
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

