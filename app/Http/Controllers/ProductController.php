<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Middleware untuk protect routes
     */
    public function __construct()
    {
        $this->middleware('auth:umkm');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $umkm = Auth::guard('umkm')->user();
        $products = $umkm->products()->latest()->paginate(10);

        return view('umkm.products.index', compact('products', 'umkm'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('umkm.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $umkm = Auth::guard('umkm')->user();

        // Handle foto upload
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('products', 'public');
        }

        $umkm->products()->create($validated);

        return redirect()
            ->route('umkm.products.index')
            ->with('success', '✅ Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);
        return view('umkm.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('umkm.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle foto update
        if ($request->hasFile('foto')) {
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }
            $validated['foto'] = $request->file('foto')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('umkm.products.index')
            ->with('success', '✅ Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return redirect()
            ->route('umkm.products.index')
            ->with('success', '✅ Produk berhasil dihapus!');
    }
}
