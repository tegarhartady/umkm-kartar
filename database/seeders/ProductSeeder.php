<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Umkm;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample UMKMs first
        $umkms = [
            [
                'nama_toko' => 'Toko Hasil Laut Teluknaga',
                'pemilik' => 'Budi Santoso',
                'email' => 'budilaut@email.com',
                'phone' => '08123456789',
                'desa' => 'Teluknaga',
                'alamat' => 'Jl. Pantai No. 123',
                'kategori' => 'Hasil Laut',
                'status' => 'disetujui',
            ],
            [
                'nama_toko' => 'Kerajinan Priuk',
                'pemilik' => 'Siti Nurhaliza',
                'email' => 'sitikerajinan@email.com',
                'phone' => '08134567890',
                'desa' => 'Priuk',
                'alamat' => 'Jl. Kerajinan No. 45',
                'kategori' => 'Kerajinan',
                'status' => 'disetujui',
            ],
            [
                'nama_toko' => 'Makanan Olahan Tangkil',
                'pemilik' => 'Ahmad Hidayat',
                'email' => 'ahmadmakanan@email.com',
                'phone' => '08145678901',
                'desa' => 'Tangkil Raya',
                'alamat' => 'Jl. Makanan No. 67',
                'kategori' => 'Makanan Olahan',
                'status' => 'disetujui',
            ],
            [
                'nama_toko' => 'Perkebunan Lestari',
                'pemilik' => 'Rina Wijaya',
                'email' => 'rinaperkebunan@email.com',
                'phone' => '08156789012',
                'desa' => 'Muara',
                'alamat' => 'Jl. Perkebunan No. 89',
                'kategori' => 'Hasil Perkebunan',
                'status' => 'disetujui',
            ],
        ];

        foreach ($umkms as $umkmData) {
            $umkm = Umkm::create($umkmData);

            // Create products for each UMKM
            $products = [
                [
                    'nama_produk' => 'Ikan Asin Premium',
                    'deskripsi' => 'Ikan asin berkualitas tinggi dari tangkapan terbaik',
                    'kategori' => 'Hasil Laut',
                    'harga' => 50000,
                    'stok' => 50,
                    'satuan' => 'kg',
                    'status' => 'aktif',
                ],
                [
                    'nama_produk' => 'Terasi Udang',
                    'deskripsi' => 'Terasi asli buatan rumahan',
                    'kategori' => 'Hasil Laut',
                    'harga' => 35000,
                    'stok' => 30,
                    'satuan' => 'pcs',
                    'status' => 'aktif',
                ],
                [
                    'nama_produk' => 'Tas Tenun Tangan',
                    'deskripsi' => 'Tas tradisional dengan motif lokal',
                    'kategori' => 'Kerajinan',
                    'harga' => 150000,
                    'stok' => 20,
                    'satuan' => 'pcs',
                    'status' => 'aktif',
                ],
                [
                    'nama_produk' => 'Kopi Robusta Lokal',
                    'deskripsi' => 'Kopi hasil perkebunan lokal',
                    'kategori' => 'Hasil Perkebunan',
                    'harga' => 80000,
                    'stok' => 40,
                    'satuan' => 'kg',
                    'status' => 'aktif',
                ],
                [
                    'nama_produk' => 'Dodol Nangka',
                    'deskripsi' => 'Makanan tradisional dengan rasa nikmat',
                    'kategori' => 'Makanan Olahan',
                    'harga' => 45000,
                    'stok' => 60,
                    'satuan' => 'box',
                    'status' => 'aktif',
                ],
            ];

            foreach ($products as $productData) {
                $productData['umkm_id'] = $umkm->id;
                Product::create($productData);
            }
        }
    }
}
