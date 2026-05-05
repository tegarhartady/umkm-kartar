<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('units')) {
            return "Error: Tabel 'units' belum ada di database. Silakan jalankan /run-migrate terlebih dahulu.";
        }
        
        $units = Unit::orderBy('id', 'desc')->paginate(10);
        return view('admin.master.units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255|unique:units,nama_satuan',
        ]);

        Unit::create([
            'nama_satuan' => $request->nama_satuan,
            'slug' => Str::slug($request->nama_satuan),
        ]);

        return back()->with('success', 'Satuan berhasil ditambahkan!');
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'nama_satuan' => 'required|string|max:255|unique:units,nama_satuan,' . $unit->id,
        ]);

        $unit->update([
            'nama_satuan' => $request->nama_satuan,
            'slug' => Str::slug($request->nama_satuan),
        ]);

        return back()->with('success', 'Satuan berhasil diperbarui!');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return back()->with('success', 'Satuan berhasil dihapus!');
    }
}
