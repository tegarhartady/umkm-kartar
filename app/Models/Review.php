<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Review extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function autoGenerateForCompletedTransactions($ignoreCache = false)
    {
        if (!$ignoreCache && Cache::has('auto_generate_reviews_ran')) {
            return 0;
        }

        if (!$ignoreCache) {
            Cache::put('auto_generate_reviews_ran', true, 3600); // 1 jam lock
        }

        $threeDaysAgo = now()->subDays(3);
        
        $transactions = Transaction::where('status', 'completed')
            ->where(function($q) use ($threeDaysAgo) {
                $q->where('completed_at', '<=', $threeDaysAgo)
                  ->orWhere(function($q2) use ($threeDaysAgo) {
                      $q2->whereNull('completed_at')->where('updated_at', '<=', $threeDaysAgo);
                  });
            })
            ->whereDoesntHave('review')
            ->whereNotNull('user_id')
            ->whereNotNull('product_id')
            ->limit(50)
            ->get();

        $count = 0;
        foreach ($transactions as $txn) {
            self::create([
                'transaction_id' => $txn->id,
                'user_id' => $txn->user_id,
                'product_id' => $txn->product_id,
                'rating' => 5,
                'comment' => 'Pesanan telah diselesaikan.',
            ]);
            $count++;
        }

        return $count;
    }
}
