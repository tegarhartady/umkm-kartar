<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UmkmController extends Controller
{
    public function index()
    {
        $umkms = Umkm::withCount('products')->paginate(10);
        return view('admin.umkm.index', compact('umkms'));
    }

    public function create()
    {
        $desas = Desa::all();
        return view('admin.umkm.create', compact('desas'));
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:pending,approved,rejected,disetujui,ditolak',
            'password' => 'required|string|min:8|confirmed',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_tempat' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'no_rekening' => 'nullable|string|max:20',
            'tipe_rekening' => 'nullable|string|max:50',
            'nama_pemilik_rekening' => 'nullable|string|max:255',
        ], [
            'latitude.numeric' => 'Latitude harus berupa angka',
            'latitude.between' => 'Latitude harus antara -90 hingga 90',
            'longitude.numeric' => 'Longitude harus berupa angka',
            'longitude.between' => 'Longitude harus antara -180 hingga 180',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Handle foto KTP upload
        if ($request->hasFile('foto_ktp')) {
            $validated['foto_ktp'] = $request->file('foto_ktp')->store('umkm/ktp', 'public');
        }

        // Handle foto Tempat upload
        if ($request->hasFile('foto_tempat')) {
            $validated['foto_tempat'] = $request->file('foto_tempat')->store('umkm/tempat', 'public');
        }

        Umkm::create($validated);

        return redirect()->route('admin.umkm.index')->with('success', 'UMKM berhasil ditambahkan!');
    }

    public function show(Umkm $umkm)
    {
        $umkm->load('products');
        return view('admin.umkm.show', compact('umkm'));
    }

    public function edit($id)
    {
        $umkm = Umkm::findOrFail($id);
        $desas = Desa::all();
        return view('admin.umkm.edit', compact('umkm', 'desas'));
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:pending,approved,rejected,disetujui,ditolak',
            'password' => 'nullable|string|min:8|confirmed',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_tempat' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'no_rekening' => 'nullable|string|max:20',
            'tipe_rekening' => 'nullable|string|max:50',
            'nama_pemilik_rekening' => 'nullable|string|max:255',
        ], [
            'latitude.numeric' => 'Latitude harus berupa angka',
            'latitude.between' => 'Latitude harus antara -90 hingga 90',
            'longitude.numeric' => 'Longitude harus berupa angka',
            'longitude.between' => 'Longitude harus antara -180 hingga 180',
        ]);

        // Handle password update if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle foto KTP upload
        if ($request->hasFile('foto_ktp')) {
            // Delete old file if exists
            if ($umkm->foto_ktp) {
                \Storage::disk('public')->delete($umkm->foto_ktp);
            }
            $validated['foto_ktp'] = $request->file('foto_ktp')->store('umkm/ktp', 'public');
        }

        // Handle foto tempat upload
        if ($request->hasFile('foto_tempat')) {
            // Delete old file if exists
            if ($umkm->foto_tempat) {
                Storage::disk('public')->delete($umkm->foto_tempat);
            }
            $validated['foto_tempat'] = $request->file('foto_tempat')->store('umkm/tempat', 'public');
        }

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
            'omzet_bulanan' => 'required|numeric|min:0',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'foto_ktp' => 'required|file|mimes:jpeg,jpg,png|max:2048',
            'foto_tempat' => 'required|file|mimes:jpeg,jpg,png|max:2048',
            'no_rekening' => 'nullable|string|max:20',
            'tipe_rekening' => 'nullable|string|max:50',
            'nama_pemilik_rekening' => 'nullable|string|max:255',
            'agreement' => 'required',
        ], [
            'omzet_bulanan.required' => 'Omzet bulanan harus diisi',
            'omzet_bulanan.numeric' => 'Omzet bulanan harus berupa angka',
            'omzet_bulanan.min' => 'Omzet bulanan minimal 0',
            'latitude.required' => 'Silakan pilih lokasi di peta',
            'latitude.numeric' => 'Latitude tidak valid',
            'latitude.between' => 'Latitude harus antara -90 hingga 90',
            'longitude.required' => 'Silakan pilih lokasi di peta',
            'longitude.numeric' => 'Longitude tidak valid',
            'longitude.between' => 'Longitude harus antara -180 hingga 180',
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
            'omzet_bulanan' => $validated['omzet_bulanan'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'foto_ktp' => $fotoKtpPath,
            'foto_tempat' => $fotoTempatPath,
            'status' => 'pending',
            'no_ktp' => $validated['no_ktp'],
            'no_rekening' => $validated['no_rekening'] ?? null,
            'tipe_rekening' => $validated['tipe_rekening'] ?? null,
            'nama_pemilik_rekening' => $validated['nama_pemilik_rekening'] ?? null,
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
    public function resetPassword($id)
    {
        $umkm = Umkm::findOrFail($id);
        $defaultPassword = 'password123';
        
        $umkm->update([
            'password' => bcrypt($defaultPassword)
        ]);
        
        return redirect()->route('admin.umkm.show', $umkm->id)
            ->with('success', 'Password berhasil direset ke: password123');
    }
}
