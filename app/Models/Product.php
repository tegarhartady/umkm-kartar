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
        'harga',
        'stok',
        'satuan',
        'kategori',
        'foto',
        'status',
    ];

    protected $casts = [
        'harga' => 'float',
        'stok' => 'integer',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}
