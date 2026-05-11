<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $settings = Setting::whereIn('group', ['payment', 'delivery'])->get()->keyBy('key');
        
        // Group items by UMKM
        $groupedCart = [];
        $totalPrice = 0;
        
        foreach ($cart as $id => $details) {
            $umkmId = $details['umkm_id'];
            $umkmName = $details['umkm_name'];
            
            if (!isset($groupedCart[$umkmId])) {
                $groupedCart[$umkmId] = [
                    'name' => $umkmName,
                    'items' => []
                ];
            }
            
            $groupedCart[$umkmId]['items'][$id] = $details;
            $totalPrice += $details['price'] * $details['quantity'];
        }

        return view('cart.index', compact('groupedCart', 'totalPrice', 'settings'));
    }

    public function add($id, Request $request)
    {
        $product = Product::with('umkm')->findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                "name" => $product->nama_produk,
                "quantity" => $quantity,
                "price" => $product->harga,
                "image" => $product->image,
                "umkm_id" => $product->umkm_id,
                "umkm_name" => $product->umkm->nama_toko ?? 'UMKM',
                "satuan" => $product->satuan ?? 'pcs'
            ];
        }

        session()->put('cart', $cart);

        if ($request->input('redirect') === 'cart') {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        if ($request->id && isset($request->quantity)) {
            $cart = session()->get('cart');
            
            if ($request->quantity <= 0) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                session()->flash('success', 'Produk berhasil dihapus dari keranjang');
            } else {
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);
                session()->flash('success', 'Keranjang berhasil diperbarui');
            }
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Produk berhasil dihapus dari keranjang');
        }
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $user = auth()->user();
        $settings = Setting::whereIn('group', ['payment', 'delivery'])->get()->keyBy('key');
        
        // Group items by UMKM
        $groupedCart = [];
        foreach ($cart as $id => $details) {
            $umkmId = $details['umkm_id'];
            if (!isset($groupedCart[$umkmId])) {
                $groupedCart[$umkmId] = [
                    'name' => $details['umkm_name'],
                    'items' => []
                ];
            }
            $groupedCart[$umkmId]['items'][$id] = $details;
        }

        $adminBanks = \App\Models\AdminBankAccount::all();
        return view('checkout.form', compact('groupedCart', 'user', 'settings', 'adminBanks'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_phone' => 'required|string|max:20',
            'buyer_address' => 'required|string',
            'buyer_city' => 'required|string|max:255',
            'buyer_postal_code' => 'nullable|string|max:10',
            'delivery_type' => 'required|in:take_away,delivery',
            'payment_method' => 'required|string',
            'order_type' => 'required|in:langsung,po',
            'po_date' => 'required_if:order_type,po|nullable|date',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $settings = Setting::whereIn('group', ['payment', 'delivery'])->get()->keyBy('key');
        $deliveryFeeFlat = Setting::get('delivery_fee_flat', 0);
        $deliveryFeeType = Setting::get('delivery_fee_type', 'flat');
        $deliveryFeePerKm = Setting::get('delivery_fee_per_km', 0);
        
        // If using distance, for now we default to the per_km fee as a simple base
        if ($deliveryFeeType === 'distance') {
            $deliveryFeeFlat = $deliveryFeePerKm;
        }
        
        $checkoutCode = 'CHK-' . strtoupper(\Illuminate\Support\Str::random(10));
        $transactions = [];
        $totalGrossAmount = 0;

        // Group cart items by UMKM to create per-UMKM transactions
        $groupedCart = [];
        foreach ($cart as $id => $details) {
            $groupedCart[$details['umkm_id']][] = array_merge(['id' => $id], $details);
        }

        try {
            \DB::beginTransaction();

            foreach ($groupedCart as $umkmId => $items) {
                $umkmSubtotal = 0;
                foreach ($items as $item) {
                    $umkmSubtotal += $item['price'] * $item['quantity'];
                }

                $shippingFee = ($validated['delivery_type'] === 'delivery') ? $deliveryFeeFlat : 0;
                $umkmTotal = $umkmSubtotal + $shippingFee;
                $totalGrossAmount += $umkmTotal;

                // Create Transaction per UMKM
                $transaction = \App\Models\Transaction::create([
                    'checkout_code' => $checkoutCode,
                    'transaction_code' => 'TRX-' . strtoupper(\Illuminate\Support\Str::random(12)),
                    'user_id' => auth()->id(),
                    'umkm_id' => $umkmId,
                    'product_id' => $items[0]['id'], // Store first product for compatibility
                    'quantity' => array_sum(array_column($items, 'quantity')),
                    'price' => $items[0]['price'], // Store first product price for compatibility
                    'total_price' => $umkmTotal, // Final price including shipping
                    'delivery_fee' => $shippingFee,
                    'status' => 'pending',
                    'delivery_status' => 'pending',
                    'delivery_type' => $validated['delivery_type'],
                    'payment_method' => $validated['payment_method'],
                    'order_type' => $validated['order_type'],
                    'po_date' => $validated['po_date'] ?? null,
                    'buyer_name' => $validated['buyer_name'],
                    'buyer_phone' => $validated['buyer_phone'],
                    'buyer_address' => $validated['buyer_address'],
                    'buyer_city' => $validated['buyer_city'],
                    'buyer_postal_code' => $validated['buyer_postal_code'],
                ]);

                // Create Transaction Items
                foreach ($items as $item) {
                    \App\Models\TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                    ]);
                }

                $transactions[] = $transaction;
            }

            \DB::commit();

            // Clear Cart
            session()->forget('cart');

            // Handle Midtrans if selected
            if ($validated['payment_method'] === 'midtrans') {
                return $this->handleMidtransPayment($checkoutCode, $totalGrossAmount, $validated, $transactions);
            }

            // Redirect to success/first transaction
            return redirect()->route('checkout.success', $transactions[0]->id)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function handleMidtransPayment($checkoutCode, $amount, $validated, $transactions)
    {
        $serverKey = Setting::where('key', 'midtrans_server_key')->first()?->value;
        $isProduction = (Setting::where('key', 'midtrans_is_production')->first()?->value ?? '0') == '1';

        if (!$serverKey) {
            return redirect()->route('checkout.success', $transactions[0]->id)->with('warning', 'Pesanan dibuat, tapi Midtrans belum dikonfigurasi.');
        }

        \Midtrans\Config::$serverKey = $serverKey;
        \Midtrans\Config::$isProduction = $isProduction;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $checkoutCode,
                'gross_amount' => (int)$amount,
            ],
            'customer_details' => [
                'first_name' => $validated['buyer_name'],
                'phone' => $validated['buyer_phone'],
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Update all transactions with snap token
            foreach ($transactions as $t) {
                $t->update(['snap_token' => $snapToken]);
            }

            return view('cart.payment_process', compact('snapToken', 'checkoutCode', 'transactions'));
        } catch (\Exception $e) {
            \Log::error('Midtrans Snap Error: ' . $e->getMessage());
            return redirect()->route('checkout.success', $transactions[0]->id)->with('error', 'Gagal menghubungkan ke Midtrans: ' . $e->getMessage());
        }
    }
}
