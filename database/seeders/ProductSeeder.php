<?php

namespace Database\Seeders;

use App\Models\Umkm;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Hanya tambahkan produk ke UMKM yang sudah ada dari UmkmSeeder
        // Jangan buat UMKM baru di sini
        
        $umkms = Umkm::all();
        
        if ($umkms->count() === 0) {
            // Jika tidak ada UMKM, skip (UmkmSeeder akan membuat yang needed)
            return;
        }

        // Untuk setiap UMKM yang sudah ada, pastikan memiliki beberapa produk
        // Produk akan sudah dibuat oleh UmkmSeeder di method seedProductsForUmkm
        // Seeders ini hanya untuk tambahan jika diperlukan
    }
}
