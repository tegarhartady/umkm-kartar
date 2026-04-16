<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        // Statistik umum
        $totalUmkm = Umkm::count();
        $umkmAktif = Umkm::where('status', 'disetujui')->count();
        $umkmPending = Umkm::where('status', 'pending')->count();
        $totalProduk = Product::count();

        // Data per desa
        $dataPerDesa = Umkm::select('desa', DB::raw('count(*) as total'))
            ->where('status', 'disetujui')
            ->groupBy('desa')
            ->get();

        // Data per kategori produk
        $dataPerKategori = Product::select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->get();

        // UMKM terdaftar per bulan (6 bulan terakhir)
        $umkmPerBulan = Umkm::where('created_at', '>=', now()->subMonths(6))
            ->get()
            ->groupBy(function($date) {
                return $date->created_at->format('Y-m');
            })
            ->map(function($group) {
                return $group->count();
            })
            ->sortKeys()
            ->toArray();

        // Top UMKM dengan produk terbanyak
        $topUmkm = Umkm::withCount('products')
            ->where('status', 'disetujui')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.laporan.index', compact(
            'totalUmkm', 
            'umkmAktif', 
            'umkmPending',
            'totalProduk',
            'dataPerDesa',
            'dataPerKategori',
            'umkmPerBulan',
            'topUmkm'
        ));
    }

    public function exportPdf()
    {
        // Implementasi export PDF
        return back()->with('info', 'Fitur export PDF akan segera tersedia');
    }

    public function exportExcel()
    {
        // Implementasi export Excel
        return back()->with('info', 'Fitur export Excel akan segera tersedia');
    }
}
