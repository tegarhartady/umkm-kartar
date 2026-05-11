<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function callback(Request $request)
    {
        if (!class_exists('\Midtrans\Config')) {
            return response()->json(['message' => 'Midtrans library not found'], 500);
        }

        \Midtrans\Config::$serverKey = Setting::where('key', 'midtrans_server_key')->first()?->value ?? config('midtrans.server_key');
        \Midtrans\Config::$isProduction = (Setting::where('key', 'midtrans_is_production')->first()?->value ?? '0') == '1';
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $notification = new \Midtrans\Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status;

            $transactions = Transaction::where('transaction_code', $orderId)->get();
            if ($transactions->isEmpty()) {
                $transactions = Transaction::where('checkout_code', $orderId)->get();
            }

            if ($transactions->isEmpty()) {
                return response()->json(['message' => 'Transaction(s) not found'], 404);
            }

            foreach ($transactions as $transaction) {
                if ($transactionStatus == 'capture') {
                    if ($paymentType == 'credit_card') {
                        if ($fraudStatus == 'challenge') {
                            $transaction->status = 'pending';
                        } else {
                            $transaction->status = 'paid';
                            $transaction->paid_at = now();
                        }
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $transaction->status = 'paid';
                    $transaction->paid_at = now();
                } elseif ($transactionStatus == 'pending') {
                    $transaction->status = 'pending';
                } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
                    $transaction->status = 'failed';
                }

                $transaction->save();
            }

            return response()->json(['message' => 'Callback handled successfully']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
