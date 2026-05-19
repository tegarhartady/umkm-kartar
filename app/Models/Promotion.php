<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'button_text',
        'button_link',
        'is_active',
    ];

    public function umkms()
    {
        return $this->belongsToMany(Umkm::class, 'promotion_umkm');
    }
}
