<?php

namespace Database\Seeders;

use App\Models\Umkm;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UmkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Jalankan dengan: php artisan db:seed --class=UmkmSeeder
     */
    public function run(): void
    {
        // Buat 3 UMKM test dengan status disetujui
        $umkms = [
            [
                'nama_toko' => 'Keripik Tempe Enak',
                'pemilik' => 'Ibu Siti',
                'email' => 'umkm1@test.com',
                'phone' => '081234567890',
                'desa' => 'Teluknaga',
                'alamat' => 'Jl. Raya Teluknaga No. 123',
                'kategori' => 'Makanan',
                'deskripsi' => 'Keripik tempe pedas dengan bumbu tradisional',
                'status' => 'disetujui',
                'password' => Hash::make('umkm1234'),
            ],
            [
                'nama_toko' => 'Batik Tulis Teluknaga',
                'pemilik' => 'Bapak Ahmad',
                'email' => 'umkm2@test.com',
                'phone' => '081987654321',
                'desa' => 'Teluknaga',
                'alamat' => 'Jl. Merdeka No. 45',
                'kategori' => 'Kerajinan',
                'deskripsi' => 'Batik tulis asli dengan motif tradisional',
                'status' => 'disetujui',
                'password' => Hash::make('umkm5678'),
            ],
            [
                'nama_toko' => 'Jamu Tradisional Nusantara',
                'pemilik' => 'Ibu Rina',
                'email' => 'umkm3@test.com',
                'phone' => '082222222222',
                'desa' => 'Teluknaga',
                'alamat' => 'Jl. Gatot Subroto No. 78',
                'kategori' => 'Minuman',
                'deskripsi' => 'Jamu tradisional dengan bahan-bahan alami pilihan',
                'status' => 'disetujui',
                'password' => Hash::make('umkm9999'),
            ],
        ];

        foreach ($umkms as $umkmData) {
            // Cek apakah UMKM sudah ada berdasarkan email
            $existing = Umkm::where('email', $umkmData['email'])->first();
            
            if ($existing) {
                $this->command->line("⏭️  Skipping: {$umkmData['email']} (already exists)");
                $umkm = $existing;
            } else {
                $umkm = Umkm::create($umkmData);
                $this->command->line("✅ Created: {$umkmData['email']}");
            }

            // Tambah produk untuk setiap UMKM
            // Cek apakah sudah ada produk
            if ($umkm->products()->count() === 0) {
                Product::create([
                    'umkm_id' => $umkm->id,
                    'nama_produk' => 'Produk ' . $umkm->nama_toko . ' #1',
                    'deskripsi' => 'Deskripsi produk pertama dari ' . $umkm->pemilik,
                    'harga' => 15000,
                    'stok' => 50,
                    'satuan' => 'pcs',
                    'kategori' => $umkm->kategori,
                    'status' => 'aktif',
                ]);

                Product::create([
                    'umkm_id' => $umkm->id,
                    'nama_produk' => 'Produk ' . $umkm->nama_toko . ' #2',
                    'deskripsi' => 'Deskripsi produk kedua dari ' . $umkm->pemilik,
                    'harga' => 25000,
                    'stok' => 30,
                    'satuan' => 'pcs',
                    'kategori' => $umkm->kategori,
                    'status' => 'pending',
                ]);
                
                $this->command->line("  └─ Added 2 products for {$umkm->nama_toko}");
            }
        }

        $this->command->info('✅ UmkmSeeder berhasil dijalankan!');
        $this->command->info('');
        $this->command->info('📧 Login Data untuk Testing:');
        $this->command->info('Email: umkm1@test.com | Password: umkm1234');
        $this->command->info('Email: umkm2@test.com | Password: umkm5678');
        $this->command->info('Email: umkm3@test.com | Password: umkm9999');
    }
}
