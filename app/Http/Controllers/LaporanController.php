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
        $fileName = 'data_umkm_' . date('Y-m-d') . '.csv';
        $umkms = Umkm::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID', 'Nama Toko', 'Pemilik', 'Desa', 'Kecamatan', 'Kategori', 'No Telp', 'Omzet Bulanan (Rp)', 'Status', 'Tanggal Daftar');

        $callback = function() use($umkms, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8 compatibility
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);

            foreach ($umkms as $umkm) {
                fputcsv($file, array(
                    $umkm->id,
                    $umkm->nama_toko,
                    $umkm->pemilik,
                    $umkm->desa,
                    $umkm->kecamatan,
                    $umkm->kategori,
                    $umkm->no_telp,
                    $umkm->omzet_bulanan,
                    $umkm->status,
                    $umkm->created_at ? $umkm->created_at->format('Y-m-d H:i') : ''
                ));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function transaksiDesa(Request $request)
    {
        $desaList = Umkm::select('desa')
            ->whereNotNull('desa')
            ->where('desa', '!=', '')
            ->distinct()
            ->orderBy('desa')
            ->pluck('desa');
            
        $selectedDesa = $request->get('desa');
        
        $query = \App\Models\Transaction::with(['umkm', 'product', 'user'])
            ->select('transactions.*')
            ->join('umkms', 'transactions.umkm_id', '=', 'umkms.id');

        if ($selectedDesa) {
            $query->where('umkms.desa', $selectedDesa);
        }

        $query->orderBy('umkms.desa', 'asc')->orderBy('transactions.created_at', 'desc');
        
        $transactions = $query->paginate(20)->appends($request->all());

        return view('admin.laporan.transaksi_desa', compact('desaList', 'selectedDesa', 'transactions'));
    }
}
