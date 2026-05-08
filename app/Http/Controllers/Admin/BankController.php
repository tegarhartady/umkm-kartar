<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::latest()->paginate(10);
        return view('admin.banks.index', compact('banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'kode_bank' => 'nullable|string|max:50',
        ]);

        Bank::create([
            'nama_bank' => $request->nama_bank,
            'kode_bank' => $request->kode_bank,
            'is_active' => true,
        ]);

        return back()->with('success', 'Bank berhasil ditambahkan.');
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
            'kode_bank' => 'nullable|string|max:50',
        ]);

        $bank->update([
            'nama_bank' => $request->nama_bank,
            'kode_bank' => $request->kode_bank,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Bank berhasil diperbarui.');
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();
        return back()->with('success', 'Bank berhasil dihapus.');
    }

    public function toggle(Bank $bank)
    {
        $bank->is_active = !$bank->is_active;
        $bank->save();
        return back()->with('success', 'Status bank berhasil diubah.');
    }
}
