<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    // ...existing methods...

    public function daftarUmkm()
    {
        $desas = Desa::all();
        $categories = ['Hasil Laut', 'Makanan Olahan', 'Bumbu Dapur', 'Kerajinan', 'Kuliner'];
        return view('pages.daftar-umkm', compact('desas', 'categories'));
    }

    // ...existing methods...
}