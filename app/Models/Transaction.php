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
        'po_date',
        'delivery_type',
        'buyer_name',
        'buyer_phone',
        'buyer_address',
        'buyer_city',
        'buyer_postal_code',
        'notes',
        'snap_token',
        'payment_proof',
        'order_photo',
        'paid_at',
        'completed_at',
        'umkm_id',
        'checkout_code',
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
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $activeStatuses = ['paid', 'proses', 'ready', 'shipping', 'completed'];
            if (in_array($transaction->status, $activeStatuses)) {
                $transaction->decrementProductStock();
            }
        });

        static::updating(function ($transaction) {
            if ($transaction->isDirty('status')) {
                $oldStatus = $transaction->getOriginal('status');
                $newStatus = $transaction->status;

                $activeStatuses = ['paid', 'proses', 'ready', 'shipping', 'completed'];
                $inactiveStatuses = ['pending', 'cancelled', 'failed'];

                // Jika berubah dari inactive (misal pending) ke active (misal paid/proses/completed)
                if (in_array($oldStatus, $inactiveStatuses) && in_array($newStatus, $activeStatuses)) {
                    $transaction->decrementProductStock();
                }

                // Jika berubah dari active ke inactive (misal dibatalkan setelah bayar/proses)
                if (in_array($oldStatus, $activeStatuses) && in_array($newStatus, $inactiveStatuses)) {
                    $transaction->incrementProductStock();
                }
            }
        });
    }

    public function decrementProductStock()
    {
        if ($this->items()->count() > 0) {
            foreach ($this->items as $item) {
                $product = $item->product;
                if ($product && $product->stok !== null) {
                    $newStock = max(0, $product->stok - $item->quantity);
                    $product->update(['stok' => $newStock]);
                }
            }
        } elseif ($this->product && $this->product->stok !== null) {
            $product = $this->product;
            $newStock = max(0, $product->stok - $this->quantity);
            $product->update(['stok' => $newStock]);
        }
    }

    public function incrementProductStock()
    {
        if ($this->items()->count() > 0) {
            foreach ($this->items as $item) {
                $product = $item->product;
                if ($product && $product->stok !== null) {
                    $product->increment('stok', $item->quantity);
                }
            }
        } elseif ($this->product && $this->product->stok !== null) {
            $this->product->increment('stok', $this->quantity);
        }
    }
}
