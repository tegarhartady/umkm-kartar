<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = ['nama_satuan', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($unit) {
            $unit->slug = Str::slug($unit->nama_satuan);
        });
    }
}
