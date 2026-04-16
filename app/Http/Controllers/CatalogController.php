<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function indexProducts(Request $request)
    {
        // Base query - accept both 'published' and 'aktif' status
        $query = Product::with('umkm')
            ->whereIn('status', ['published', 'aktif']);

        // Search by product name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        // Filter by desa (through UMKM relationship)
        if ($request->filled('desa')) {
            $desa = $request->input('desa');
            $query->whereHas('umkm', function ($q) use ($desa) {
                $q->where('desa', $desa);
            });
        }

        // Filter by UMKM
        if ($request->filled('umkm_id')) {
            $query->where('umkm_id', $request->input('umkm_id'));
        }

        // Filter by stock (available only)
        if ($request->boolean('tersedia')) {
            $query->where('stok', '>', 0);
        }

        // Filter by price range
        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', $request->input('harga_min'));
        }
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->input('harga_max'));
        }

        // Sort options
        $sort = $request->input('sort', 'terbaru');
        switch ($sort) {
            case 'harga_rendah':
                $query->orderBy('harga', 'asc');
                break;
            case 'harga_tinggi':
                $query->orderBy('harga', 'desc');
                break;
            case 'terpopuler':
                $query->orderBy('views', 'desc');
                break;
            case 'terbaru':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginate
        $products = $query->paginate(12)->withQueryString();

        // Get distinct categories from database
        $categories = Product::whereIn('status', ['published', 'aktif'])
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->sort();

        // Get distinct desas from database
        $desas = \App\Models\Desa::whereHas('umkms.products', function ($q) {
            $q->whereIn('status', ['published', 'aktif']);
        })->get();

        // Get distinct UMKMs from database
        $umkms = \App\Models\Umkm::whereHas('products', function ($q) {
            $q->whereIn('status', ['published', 'aktif']);
        })->get();

        return view('pages.katalog', compact('products', 'categories', 'desas', 'umkms'));
    }

    public function index()
    {
        $products = Product::with('umkm')
            ->where('stok', '>', 0)
            ->paginate(12);
            
        return view('catalog.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('umkm');
        $relatedProducts = Product::with('umkm')
            ->where('kategori', $product->kategori)
            ->where('id', '!=', $product->id)
            ->where('stok', '>', 0)
            ->limit(4)
            ->get();
            
        return view('catalog.show', compact('product', 'relatedProducts'));
    }

    public function checkout(Product $product)
    {
        if ($product->stok <= 0) {
            return redirect()->back()->with('error', 'Produk ini sedang tidak tersedia.');
        }
        
        return view('catalog.checkout', compact('product'));
    }

    public function processOrder(Request $request, Product $product)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'quantity' => 'required|integer|min:1|max:' . $product->stok,
        ]);

        // Check stock again
        if ($product->stok < $validated['quantity']) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        // Create order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'product_id' => $product->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'quantity' => $validated['quantity'],
            'price' => $product->harga,
            'total_amount' => $product->harga * $validated['quantity'],
        ]);

        // Redirect to payment
        return redirect()->route('catalog.payment', $order->id);
    }

    public function payment(Order $order)
    {
        $order->load('product.umkm');
        return view('catalog.payment', compact('order'));
    }

    public function processPayment(Request $request, Order $order)
    {
        // Here you would integrate with actual payment gateway
        // For now, we'll simulate payment success
        
        $order->update([
            'status' => 'paid',
            'payment_status' => 'completed',
            'payment_at' => now(),
        ]);

        // Reduce product stock
        $order->product()->decrement('stok', $order->quantity);

        return redirect()->route('catalog.success', $order->id);
    }

    public function success(Order $order)
    {
        $order->load('product.umkm');
        return view('catalog.success', compact('order'));
    }
}
