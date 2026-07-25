<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        \App\Models\Review::autoGenerateForCompletedTransactions();

        $recommendedProducts = Product::where('status', 'aktif')
            ->withAvg('reviews', 'rating')
            ->orderByDesc('is_best_seller')
            ->orderByDesc('reviews_avg_rating')
            ->limit(4)
            ->get();
        $testimonials = Testimonial::where('is_active', true)->latest()->get();
        
        $activePromotion = null;
        try {
            $activePromotion = \App\Models\Promotion::where('is_active', true)->first();
        } catch (\Exception $e) {
            // Table might not exist yet
            \Log::warning('Promotions table missing: ' . $e->getMessage());
        }
        
        return view('index', compact('recommendedProducts', 'testimonials', 'activePromotion'));
    }

    public function showProduct($id)
    {
        $product = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->with(['reviews' => function($q) {
                $q->with('user')->latest();
            }])
            ->withSum(['transactions' => function ($query) {
                $query->whereIn('status', ['completed']);
            }], 'quantity')
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
