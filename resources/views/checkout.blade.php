@extends('layouts.app')

@section('title', 'Checkout - ' . (isset($product) ? $product->nama_produk : 'LOKALIN'))

@section('content')

<!-- Checkout Section -->
<section class="py-5" style="background-color: #f8f9fa; min-height: 100vh; padding-top: 100px !important;">
    <div class="container">
        <div class="row g-4">
            <!-- Form Checkout (Kiri) -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-bag-check me-2"></i>Formulir Pemesanan</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ isset($product) ? route('catalog.order', $product->id) : route('cart.checkout.process') }}" method="POST" id="checkoutForm">
                            @csrf
                            
                            <!-- Data Penerima -->
                            <h6 class="fw-bold mb-4 text-uppercase text-success" style="letter-spacing: 1px;"><i class="bi bi-person-lines-fill me-2"></i>Informasi Penerima</h6>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="{{ isset($product) ? 'customer_name' : 'buyer_name' }}" class="form-control form-control-lg rounded-3" value="{{ old('customer_name', $user->name ?? '') }}" placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nomor WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" name="{{ isset($product) ? 'customer_phone' : 'buyer_phone' }}" class="form-control form-control-lg rounded-3" value="{{ old('customer_phone', $user->phone ?? '') }}" placeholder="Contoh: 08123456789" required>
                                </div>
                            </div>

                            @if(isset($product))
                            <div class="mb-4">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="customer_email" class="form-control form-control-lg rounded-3" value="{{ old('customer_email', $user->email ?? '') }}" placeholder="email@contoh.com" required>
                            </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Alamat Pengiriman Lengkap <span class="text-danger">*</span></label>
                                <textarea name="{{ isset($product) ? 'customer_address' : 'buyer_address' }}" class="form-control rounded-3" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, dsb" required>{{ old('customer_address', $user->address ?? '') }}</textarea>
                            </div>

                            @if(!isset($product))
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kecamatan/Desa <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_city" class="form-control form-control-lg rounded-3" value="{{ old('buyer_city', $user->city ?? '') }}" placeholder="Contoh: Teluknaga" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kode Pos</label>
                                    <input type="text" name="buyer_postal_code" class="form-control form-control-lg rounded-3" value="{{ old('buyer_postal_code', $user->postal_code ?? '') }}" placeholder="15510">
                                </div>
                            </div>
                            @endif

                            <hr class="my-5">

                            <!-- Opsi Pesanan (Hanya untuk Cart) -->
                            @if(!isset($product))
                            <h6 class="fw-bold mb-4 text-uppercase text-success" style="letter-spacing: 1px;"><i class="bi bi-gear-fill me-2"></i>Opsi Pengiriman & Pesanan</h6>
                            
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Metode Pengiriman</label>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="form-check custom-card-check p-0">
                                            <input class="d-none" type="radio" name="delivery_type" id="delivery_takeaway" value="take_away" checked onchange="calculateTotal()">
                                            <label class="w-100 border rounded-3 p-3 cursor-pointer" for="delivery_takeaway">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-bold">Take Away</div>
                                                        <small class="text-muted">Ambil sendiri ke UMKM</small>
                                                    </div>
                                                    <i class="bi bi-shop fs-4 text-success opacity-50"></i>
                                                </div>
                                            </label>
                                        </div>
                                        @if((App\Models\Setting::get('delivery_enabled', '0')) == '1')
                                        <div class="form-check custom-card-check p-0">
                                            <input class="d-none" type="radio" name="delivery_type" id="delivery_shipping" value="delivery" onchange="calculateTotal()">
                                            <label class="w-100 border rounded-3 p-3 cursor-pointer" for="delivery_shipping">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-bold">Kirim Ke Alamat</div>
                                                        <small class="text-muted">Kurir akan mengantar pesanan</small>
                                                    </div>
                                                    <i class="bi bi-truck fs-4 text-success opacity-50"></i>
                                                </div>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipe Pesanan</label>
                                    <div class="d-flex flex-column gap-2">
                                        <div class="form-check custom-card-check p-0">
                                            <input class="d-none" type="radio" name="order_type" id="order_instant" value="langsung" checked onchange="togglePoDate()">
                                            <label class="w-100 border rounded-3 p-3 cursor-pointer" for="order_instant">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-bold">Langsung</div>
                                                        <small class="text-muted">Stok tersedia sekarang</small>
                                                    </div>
                                                    <i class="bi bi-lightning-fill fs-4 text-warning opacity-50"></i>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="form-check custom-card-check p-0">
                                            <input class="d-none" type="radio" name="order_type" id="order_po" value="po" onchange="togglePoDate()">
                                            <label class="w-100 border rounded-3 p-3 cursor-pointer" for="order_po">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="fw-bold">Pre-Order (PO)</div>
                                                        <small class="text-muted">Dibuat sesuai pesanan</small>
                                                    </div>
                                                    <i class="bi bi-calendar-check fs-4 text-info opacity-50"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="po_date_container" class="mt-3" style="display: none;">
                                        <input type="date" class="form-control" name="po_date" id="po_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        <small class="text-muted">Kapan pesanan diharapkan selesai.</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-5">

                            <!-- Metode Pembayaran -->
                            <h6 class="fw-bold mb-4 text-uppercase text-success" style="letter-spacing: 1px;"><i class="bi bi-credit-card-fill me-2"></i>Metode Pembayaran</h6>
                            
                            <div class="row g-3 mb-4">
                                @if((App\Models\Setting::get('payment_midtrans_enabled', '0')) == '1')
                                <div class="col-md-6">
                                    <div class="form-check custom-card-check p-0">
                                        <input class="d-none" type="radio" name="payment_type" id="pay_auto" value="auto" checked onchange="togglePaymentType()">
                                        <label class="w-100 border rounded-3 p-3 cursor-pointer h-100" for="pay_auto">
                                            <div class="fw-bold">Otomatis</div>
                                            <small class="text-muted">VA, QRIS, GoPay (Midtrans)</small>
                                        </label>
                                    </div>
                                </div>
                                @endif
                                @if((App\Models\Setting::get('payment_manual_enabled', '1')) == '1')
                                <div class="col-md-6">
                                    <div class="form-check custom-card-check p-0">
                                        <input class="d-none" type="radio" name="payment_type" id="pay_manual" value="manual" onchange="togglePaymentType()">
                                        <label class="w-100 border rounded-3 p-3 cursor-pointer h-100" for="pay_manual">
                                            <div class="fw-bold">Manual / COD</div>
                                            <small class="text-muted">Transfer Bank atau Bayar Ditempat</small>
                                        </label>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div id="metode-pembayaran-wrapper" style="display: none;" class="mb-4">
                                <div id="metode-auto" style="display: none;">
                                    <input type="hidden" name="payment_method" value="midtrans">
                                    <div class="alert alert-success border-0 small">Sistem akan mengarahkan Anda ke gerbang pembayaran aman Midtrans.</div>
                                </div>
                                <div id="metode-manual" style="display: none;">
                                    <select class="form-select form-select-lg rounded-3" id="payment_method_manual" name="payment_method" onchange="togglePaymentDetails()">
                                        <option value="" disabled selected>Pilih Metode Manual</option>
                                        <option value="transfer">Transfer Bank Manual</option>
                                        <option value="qris">QRIS (Manual)</option>
                                        <option value="cod">COD (Bayar di Tempat)</option>
                                    </select>
                                </div>
                            </div>

                            <div id="payment-details-box" class="mb-4 p-4 border rounded-3 bg-light" style="display: none;">
                                <div id="details-transfer" style="display: none;">
                                    <h6 class="fw-bold mb-2">Informasi Rekening</h6>
                                    <div class="bg-white p-3 rounded shadow-sm">
                                        <p class="mb-1">Bank: <strong>{{ App\Models\Setting::get('payment_bank_name', '-') }}</strong></p>
                                        <p class="mb-1 h5 fw-bold text-success">{{ App\Models\Setting::get('payment_bank_account', '-') }}</p>
                                        <p class="mb-0 small text-muted">a.n {{ App\Models\Setting::get('payment_bank_holder', '-') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="alert alert-success d-flex align-items-center gap-3 border-0 rounded-4 shadow-sm py-3 px-4">
                                <i class="bi bi-shield-lock-fill fs-2"></i>
                                <div class="small">Pesanan Anda akan langsung diteruskan ke UMKM terkait. Data pribadi Anda dijamin keamanannya oleh sistem LOKALIN.</div>
                            </div>

                            <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0">

                            <div class="d-grid mt-5">
                                <button type="submit" class="btn btn-success btn-lg py-3 fw-bold rounded-3 shadow-sm hover-grow">
                                    {{ isset($product) ? 'Buat Pesanan & Bayar' : 'Konfirmasi & Buat Pesanan' }}
                                </button>
                                <a href="{{ isset($product) ? route('beli', $product->id) : route('cart.index') }}" class="btn btn-link text-muted mt-3">Batalkan dan Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan (Kanan - Sticky) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 110px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-success"></i>Ringkasan Pesanan</h6>
                    </div>
                    <div class="card-body p-4">
                        @if(isset($product))
                            <div class="d-flex gap-3 mb-4">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png') }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $product->nama_produk }}</h6>
                                    <p class="text-muted small mb-0">{{ $product->umkm->nama_toko }}</p>
                                    <div class="fw-bold text-success mt-1">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-4 p-3 bg-light rounded-3">
                                <label class="small fw-bold mb-0">Jumlah</label>
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
                                    <input type="number" id="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stok }}" onchange="updateTotal()">
                                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
                                </div>
                            </div>
                        @else
                            <div class="mb-4">
                                @foreach($groupedCart as $umkmId => $data)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2 small text-muted">
                                            <i class="bi bi-shop me-1"></i> {{ $data['name'] }}
                                        </div>
                                        @foreach($data['items'] as $item)
                                            <div class="d-flex justify-content-between mb-1 small">
                                                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                                <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Total Produk</span>
                                <span id="subtotal" class="fw-bold">
                                    @if(isset($product))
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format(array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))), 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            @if(!isset($product))
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Biaya Pengiriman</span>
                                <span id="delivery-fee-display" class="fw-bold text-success">Gratis</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                <h5 class="fw-bold mb-0">Total Tagihan</h5>
                                <h5 id="total" class="fw-bold text-success mb-0">
                                    @if(isset($product))
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format(array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))), 0, ',', '.') }}
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .custom-card-check input:checked + label {
        border-color: #198754 !important;
        background-color: #f8fffb;
        box-shadow: 0 5px 15px rgba(25, 135, 84, 0.1);
    }
    .custom-card-check label { transition: all 0.2s ease; }
    .custom-card-check label:hover { background-color: #fcfcfc; border-color: #dee2e6; }
    .hover-grow { transition: transform 0.2s ease; }
    .hover-grow:hover { transform: translateY(-3px); }
    .sticky-top { top: 110px !important; z-index: 900; }
    @media (max-width: 991px) { .sticky-top { position: static !important; margin-top: 2rem; } }
</style>
@endpush

@push('scripts')
@if(isset($product))
<script>
    const productPrice = {{ $product->harga }};
    const maxStock = {{ $product->stok }};
    function changeQuantity(change) {
        const input = document.getElementById('quantity');
        let qty = parseInt(input.value) + change;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        input.value = qty;
        updateTotal();
    }
    function updateTotal() {
        const qty = parseInt(document.getElementById('quantity').value);
        const total = productPrice * qty;
        document.getElementById('subtotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
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
        const type = document.querySelector('input[name="delivery_type"]:checked')?.value;
        const shipping = type === 'delivery' ? (deliveryFeeFlat * umkmCount) : 0;
        const grand = productTotal + shipping;
        document.getElementById('delivery-fee-display').textContent = shipping > 0 ? formatIDR(shipping) : 'Gratis';
        document.getElementById('total').textContent = formatIDR(grand);
        document.getElementById('delivery_fee_input').value = shipping;
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
        const method = type === 'auto' ? 'midtrans' : document.getElementById('payment_method_manual').value;
        const box = document.getElementById('payment-details-box');
        const detailsTransfer = document.getElementById('details-transfer');
        box.style.display = (method === 'transfer' || method === 'qris') ? 'block' : 'none';
        detailsTransfer.style.display = (method === 'transfer') ? 'block' : 'none';
    }

    function togglePoDate() {
        const type = document.querySelector('input[name="order_type"]:checked')?.value;
        const container = document.getElementById('po_date_container');
        document.getElementById('po_date').required = (type === 'po');
        container.style.display = (type === 'po' ? 'block' : 'none');
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

@endsection