@extends('layouts.app')

@section('title', 'Checkout - Karang Taruna Teluknaga')

@section('content')
<section class="py-5 mt-5">
    <div class="container">
        <h2 class="fw-bold mb-4"><i class="bi bi-cart-check me-2"></i>Checkout</h2>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('cart.checkout.process') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Data Penerima -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-light py-3 border-0">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2 text-success"></i>Informasi Penerima</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_name" class="form-control rounded-3" value="{{ old('buyer_name', $user->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nomor WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_phone" class="form-control rounded-3" value="{{ old('buyer_phone', $user->phone) }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="buyer_address" class="form-control rounded-3" rows="3" required>{{ old('buyer_address', $user->address) }}</textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kecamatan/Desa <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_city" class="form-control rounded-3" value="{{ old('buyer_city', $user->city) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kode Pos</label>
                                    <input type="text" name="buyer_postal_code" class="form-control rounded-3" value="{{ old('buyer_postal_code', $user->postal_code) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Opsi Pengiriman & Pesanan -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-light py-3 border-0">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-gear-fill me-2 text-success"></i>Opsi Pengiriman & Tipe Pesanan</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Metode Distribusi</label>
                                    <select name="delivery_type" class="form-select rounded-3" onchange="calculateTotal()">
                                        <option value="take_away" selected>Ambil Sendiri (Take Away)</option>
                                        @if((App\Models\Setting::get('delivery_enabled', '0')) == '1')
                                            <option value="delivery">Kirim ke Alamat</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipe Pesanan</label>
                                    <div class="d-flex gap-3 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="order_type" id="order_instant" value="langsung" checked onchange="togglePoDate()">
                                            <label class="form-check-label" for="order_instant">Langsung</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="order_type" id="order_po" value="po" onchange="togglePoDate()">
                                            <label class="form-check-label" for="order_po">Pre-Order (PO)</label>
                                        </div>
                                    </div>
                                    <div id="po_date_container" class="mt-3" style="display: none;">
                                        <input type="date" class="form-control rounded-3" name="po_date" id="po_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-light py-3 border-0">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-wallet2 me-2 text-success"></i>Metode Pembayaran</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-3">
                                @if((App\Models\Setting::get('payment_midtrans_enabled', '0')) == '1')
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 d-flex align-items-center cursor-pointer payment-option-card">
                                        <input class="form-check-input me-2" type="radio" name="payment_type" id="pay_auto" value="auto" checked onchange="togglePaymentType()">
                                        <label class="fw-bold mb-0 w-100" for="pay_auto">Otomatis (Midtrans)</label>
                                    </div>
                                </div>
                                @endif
                                @if((App\Models\Setting::get('payment_manual_enabled', '1')) == '1')
                                <div class="col-md-6">
                                    <div class="p-3 border rounded-3 d-flex align-items-center cursor-pointer payment-option-card">
                                        <input class="form-check-input me-2" type="radio" name="payment_type" id="pay_manual" value="manual" onchange="togglePaymentType()">
                                        <label class="fw-bold mb-0 w-100" for="pay_manual">Manual / COD</label>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div id="metode-pembayaran-wrapper" style="display: none;">
                                <div id="metode-auto" style="display: none;">
                                    <input type="hidden" name="payment_method" value="midtrans">
                                    <div class="alert alert-info border-0 rounded-3 small">Pembayaran VA, QRIS, E-Wallet akan dikonfirmasi otomatis.</div>
                                </div>
                                <div id="metode-manual" style="display: none;">
                                    <select class="form-select rounded-3" id="payment_method_manual" name="payment_method" onchange="togglePaymentDetails()">
                                        <option value="" disabled selected>Pilih Metode Manual</option>
                                        <option value="transfer">Transfer Bank Manual</option>
                                        <option value="qris">QRIS (Manual)</option>
                                        <option value="cod">COD (Bayar di Tempat)</option>
                                    </select>
                                </div>
                            </div>

                            <div id="payment-details-box" class="mt-3 p-3 border rounded-3 bg-light" style="display: none;">
                                <div id="details-transfer" style="display: none;">
                                    <h6 class="fw-bold mb-2">Informasi Rekening</h6>
                                    <p class="mb-1">Bank: <strong>{{ App\Models\Setting::get('payment_bank_name', '-') }}</strong></p>
                                    <p class="mb-1 fw-bold text-success" style="font-size: 1.2rem;">{{ App\Models\Setting::get('payment_bank_account', '-') }}</p>
                                    <p class="mb-0 small text-muted">a.n {{ App\Models\Setting::get('payment_bank_holder', '-') }}</p>
                                </div>
                                <div id="details-qris" class="text-center" style="display: none;">
                                    <h6 class="fw-bold mb-2">Scan QRIS</h6>
                                    @php $qris = App\Models\Setting::get('payment_qris_image'); @endphp
                                    @if($qris)
                                        <img src="{{ asset($qris) }}" class="img-fluid rounded shadow-sm" style="max-width: 200px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                            
                            @foreach($groupedCart as $umkmId => $data)
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-2 small text-muted">
                                        <i class="bi bi-shop me-1 text-success"></i> {{ $data['name'] }}
                                    </div>
                                    @foreach($data['items'] as $item)
                                        <div class="d-flex justify-content-between mb-1 small">
                                            <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                            <span class="fw-bold">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <hr class="my-4">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Produk</span>
                                <span class="fw-bold">Rp {{ number_format(array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))), 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Ongkos Kirim</span>
                                <span id="delivery-fee-display" class="fw-bold text-success">Gratis</span>
                            </div>

                            <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                                <h5 class="fw-bold mb-0">Total Tagihan</h5>
                                <h5 id="total" class="fw-bold text-success mb-0">Rp {{ number_format(array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))), 0, ',', '.') }}</h5>
                            </div>

                            <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0">

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm py-3 fw-bold grow">
                                    Buat Pesanan
                                </button>
                                <a href="{{ route('cart.index') }}" class="btn btn-link text-muted mt-2 small">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Keranjang
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

@push('scripts')
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
        const type = document.querySelector('select[name="delivery_type"]').value;
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
        const detailsQris = document.getElementById('details-qris');
        
        box.style.display = (method === 'transfer' || method === 'qris') ? 'block' : 'none';
        detailsTransfer.style.display = (method === 'transfer') ? 'block' : 'none';
        detailsQris.style.display = (method === 'qris') ? 'block' : 'none';
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
@endpush
@endsection
