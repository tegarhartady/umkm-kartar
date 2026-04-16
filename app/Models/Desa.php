<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'deskripsi',
        'jumlah_umkm',
    ];

    public function umkms()
    {
        return $this->hasMany(Umkm::class, 'desa', 'nama_desa');
    }
}

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'icon',
    ];
}
