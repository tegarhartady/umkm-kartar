<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'user_id',
        'product_id',
        'quantity',
        'price',
        'total_price',
        'delivery_fee',
        'status',
        'delivery_status',
        'payment_method',
        'order_type',
        'delivery_type',
        'buyer_name',
        'buyer_phone',
        'buyer_address',
        'buyer_city',
        'buyer_postal_code',
        'notes',
        'snap_token',
        'paid_at',
        'completed_at',
        'umkm_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function umkm()
    {
        return $this->product->umkm();
    }
}
