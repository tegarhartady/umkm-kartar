<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'umkm_id',
        'nama_produk',
        'deskripsi',
        'kategori',
        'satuan',
        'metode_pemesanan',
        'harga',
        'stok',
        'image',
        'status',
        'is_best_seller',
    ];

    protected $casts = [
        'harga' => 'float',
        'stok' => 'integer',
        'is_best_seller' => 'boolean',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'product_id');
    }
}
