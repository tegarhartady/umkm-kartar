<?php

namespace App\Http\Controllers\Umkm;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UmkmProductController extends Controller
{
    public function index()
    {
        $umkm = Auth::guard('umkm')->user();
        $products = $umkm->products()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(10);
            
        return view('umkm.products.index', compact('products'));
    }

    public function create()
    {
        return view('umkm.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string',
            'metode_pemesanan' => 'required|in:siap_jadi,po,keduanya',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_best_seller' => 'nullable',
        ]);

        $umkm = Auth::guard('umkm')->user();
        $validated['umkm_id'] = $umkm->id;
        $validated['status'] = 'aktif';
        $validated['is_best_seller'] = $request->has('is_best_seller');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('umkm.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);
        $umkm = Auth::guard('umkm')->user();
        
        if ($product->umkm_id !== $umkm->id) {
            abort(403);
        }

        return view('umkm.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $umkm = Auth::guard('umkm')->user();
        
        if ($product->umkm_id !== $umkm->id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string',
            'metode_pemesanan' => 'required|in:siap_jadi,po,keduanya',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:aktif,nonaktif',
            'is_best_seller' => 'nullable',
        ]);

        $validated['is_best_seller'] = $request->has('is_best_seller');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('umkm.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $umkm = Auth::guard('umkm')->user();
        
        if ($product->umkm_id !== $umkm->id) {
            abort(403);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('umkm.products.index')->with('success', 'Produk berhasil dihapus');
    }
}
