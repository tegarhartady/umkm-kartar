<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Umkm;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource for admin.
     */
    public function index()
    {
        $products = Product::with(['umkm'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(15);
            
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource for admin.
     */
    public function create()
    {
        $umkms = Umkm::where('status', 'disetujui')->get();
        $categories = Category::orderBy('nama_kategori')->get();
        $units = Unit::orderBy('nama_satuan')->get();
        return view('admin.products.create', compact('umkms', 'categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'umkm_id' => 'required|exists:umkms,id',
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'satuan' => 'required|string',
            'metode_pemesanan' => 'required|in:siap_jadi,po,keduanya',
            'deskripsi' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $validated['status'] = 'aktif';
        
        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource for admin.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource for admin.
     */
    public function edit($id)
    {
        $product = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);
        $umkms = Umkm::where('status', 'disetujui')->get();
        $categories = Category::orderBy('nama_kategori')->get();
        $units = Unit::orderBy('nama_satuan')->get();
        return view('admin.products.edit', compact('product', 'umkms', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kategori' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', '✅ Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', '✅ Produk berhasil dihapus!');
    }

    /**
     * Display products moderation page.
     */
    public function moderasi()
    {
        $products = Product::with(['umkm'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(15);
            
        return view('admin.products.moderasi', compact('products'));
    }

    /**
     * Update product status.
     */
    public function updateStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'status' => $request->status
        ]);

        return back()->with('success', "Status produk '{$product->nama_produk}' berhasil diperbarui ke {$request->status}.");
    }
}
