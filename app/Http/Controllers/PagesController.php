<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function daftarUmkm()
    {
        $desas = Desa::all();
        $banks = \App\Models\Bank::where('is_active', true)->orderBy('nama_bank')->get();
        $categories = ['Hasil Laut', 'Makanan Olahan', 'Bumbu Dapur', 'Kerajinan', 'Kuliner'];
        return view('pages.daftar-umkm', compact('desas', 'categories', 'banks'));
    }

    public function desaMitra()
    {
        return view('pages.desa-mitra');
    }

    public function csrPik2()
    {
        return view('pages.csr-pik2');
    }

    public function tentang()
    {
        return view('pages.tentang');
    }
}