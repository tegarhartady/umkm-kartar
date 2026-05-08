<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            Promotion::where('is_active', true)->update(['is_active' => false]);
        }

        $promo = new Promotion($validated);
        $promo->is_active = $isActive;
        $promo->save();

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            Promotion::where('id', '!=', $promotion->id)->update(['is_active' => false]);
        }

        $promotion->fill($validated);
        $promotion->is_active = $isActive;
        $promotion->save();

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil diupdate.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil dihapus.');
    }

    public function toggle(Promotion $promotion)
    {
        if (!$promotion->is_active) {
            Promotion::where('is_active', true)->update(['is_active' => false]);
            $promotion->is_active = true;
        } else {
            $promotion->is_active = false;
        }
        $promotion->save();
        
        return back()->with('success', 'Status promo berhasil diubah.');
    }
}
