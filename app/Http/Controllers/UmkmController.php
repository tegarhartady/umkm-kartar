<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UmkmController extends Controller
{
    public function index()
    {
        $umkms = Umkm::withCount('products')->paginate(10);
        return view('admin.umkm.index', compact('umkms'));
    }

    public function create()
    {
        return view('admin.umkm.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'email' => 'required|email|unique:umkms',
            'phone' => 'required|string|max:20',
            'desa' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'omzet_bulanan' => 'nullable|numeric',
        ]);

        $validated['status'] = 'pending';

        Umkm::create($validated);

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil ditambahkan!');
    }

    public function show(Umkm $umkm)
    {
        $umkm->load('products');
        return view('admin.umkm.show', compact('umkm'));
    }

    public function edit(Umkm $umkm)
    {
        return view('admin.umkm.edit', compact('umkm'));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'email' => 'required|email|unique:umkms,email,' . $umkm->id,
            'phone' => 'required|string|max:20',
            'desa' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'omzet_bulanan' => 'nullable|numeric',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $umkm->update($validated);

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil diperbarui!');
    }

    public function destroy(Umkm $umkm)
    {
        $umkm->delete();
        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil dihapus!');
    }

    /**
     * Approve UMKM dan generate password default
     */
    public function approve(Umkm $umkm)
    {
        // Generate password otomatis: umkm + 4 angka random
        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $defaultPassword = 'umkm' . $randomNumber;

        // Update UMKM: set password dan ubah status ke disetujui
        $umkm->update([
            'password' => \Illuminate\Support\Facades\Hash::make($defaultPassword),
            'status' => 'disetujui',
        ]);

        // Success message dengan password untuk admin copy
        $successMessage = "✅ UMKM '{$umkm->nama_toko}' telah disetujui!\n\n" .
                          "📧 Data Login UMKM:\n" .
                          "Email: {$umkm->email}\n" .
                          "Password: {$defaultPassword}\n\n" .
                          "💡 Tip: Copy password ini dan kirim ke UMKM via WhatsApp atau Email";

        return back()->with('success', $successMessage);
    }

    /**
     * Reject UMKM
     */
    public function reject(Umkm $umkm)
    {
        $umkm->update([
            'status' => 'ditolak',
        ]);

        return back()->with('success', "UMKM '{$umkm->nama_toko}' ditolak.");
    }

    /**
     * List UMKM pending untuk verifikasi
     */
    public function verifikasi()
    {
        $umkms = Umkm::where('status', 'pending')->paginate(15);
        return view('admin.umkm.verifikasi', compact('umkms'));
    }

    /**
     * Handle registration dari public form /daftar-umkm
     */
    public function registerFromPublic(Request $request)
    {
        // Debug upload files
        \Log::info('Files uploaded:', $request->allFiles());
        
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            \Log::info('Foto KTP details:', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension(),
                'valid' => $file->isValid(),
            ]);
        }

        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'no_ktp' => 'required|string|max:16',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:umkms,email',
            'alamat' => 'required|string',
            'nama_toko' => 'required|string|max:255|unique:umkms,nama_toko',
            'kategori' => 'required|string',
            'desa' => 'required|string',
            'deskripsi' => 'nullable|string',
            'lama_usaha' => 'nullable|string',
            'produk_utama' => 'required|string',
            'foto_ktp' => 'required|file|mimes:jpeg,jpg,png|max:2048',
            'foto_tempat' => 'required|file|mimes:jpeg,jpg,png|max:2048',
            'agreement' => 'required',
        ], [
            'foto_ktp.required' => 'Foto KTP harus diupload',
            'foto_ktp.file' => 'File foto KTP tidak valid',
            'foto_ktp.mimes' => 'Foto KTP harus berformat JPEG, JPG, atau PNG',
            'foto_ktp.max' => 'Ukuran foto KTP maksimal 2MB',
            'foto_tempat.required' => 'Foto tempat usaha harus diupload',
            'foto_tempat.file' => 'File foto tempat tidak valid',
            'foto_tempat.mimes' => 'Foto tempat harus berformat JPEG, JPG, atau PNG',
            'foto_tempat.max' => 'Ukuran foto tempat maksimal 2MB',
            'agreement.required' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        // Handle foto KTP upload
        $fotoKtpPath = null;
        if ($request->hasFile('foto_ktp')) {
            $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');
        }

        // Handle foto tempat upload
        $fotoTempatPath = null;
        if ($request->hasFile('foto_tempat')) {
            $fotoTempatPath = $request->file('foto_tempat')->store('tempat_usaha', 'public');
        }

        // Buat UMKM baru dengan status pending
        $umkm = Umkm::create([
            'pemilik' => $validated['nama_pemilik'],
            'nama_toko' => $validated['nama_toko'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'desa' => $validated['desa'],
            'alamat' => $validated['alamat'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'],
            'lama_usaha' => $validated['lama_usaha'],
            'produk_utama' => $validated['produk_utama'],
            'foto_ktp' => $fotoKtpPath,
            'foto_tempat' => $fotoTempatPath,
            'status' => 'pending',
            'no_ktp' => $validated['no_ktp'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil! Admin akan meninjau data Anda dalam 1-2 hari kerja.',
                'umkm_id' => $umkm->id
            ]);
        }

        return redirect('/daftar-umkm')->with('success', 
            'Pendaftaran berhasil! Silakan tunggu persetujuan dari admin. Email persetujuan akan dikirim ke ' . $validated['email']);
    }

    /**
     * Reset password UMKM
     */
    public function resetPassword(Umkm $umkm)
    {
        // Generate password baru
        $newPassword = 'umkm' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // Update password
        $umkm->update(['password' => \Illuminate\Support\Facades\Hash::make($newPassword)]);

        return back()->with([
            'success' => true,
            'message' => "Password UMKM '{$umkm->nama_toko}' telah di-reset!",
            'umkm_email' => $umkm->email,
            'umkm_password' => $newPassword,
        ]);
    }
}
