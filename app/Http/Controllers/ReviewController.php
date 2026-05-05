<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Transaction;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $transaction = Transaction::findOrFail($validated['transaction_id']);

        // Pastikan transaksi milik user dan statusnya sudah completed
        if ($transaction->user_id !== auth()->id() || $transaction->status !== 'completed') {
            return back()->with('error', 'Anda tidak dapat memberikan ulasan untuk pesanan ini.');
        }

        // Cek apakah sudah pernah review
        if ($transaction->review) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        Review::create([
            'transaction_id' => $transaction->id,
            'user_id' => auth()->id(),
            'product_id' => $transaction->product_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
