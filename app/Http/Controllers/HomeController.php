<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $recommendedProducts = Product::where('status', 'aktif')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->limit(4)
            ->get();
        return view('index', compact('recommendedProducts'));
    }

    public function showProduct($id)
    {
        $product = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->findOrFail($id);
            
        $umkm = $product->umkm;
        
        $relatedProducts = Product::where('umkm_id', $umkm->id)
            ->where('id', '!=', $id)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->limit(4)
            ->get();
        
        return view('beli', compact('product', 'umkm', 'relatedProducts'));
    }
}
