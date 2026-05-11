@extends('layouts.app')

@section('title', 'Checkout - ' . (isset($product) ? $product->nama_produk : 'Keranjang Belanja') . ' - Karang Taruna Teluknaga')

@section('content')

<div class="checkout-container">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="checkout-card">
            <div class="checkout-header">
                <h2 class="mb-2 fw-bold"><i class="bi bi-cart-check me-3"></i>{{ isset($product) ? 'Checkout Produk' : 'Konfirmasi Pesanan' }}</h2>
                <p class="mb-0 opacity-75">{{ isset($product) ? 'Lengkapi data untuk melanjutkan pembelian' : 'Tinjau daftar belanja dan lengkapi data pengiriman' }}</p>
            </div>
            
            <div class="checkout-body">
                <!-- Shopping Cart Summary -->
                <div class="product-summary">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-bag-heart me-2 text-primary"></i>Ringkasan {{ isset($product) ? 'Produk' : 'Belanja' }}
                    </h5>
                    
                    @if(isset($product))
                        <div class="row align-items-center g-4">
                            <div class="col-auto">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png') }}" class="product-image-small shadow-sm" style="width: 100px; height: 100px;">
                            </div>
                            <div class="col">
                                <h5 class="fw-bold mb-1 text-dark">{{ $product->nama_produk }}</h5>
                                <p class="text-muted mb-2"><i class="bi bi-shop me-1"></i>{{ $product->umkm->nama_toko }}</p>
                                <div class="text-primary fw-bold fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }} / {{ $product->satuan }}</div>
                            </div>
                        </div>
                        
                        <div class="quantity-selector mt-4 p-3 bg-light rounded-4 d-flex align-items-center gap-3">
                            <label class="fw-bold mb-0">Jumlah:</label>
                            <div class="input-group" style="width: 140px;">
                                <button type="button" class="btn btn-outline-primary rounded-start-pill" onclick="changeQuantity(-1)">-</button>
                                <input type="number" id="quantity" value="1" min="1" max="{{ $product->stok }}" class="form-control text-center fw-bold border-primary" onchange="updateTotal()">
                                <button type="button" class="btn btn-outline-primary rounded-end-pill" onclick="changeQuantity(1)">+</button>
                            </div>
                            <span class="text-muted small">Stok: {{ $product->stok }}</span>
                        </div>
                    @else
                        @foreach($groupedCart as $umkmId => $data)
                            <div class="umkm-group mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-shop text-primary me-2 fs-5"></i>
                                    <h6 class="mb-0 fw-bold text-dark">{{ $data['name'] }}</h6>
                                </div>
                                
                                @foreach($data['items'] as $id => $item)
                                    <div class="row align-items-center mb-3 g-3">
                                        <div class="col-auto">
                                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/no-image.png') }}" class="product-image-small shadow-sm" style="width: 60px; height: 60px;">
                                        </div>
                                        <div class="col">
                                            <h6 class="fw-bold mb-1 text-dark">{{ $item['name'] }}</h6>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="text-muted mb-0 small">{{ $item['quantity'] }} {{ $item['satuan'] ?? 'pcs' }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                                <span class="fw-bold text-primary">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif

                    <!-- Fee Summary -->
                    <div class="total-display mt-4 p-4 rounded-4 bg-white shadow-sm border">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">{{ isset($product) ? 'Subtotal:' : 'Subtotal Produk:' }}</span>
                            <span class="fw-bold" id="subtotal">
                                @if(isset($product))
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format(array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))), 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        
                        @if(!isset($product))
                        <div class="d-flex justify-content-between mb-2 align-items-center">
                            <span class="text-muted">Biaya Pengiriman:</span>
                            <div class="text-end">
                                <span id="delivery-fee-display" class="fw-bold text-primary">Gratis</span>
                                <div class="x-small text-muted" id="delivery-calculation" style="display:none;">
                                    (Rp{{ number_format(App\Models\Setting::get('delivery_fee_flat', 0), 0, ',', '.') }} x {{ count($groupedCart) }} UMKM)
                                </div>
                            </div>
                        </div>
                        @endif

                        <hr class="my-3 opacity-10">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="fs-5">Total Pembayaran:</strong>
                            <strong id="total" class="text-success fs-3">
                                @if(isset($product))
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format(array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))), 0, ',', '.') }}
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <form action="{{ isset($product) ? route('catalog.order', $product->id) : route('cart.checkout.process') }}" method="POST" id="checkoutForm">
                    @csrf
                    @if(isset($product))
                        <input type="hidden" name="quantity" id="quantityInput" value="1">
                    @endif
                    
                    @if(!isset($product))
                        <!-- Pengiriman & Pembayaran Section for Cart -->
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3"><i class="bi bi-truck me-2 text-primary"></i>Metode Pengiriman</h5>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input class="d-none custom-option-input" type="radio" name="delivery_type" id="delivery_takeaway" value="take_away" checked onchange="calculateTotal()">
                                        <label class="custom-option d-flex flex-column p-3 rounded-4" for="delivery_takeaway">
                                            <span class="fw-bold">Ambil Sendiri (Take Away)</span>
                                            <small class="text-muted">Tanpa biaya pengiriman</small>
                                        </label>
                                    </div>
                                    @if((App\Models\Setting::get('delivery_enabled', '0')) == '1')
                                    <div class="col-12">
                                        <input class="d-none custom-option-input" type="radio" name="delivery_type" id="delivery_shipping" value="delivery" onchange="calculateTotal()">
                                        <label class="custom-option d-flex flex-column p-3 rounded-4" for="delivery_shipping">
                                            <span class="fw-bold">Kirim ke Alamat</span>
                                            <small class="text-muted">Biaya flat per UMKM</small>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Jenis Pesanan</h5>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input class="d-none custom-option-input" type="radio" name="order_type" id="order_instant" value="langsung" checked onchange="togglePoDate()">
                                        <label class="custom-option d-flex flex-column p-3 rounded-4" for="order_instant">
                                            <span class="fw-bold">Langsung Kirim</span>
                                            <small class="text-muted">Stok tersedia</small>
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <input class="d-none custom-option-input" type="radio" name="order_type" id="order_po" value="po" onchange="togglePoDate()">
                                        <label class="custom-option d-flex flex-column p-3 rounded-4" for="order_po">
                                            <span class="fw-bold">Pre-Order (PO)</span>
                                            <small class="text-muted">Dibuat sesuai pesanan</small>
                                        </label>
                                    </div>
                                </div>
                                <div id="po_date_container" class="mt-3" style="display: none;">
                                    <div class="form-floating">
                                        <input type="date" class="form-control rounded-4" name="po_date" id="po_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        <label for="po_date">Tanggal Target Selesai *</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Section for Cart -->
                        <div class="mb-5">
                            <h5 class="fw-bold mb-3"><i class="bi bi-wallet2 me-2 text-primary"></i>Metode Pembayaran</h5>
                            <div class="row g-3">
                                @if((App\Models\Setting::get('payment_midtrans_enabled', '0')) == '1')
                                <div class="col-md-6">
                                    <input class="d-none custom-option-input" type="radio" name="payment_type" id="pay_auto" value="auto" checked onchange="togglePaymentType()">
                                    <label class="custom-option d-flex align-items-center p-3 rounded-4" for="pay_auto">
                                        <i class="bi bi-lightning-charge text-warning fs-3 me-3"></i>
                                        <div>
                                            <div class="fw-bold">Otomatis (Midtrans)</div>
                                            <small class="text-muted">VA, QRIS, E-Wallet</small>
                                        </div>
                                    </label>
                                </div>
                                @endif

                                @if((App\Models\Setting::get('payment_manual_enabled', '1')) == '1')
                                <div class="col-md-6">
                                    <input class="d-none custom-option-input" type="radio" name="payment_type" id="pay_manual" value="manual" onchange="togglePaymentType()">
                                    <label class="custom-option d-flex align-items-center p-3 rounded-4" for="pay_manual">
                                        <i class="bi bi-bank text-info fs-3 me-3"></i>
                                        <div>
                                            <div class="fw-bold">Manual / COD</div>
                                            <small class="text-muted">Transfer Bank</small>
                                        </div>
                                    </label>
                                </div>
                                @endif
                            </div>

                            <div id="metode-pembayaran-wrapper" style="display: none;" class="mt-3">
                                <div id="metode-auto" style="display: none;">
                                    <div class="p-3 border rounded-4 bg-light d-flex align-items-center">
                                        <input class="form-check-input me-2" type="radio" name="payment_method" id="method_midtrans" value="midtrans" checked>
                                        <label class="fw-bold mb-0" for="method_midtrans">Bayar via Midtrans Gateway</label>
                                    </div>
                                </div>
                                <div id="metode-manual" style="display: none;">
                                    <div class="form-floating">
                                        <select class="form-select rounded-4" id="payment_method" name="payment_method" onchange="togglePaymentDetails()">
                                            <option value="" disabled selected>Pilih Metode Manual</option>
                                            <option value="transfer">Transfer Bank Manual</option>
                                            <option value="qris">QRIS (Manual)</option>
                                            <option value="cod">COD (Bayar di Tempat)</option>
                                        </select>
                                        <label for="payment_method">Metode Pembayaran *</label>
                                    </div>
                                </div>
                            </div>

                            <div id="payment-details-box" class="mt-3 p-4 border rounded-4 bg-light" style="display: none; border-left: 5px solid #667eea !important;">
                                <div id="details-transfer" style="display: none;">
                                    <h6 class="fw-bold mb-2">Rekening Pembayaran</h6>
                                    <div class="bg-white p-3 rounded-4 shadow-sm border">
                                        <p class="mb-1 text-muted small">Bank: <strong>{{ App\Models\Setting::get('payment_bank_name', '-') }}</strong></p>
                                        <p class="mb-1 h5 fw-bold text-primary">{{ App\Models\Setting::get('payment_bank_account', '-') }}</p>
                                        <p class="mb-0 small">a.n {{ App\Models\Setting::get('payment_bank_holder', '-') }}</p>
                                    </div>
                                </div>
                                <div id="details-qris" class="text-center" style="display: none;">
                                    <h6 class="fw-bold mb-3">Scan QRIS</h6>
                                    @php $qris = App\Models\Setting::get('payment_qris_image'); @endphp
                                    @if($qris)
                                        <img src="{{ asset($qris) }}" class="img-fluid rounded-4 mb-2 shadow-sm" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-person-check me-2 text-primary"></i>Data Penerima
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="{{ isset($product) ? 'customer_name' : 'buyer_name' }}" class="form-control rounded-4" id="name" value="{{ old('customer_name', $user->name ?? '') }}" placeholder="Nama Lengkap" required>
                                <label for="name">Nama Lengkap *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="{{ isset($product) ? 'customer_phone' : 'buyer_phone' }}" class="form-control rounded-4" id="phone" value="{{ old('customer_phone', $user->phone ?? '') }}" placeholder="Nomor WhatsApp" required>
                                <label for="phone">Nomor WhatsApp *</label>
                            </div>
                        </div>
                    </div>

                    @if(isset($product))
                    <div class="form-floating">
                        <input type="email" name="customer_email" class="form-control rounded-4" id="email" value="{{ old('customer_email', $user->email ?? '') }}" placeholder="Email" required>
                        <label for="email">Email *</label>
                    </div>
                    @endif

                    <div class="form-floating">
                        <textarea name="{{ isset($product) ? 'customer_address' : 'buyer_address' }}" class="form-control rounded-4" id="address" placeholder="Alamat Lengkap" style="height: 120px" required>{{ old('customer_address', $user->address ?? '') }}</textarea>
                        <label for="address">Alamat Lengkap *</label>
                    </div>

                    @if(!isset($product))
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="buyer_city" class="form-control rounded-4" id="city" value="{{ old('buyer_city', $user->city ?? '') }}" placeholder="Kecamatan/Kota" required>
                                <label for="city">Kecamatan/Kota *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" name="buyer_postal_code" class="form-control rounded-4" id="postal" value="{{ old('buyer_postal_code', $user->postal_code ?? '') }}" placeholder="Kode Pos">
                                <label for="postal">Kode Pos</label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="security-info p-3 rounded-4 bg-light mb-5 d-flex align-items-center">
                        <i class="bi bi-shield-check text-success fs-3 me-3"></i>
                        <p class="mb-0 small text-muted">Data Anda aman dan terenkripsi. Kami akan menghubungi Anda segera untuk konfirmasi pesanan.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <a href="{{ isset($product) ? route('beli', $product->id) : route('cart.index') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-4">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                        <button type="submit" id="btn-submit-order" class="btn btn-checkout btn-lg px-5 rounded-4 shadow-lg">
                            <i class="bi bi-check2-circle me-2"></i>{{ isset($product) ? 'Lanjut ke Pembayaran' : 'Konfirmasi Pesanan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .checkout-container {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        min-height: 100vh;
        padding: 100px 0 50px 0;
    }
    
    .checkout-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 30px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .checkout-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: white;
        padding: 3.5rem;
        text-align: center;
    }
    
    .checkout-body {
        padding: 4rem;
    }
    
    .product-summary {
        margin-bottom: 4rem;
    }
    
    .product-image-small {
        border-radius: 20px;
        object-fit: cover;
    }
    
    .form-floating {
        margin-bottom: 1.5rem;
    }
    
    .form-floating > .form-control {
        border-radius: 18px;
        border: 3px solid #f1f3f4;
        height: 65px;
        background: #fafafa;
        transition: all 0.3s ease;
    }
    
    .form-floating > .form-control:focus {
        border-color: #ff9a9e;
        box-shadow: 0 0 0 5px rgba(255, 154, 158, 0.15);
        background: white;
    }
    
    .custom-option {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 3px solid #f1f3f4;
        background: #fafafa;
    }
    
    .custom-option-input:checked + .custom-option {
        border-color: #ff9a9e;
        background: white;
        box-shadow: 0 10px 20px rgba(255, 154, 158, 0.15);
    }
    
    .custom-option-input:checked + .custom-option .fw-bold {
        color: #ff9a9e;
    }
    
    .custom-option-input {
        display: none;
    }
    
    .btn-checkout {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        border: none;
        color: white;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 1.25rem 3rem;
        transition: all 0.3s ease;
    }
    
    .btn-checkout:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(255, 154, 158, 0.4);
        color: white;
    }

    .x-small { font-size: 11px; }

    @media (max-width: 768px) {
        .checkout-body { padding: 2rem; }
        .checkout-header { padding: 2.5rem; }
    }
