<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

return new class extends Migration
{
    public function up(): void
    {
        // Add categories
        $categories = [
            ['nama_kategori' => 'Makanan Olahan', 'icon' => 'bi-cup-hot'],
            ['nama_kategori' => 'Kerajinan', 'icon' => 'bi-palette'],
            ['nama_kategori' => 'Hasil Laut', 'icon' => 'bi-water'],
            ['nama_kategori' => 'Hasil Perkebunan', 'icon' => 'bi-flower1'],
            ['nama_kategori' => 'Fashion', 'icon' => 'bi-bag'],
            ['nama_kategori' => 'Lainnya', 'icon' => 'bi-box'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['nama_kategori' => $cat['nama_kategori']],
                ['icon' => $cat['icon']]
            );
        }
    }

    public function down(): void
    {
        // Skip
    }
};