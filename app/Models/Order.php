<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'product_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'quantity',
        'price',
        'total_amount',
        'status',
        'payment_status',
        'payment_token',
        'payment_data',
        'payment_at',
    ];

    protected $casts = [
        'payment_data' => 'array',
        'payment_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function generateOrderNumber()
    {
        return 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
}
