<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class Umkm extends Model implements AuthenticatableContract
{
    use Authenticatable;
    protected $table = 'umkms';

    protected $fillable = [
        'nama_toko',
        'pemilik',
        'email',
        'phone',
        'desa',
        'alamat',
        'kategori',
        'deskripsi',
        'password',
        'status',
        'omzet_bulanan',
        'foto_toko',
        'no_ktp',
        'lama_usaha',
        'foto_ktp',
        'foto_tempat',
        'produk_utama',
        'foto_qris',
        'latitude',
        'longitude',
        'no_rekening',
        'tipe_rekening',
        'nama_pemilik_rekening',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationship dengan products
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class);
    }

    // Relationship dengan reimbursements
    public function reimbursements()
    {
        return $this->hasMany(\App\Models\Reimbursement::class);
    }

    // Relationship dengan promotions
    public function promotions()
    {
        return $this->belongsToMany(\App\Models\Promotion::class, 'promotion_umkm');
    }
}
