<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Umkm;

return new class extends Migration
{
    public function up(): void
    {
        // Insert test UMKM dengan password yang sudah ter-hash
        Umkm::updateOrCreate(
            ['email' => 'test@umkm.com'],
            [
                'nama_toko' => 'UMKM Test',
                'pemilik' => 'Test Pemilik',
                'no_ktp' => '1234567890123456',
                'phone' => '08123456789',
                'desa' => 'Teluknaga',
                'alamat' => 'Jl. Test No. 123',
                'kategori' => 'Makanan Olahan',
                'lama_usaha' => '1-3',
                'deskripsi' => 'UMKM Test untuk Login',
                'produk_utama' => 'Kerupuk Test',
                'password' => Hash::make('test1234'),  // Password: test1234
                'status' => 'disetujui',
                'foto_ktp' => null,
                'foto_tempat' => null,
            ]
        );
    }

    public function down(): void
    {
        Umkm::where('email', 'test@umkm.com')->delete();
    }
};