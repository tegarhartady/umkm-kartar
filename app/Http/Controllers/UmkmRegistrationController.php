<?php

namespace App\Http\Controllers;

use App\Models\Umkm;
use Illuminate\Http\Request;

class UmkmRegistrationController extends Controller
{
    /**
     * Tampilkan form registrasi UMKM
     */
    public function showForm()
    {
        return view('umkm.register');
    }

    /**
     * Proses registrasi UMKM baru
     */
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'no_ktp' => 'required|string|size:16|unique:umkms,no_ktp',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:umkms,email',
            'nama_toko' => 'required|string|max:255|unique:umkms,nama_toko',
            'kategori' => 'required|string',
            'desa' => 'required|string|max:255',
            'lama_usaha' => 'required|string',
            'alamat' => 'required|string',
            'deskripsi' => 'required|string',
            'produk_utama' => 'required|string|max:255',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_tempat' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'agreement' => 'required|accepted',
        ], [
            'nama_pemilik.required' => 'Nama pemilik harus diisi',
            'no_ktp.required' => 'Nomor KTP harus diisi',
            'no_ktp.size' => 'Nomor KTP harus 16 digit',
            'no_ktp.unique' => 'Nomor KTP sudah terdaftar',
            'phone.required' => 'Nomor telepon harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'nama_toko.required' => 'Nama toko harus diisi',
            'nama_toko.unique' => 'Nama toko sudah terdaftar',
            'kategori.required' => 'Kategori harus dipilih',
            'desa.required' => 'Desa harus diisi',
            'lama_usaha.required' => 'Lama usaha harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'deskripsi.required' => 'Deskripsi UMKM harus diisi',
            'produk_utama.required' => 'Produk utama harus diisi',
            'foto_ktp.required' => 'Foto KTP harus diupload',
            'foto_ktp.image' => 'Foto KTP harus berupa gambar',
            'foto_ktp.mimes' => 'Foto KTP harus JPG atau PNG',
            'foto_ktp.max' => 'Ukuran foto KTP maksimal 2MB',
            'foto_tempat.required' => 'Foto tempat usaha harus diupload',
            'foto_tempat.image' => 'Foto tempat usaha harus berupa gambar',
            'foto_tempat.mimes' => 'Foto tempat usaha harus JPG atau PNG',
            'foto_tempat.max' => 'Ukuran foto tempat usaha maksimal 2MB',
            'agreement.required' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        try {
            // Upload foto KTP
            $fotoKtpPath = null;
            if ($request->hasFile('foto_ktp')) {
                $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');
            }

            // Upload foto tempat usaha
            $fotoTempatPath = null;
            if ($request->hasFile('foto_tempat')) {
                $fotoTempatPath = $request->file('foto_tempat')->store('tempat_usaha', 'public');
            }

            // Buat UMKM baru dengan status pending
            $umkm = Umkm::create([
                'pemilik' => $validated['nama_pemilik'],
                'no_ktp' => $validated['no_ktp'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'nama_toko' => $validated['nama_toko'],
                'kategori' => $validated['kategori'],
                'desa' => $validated['desa'],
                'lama_usaha' => $validated['lama_usaha'],
                'alamat' => $validated['alamat'],
                'deskripsi' => $validated['deskripsi'],
                'foto_ktp' => $fotoKtpPath,
                'foto_tempat' => $fotoTempatPath,
                'status' => 'pending', // ⭐ Status pending menunggu approval
                'password' => null, // Password akan di-generate oleh admin
            ]);

            return redirect('/')->with('success', 
                '✅ Pendaftaran berhasil! Data Anda sedang menunggu persetujuan admin. ' .
                'Anda akan menerima password via WhatsApp/Email dalam 1-2 hari kerja.');

        } catch (\Exception $e) {
            \Log::error('Error during UMKM registration: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 
                '❌ Terjadi kesalahan saat registrasi. Silakan coba lagi. ' .
                'Error: ' . $e->getMessage());
        }
    }
}
