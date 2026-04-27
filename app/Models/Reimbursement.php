<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reimbursement extends Model
{
    protected $fillable = [
        'umkm_id',
        'judul',
        'deskripsi',
        'jumlah',
        'kategori',
        'bukti_gambar',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship
    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}