<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Show checkout form
     */
    public function show($productId)
    {
        $product = Product::with('umkm')->findOrFail($productId);
        $user = auth()->user();
        $settings = Setting::whereIn('group', ['payment', 'delivery'])->get()->keyBy('key');

        return view('checkout.form', compact('product', 'user', 'settings'));
    }

    /**
     * Store transaction
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'buyer_name' => 'required|string|max:255',
                'buyer_phone' => 'required|string|max:20',
                'buyer_address' => 'required|string',
                'buyer_city' => 'required|string|max:255',
                'buyer_postal_code' => 'nullable|string|max:10',
                'payment_method' => 'required|in:midtrans,transfer,qris,cod',
                'order_type' => 'required|in:po,langsung',
                'delivery_type' => 'required|in:take_away,delivery',
                'quantity' => 'required|integer|min:1',
                'notes' => 'nullable|string',
                'delivery_fee' => 'nullable|numeric|min:0',
            ]);

            $product = Product::findOrFail($validated['product_id']);
            $totalHarga = ($product->harga * $validated['quantity']) + ($validated['delivery_fee'] ?? 0);

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'umkm_id' => $product->umkm_id,
                'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
                'buyer_name' => $validated['buyer_name'],
                'buyer_phone' => $validated['buyer_phone'],
                'buyer_address' => $validated['buyer_address'],
                'buyer_city' => $validated['buyer_city'],
                'buyer_postal_code' => $validated['buyer_postal_code'],
                'price' => $product->harga,
                'total_price' => $totalHarga,
                'payment_method' => $validated['payment_method'],
                'order_type' => $validated['order_type'],
                'delivery_type' => $validated['delivery_type'],
                'quantity' => $validated['quantity'],
                'notes' => $validated['notes'],
                'status' => 'pending',
                'delivery_status' => 'pending',
                'snap_token' => null,
            ]);

            $snapToken = null;
            if ($validated['payment_method'] === 'midtrans') {
                if (class_exists('\Midtrans\Config')) {
                    $serverKey = Setting::where('key', 'midtrans_server_key')->first()?->value ?? config('midtrans.server_key');
                    $isProduction = (Setting::where('key', 'midtrans_is_production')->first()?->value ?? '0') == '1';

                    if ($serverKey) {
                        \Midtrans\Config::$serverKey = $serverKey;
                        \Midtrans\Config::$isProduction = $isProduction;
                        \Midtrans\Config::$isSanitized = true;
                        \Midtrans\Config::$is3ds = true;

                        $params = [
                            'transaction_details' => [
                                'order_id' => $transaction->transaction_code,
                                'gross_amount' => (int)$totalHarga,
                            ],
                            'customer_details' => [
                                'first_name' => $validated['buyer_name'],
                                'phone' => $validated['buyer_phone'],
                            ],
                            'item_details' => [
                                [
                                    'id' => $product->id,
                                    'price' => (int)$product->harga,
                                    'quantity' => (int)$validated['quantity'],
                                    'name' => $product->nama_produk,
                                ]
                            ]
                        ];

                        if (($validated['delivery_fee'] ?? 0) > 0) {
                            $params['item_details'][] = [
                                'id' => 'delivery_fee',
                                'price' => (int)$validated['delivery_fee'],
                                'quantity' => 1,
                                'name' => 'Biaya Pengiriman',
                            ];
                        }

                        $snapToken = \Midtrans\Snap::getSnapToken($params);
                        $transaction->update(['snap_token' => $snapToken]);
                    }
                }
            }

            $redirectUrl = route('checkout.success', $transaction->id);

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'snap_token' => $snapToken,
                    'redirect_url' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl);

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Success page
     */
    public function success($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        return view('checkout.success', compact('transaction'));
    }
}
