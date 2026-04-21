<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    public function index()
    {
        $desas = Desa::withCount('umkms')->paginate(10);
        return view('admin.desa.index', compact('desas'));
    }

    public function create()
    {
        return view('admin.desa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // Set default jumlah_umkm
        $validated['jumlah_umkm'] = 0;

        Desa::create($validated);

        return redirect()->route('admin.desa.index')->with('success', 'Desa berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $desa = Desa::findOrFail($id);
        return view('admin.desa.edit', compact('desa'));
    }

    public function update(Request $request, $id)
    {
        $desa = Desa::findOrFail($id);
        
        $validated = $request->validate([
            'nama_desa' => 'required|string|unique:desas,nama_desa,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        $desa->update($validated);

        return redirect()->route('admin.desa.index')
            ->with('success', 'Desa berhasil diupdate');
    }

    public function destroy(Desa $desa)
    {
        $desa->delete();
        return redirect()->route('admin.desa.index')->with('success', 'Desa berhasil dihapus!');
    }
}
