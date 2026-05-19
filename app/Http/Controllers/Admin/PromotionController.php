<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Umkm;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('umkms')->latest()->paginate(10);
        $umkms = Umkm::whereIn('status', ['approved', 'disetujui'])->get();
        return view('admin.promotions.index', compact('promotions', 'umkms'));
    }

    public function create()
    {
        $umkms = Umkm::whereIn('status', ['approved', 'disetujui'])->get();
        return view('admin.promotions.create', compact('umkms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
            'umkm_ids' => 'nullable|array',
            'umkm_ids.*' => 'exists:umkms,id',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            Promotion::where('is_active', true)->update(['is_active' => false]);
        }

        $promo = new Promotion([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'description' => $validated['description'],
            'button_text' => $validated['button_text'],
            'button_link' => $validated['button_link'],
        ]);
        $promo->is_active = $isActive;
        $promo->save();

        if (isset($validated['umkm_ids'])) {
            $promo->umkms()->sync($validated['umkm_ids']);
        }

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promotion $promotion)
    {
        $umkms = Umkm::whereIn('status', ['approved', 'disetujui'])->get();
        return view('admin.promotions.edit', compact('promotion', 'umkms'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'required|string|max:100',
            'button_link' => 'required|string|max:255',
            'umkm_ids' => 'nullable|array',
            'umkm_ids.*' => 'exists:umkms,id',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            Promotion::where('id', '!=', $promotion->id)->update(['is_active' => false]);
        }

        $promotion->fill([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'description' => $validated['description'],
            'button_text' => $validated['button_text'],
            'button_link' => $validated['button_link'],
        ]);
        $promotion->is_active = $isActive;
        $promotion->save();

        if (isset($validated['umkm_ids'])) {
            $promotion->umkms()->sync($validated['umkm_ids']);
        } else {
            $promotion->umkms()->detach();
        }

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
