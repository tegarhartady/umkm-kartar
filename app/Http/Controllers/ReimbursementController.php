<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReimbursementController extends Controller
{
    // UMKM - List reimbursement milik UMKM
    public function index()
    {
        $umkm = Auth::guard('umkm')->user();
        $reimbursements = $umkm->reimbursements()->latest()->paginate(10);
        return view('umkm.reimbursement.index', compact('reimbursements'));
    }

    // UMKM - Create form
    public function create()
    {
        return view('umkm.reimbursement.create');
    }

    // UMKM - Store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'kategori' => 'required|string',
            'bukti_gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $umkm = Auth::guard('umkm')->user();

        // Handle file upload
        $buktiPath = null;
        if ($request->hasFile('bukti_gambar')) {
            $buktiPath = $request->file('bukti_gambar')->store('reimbursement', 'public');
        }

        // Create reimbursement
        $umkm->reimbursements()->create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'jumlah' => $validated['jumlah'],
            'kategori' => $validated['kategori'],
            'bukti_gambar' => $buktiPath,
            'status' => 'pending',
        ]);

        return redirect()->route('reimbursement.index')->with('success', 'Reimbursement berhasil diajukan!');
    }

    // UMKM & Admin - Show detail
    public function show(Reimbursement $reimbursement)
    {
        // Check authorization
        if (Auth::guard('umkm')->check()) {
            if ($reimbursement->umkm_id !== Auth::guard('umkm')->id()) {
                abort(403);
            }
        }

        return view('umkm.reimbursement.show', compact('reimbursement'));
    }

    // Admin - Approve
    public function approve(Reimbursement $reimbursement, Request $request)
    {
        $reimbursement->update([
            'status' => 'disetujui',
            'catatan_admin' => $request->input('catatan'),
        ]);

        return back()->with('success', 'Reimbursement disetujui!');
    }

    // Admin - Reject
    public function reject(Reimbursement $reimbursement, Request $request)
    {
        $reimbursement->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->input('catatan'),
        ]);

        return back()->with('success', 'Reimbursement ditolak!');
    }
}