</style>
@endpush

@push('scripts')
@if(isset($product))
<script>
    const productPrice = {{ $product->harga }};
    const maxStock = {{ $product->stok }};

    function changeQuantity(change) {
        const quantityInput = document.getElementById('quantity');
        let currentQuantity = parseInt(quantityInput.value);
        let newQuantity = currentQuantity + change;
        if (newQuantity < 1) newQuantity = 1;
        if (newQuantity > maxStock) newQuantity = maxStock;
        quantityInput.value = newQuantity;
        updateTotal();
    }

    function updateTotal() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const total = productPrice * quantity;
        document.getElementById('subtotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('quantityInput').value = quantity;
    }
</script>
@else
@php
    $isProduction = App\Models\Setting::get('midtrans_is_production', '0') == '1';
    $snapSrc = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    $clientKey = App\Models\Setting::get('midtrans_client_key', '');
@endphp
<script src="{{ $snapSrc }}" data-client-key="{{ $clientKey }}"></script>
<script>
    const productTotal = {{ array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))) }};
    const deliveryFeeFlat = {{ App\Models\Setting::get('delivery_fee_flat', 0) }};
    const umkmCount = {{ count($groupedCart) }};

    function calculateTotal() {
        const isDelivery = document.querySelector('input[name="delivery_type"]:checked').value === 'delivery';
        const totalShipping = isDelivery ? (deliveryFeeFlat * umkmCount) : 0;
        const grandTotal = productTotal + totalShipping;
        document.getElementById('delivery-fee-display').textContent = totalShipping > 0 ? formatIDR(totalShipping) : 'Gratis';
        document.getElementById('total').textContent = formatIDR(grandTotal);
        document.getElementById('delivery-calculation').style.display = isDelivery ? 'block' : 'none';
    }

    function togglePaymentType() {
        const type = document.querySelector('input[name="payment_type"]:checked')?.value;
        const wrapper = document.getElementById('metode-pembayaran-wrapper');
        const auto = document.getElementById('metode-auto');
        const manual = document.getElementById('metode-manual');
        if (!type) return;
        wrapper.style.display = 'block';
        if (type === 'auto') {
            auto.style.display = 'block';
            manual.style.display = 'none';
            togglePaymentDetails();
        } else {
            auto.style.display = 'none';
            manual.style.display = 'block';
            togglePaymentDetails();
        }
    }

    function togglePaymentDetails() {
        const type = document.querySelector('input[name="payment_type"]:checked')?.value;
        const method = type === 'auto' ? 'midtrans' : document.getElementById('payment_method').value;
        const box = document.getElementById('payment-details-box');
        const detailsTransfer = document.getElementById('details-transfer');
        const detailsQris = document.getElementById('details-qris');
        box.style.display = (method === 'transfer' || method === 'qris') ? 'block' : 'none';
        detailsTransfer.style.display = method === 'transfer' ? 'block' : 'none';
        detailsQris.style.display = method === 'qris' ? 'block' : 'none';
    }

    function togglePoDate() {
        const orderType = document.querySelector('input[name="order_type"]:checked')?.value;
        const container = document.getElementById('po_date_container');
        document.getElementById('po_date').required = (orderType === 'po');
        container.style.display = (orderType === 'po' ? 'block' : 'none');
    }

    function formatIDR(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateTotal();
        togglePaymentType();
        togglePoDate();
    });
</script>
@endif
@endpush
