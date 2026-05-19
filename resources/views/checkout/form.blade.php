@extends('layouts.app')

@section('title', 'Checkout - ' . (isset($product) ? $product->nama_produk : 'Keranjang Belanja'))

@section('content')

<section class="py-5" style="background-color: #f8f9fa; min-height: 100vh; padding-top: 130px !important;">
    <div class="container">
        <div class="row g-4">
            <!-- Form Checkout (Kiri) -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <!-- Header -->
                    <div class="card-header border-0 py-3" style="background-color: #198754;">
                        <h6 class="mb-0 fw-bold text-white"><i class="bi bi-basket3 me-2"></i>Formulir Pemesanan</h6>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <form id="checkoutForm" action="{{ isset($product) ? route('checkout.store') : route('cart.checkout.process') }}" method="POST">
                            @csrf

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(isset($product))
                                <!-- Detail Produk Tunggal (Sesuai Gambar) -->
                                <div class="mb-4 p-4 bg-white rounded-4 border shadow-sm">
                                    <div class="row align-items-center g-3">
                                        <div class="col-auto">
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png') }}" 
                                                 alt="{{ $product->nama_produk }}" 
                                                 class="rounded-3" 
                                                 style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #eee;">
                                        </div>
                                        <div class="col">
                                            <h5 class="fw-bold mb-1">{{ $product->nama_produk }}</h5>
                                            <p class="text-muted small mb-2">{{ $product->umkm->nama_toko ?? 'UMKM' }}</p>
                                            <div class="fw-bold text-success fs-5">
                                                Rp{{ number_format($product->harga, 0, ',', '.') }} 
                                                <small class="text-muted fw-normal">/ {{ $product->satuan ?? 'pcs' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                </div>
                            @else
                                <!-- Ringkasan Keranjang (Dengan Gambar) -->
                                <div class="mb-4 p-4 bg-white rounded-4 border shadow-sm">
                                    <h6 class="fw-bold mb-4 border-bottom pb-2"><i class="bi bi-cart3 me-2 text-success"></i>Daftar Belanja</h6>
                                    @foreach($groupedCart as $umkmId => $data)
                                        <div class="mb-4 last-child-no-border">
                                            <div class="small fw-bold text-primary mb-2"><i class="bi bi-shop me-1"></i> {{ $data['name'] }}</div>
                                            @foreach($data['items'] as $item)
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : asset('images/no-image.png') }}" 
                                                             alt="{{ $item['name'] }}" 
                                                             class="rounded-2 me-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #eee;">
                                                        <div>
                                                            <div class="small fw-bold">{{ $item['name'] }}</div>
                                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $item['quantity'] }} x Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                    <span class="small fw-bold">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Jenis Pesanan & Metode Pengambilan -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold mb-3">Jenis Pesanan <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input class="d-none custom-option-input" type="radio" name="order_type" id="order_instant" value="langsung" checked onchange="togglePoDate()">
                                            <label class="custom-option text-center" for="order_instant">
                                                <span class="fw-bold d-block">Langsung Kirim</span>
                                                <small class="text-muted">Stok tersedia</small>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input class="d-none custom-option-input" type="radio" name="order_type" id="order_po" value="po" onchange="togglePoDate()">
                                            <label class="custom-option text-center" for="order_po">
                                                <span class="fw-bold d-block">Pre-Order</span>
                                                <small class="text-muted">Pesan dulu</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div id="po_date_container" class="mt-3" style="display: none;">
                                        <label class="form-label fw-bold small">Tanggal Kirim/Selesai</label>
                                        <input type="date" class="form-control" name="po_date" id="po_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold mb-3">Metode Pengambilan <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input class="d-none custom-option-input" type="radio" name="delivery_type" id="delivery_takeaway" value="take_away" checked onchange="calculateTotal()">
                                            <label class="custom-option text-center" for="delivery_takeaway">
                                                <span class="fw-bold d-block">Take Away</span>
                                                <small class="text-muted">Ambil sendiri</small>
                                            </label>
                                        </div>
                                        @if((App\Models\Setting::get('delivery_enabled', '0')) == '1')
                                        <div class="col-6">
                                            <input class="d-none custom-option-input" type="radio" name="delivery_type" id="delivery_shipping" value="delivery" onchange="calculateTotal()">
                                            <label class="custom-option text-center" for="delivery_shipping">
                                                <span class="fw-bold d-block">Diantar</span>
                                                <small class="text-muted">Layanan kurir</small>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(isset($product))
                            <!-- Jumlah Pembelian -->
                            <div class="mb-4">
                                <label for="quantity" class="form-label fw-bold mb-2">Jumlah Pembelian <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="form-control border-end-0" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stok }}" required onchange="calculateTotal()">
                                    <span class="input-group-text bg-white">/ {{ $product->satuan ?? 'pcs' }}</span>
                                </div>
                                <div class="mt-2 text-muted small"><i class="bi bi-info-circle me-1"></i> Stok tersedia: <strong>{{ $product->stok }}</strong> {{ $product->satuan ?? 'pcs' }}</div>
                            </div>
                            @endif

                            <!-- Tipe Pembayaran -->
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-3">Pilih Tipe Pembayaran <span class="text-danger">*</span></label>
                                <div class="row g-3">
                                    @if((App\Models\Setting::get('payment_midtrans_enabled', '0')) == '1')
                                    <div class="col-md-6">
                                        <input class="d-none custom-option-input" type="radio" name="payment_type" id="pay_auto" value="auto" checked onchange="togglePaymentType()">
                                        <label class="custom-option d-flex align-items-center p-3" for="pay_auto">
                                            <i class="bi bi-lightning-charge text-warning fs-3 me-3"></i>
                                            <div>
                                                <div class="fw-bold">Pembayaran Otomatis</div>
                                                <small class="text-muted">VA, QRIS, GoPay (Midtrans)</small>
                                            </div>
                                        </label>
                                    </div>
                                    @endif
                                    @if((App\Models\Setting::get('payment_manual_enabled', '1')) == '1')
                                    <div class="col-md-6">
                                        <input class="d-none custom-option-input" type="radio" name="payment_type" id="pay_manual" value="manual" onchange="togglePaymentType()">
                                        <label class="custom-option d-flex align-items-center p-3" for="pay_manual">
                                            <i class="bi bi-wallet2 text-success fs-3 me-3"></i>
                                            <div>
                                                <div class="fw-bold">Pembayaran Manual</div>
                                                <small class="text-muted">Transfer Bank, QRIS, atau COD</small>
                                            </div>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Pilih Metode Manual -->
                            <div id="metode-pembayaran-wrapper" style="display: none;" class="mb-4">
                                <label class="form-label fw-bold mb-2">Pilih Metode <span class="text-danger">*</span></label>
                                <div id="metode-auto" style="display: none;">
                                    <input type="hidden" name="payment_method" value="midtrans">
                                    <div class="alert alert-info py-2 small mb-0"><i class="bi bi-info-circle me-1"></i> Pembayaran melalui Midtrans diverifikasi secara otomatis.</div>
                                </div>
                                <div id="metode-manual" style="display: none;">
                                    <select class="form-select form-select-lg rounded-3" id="payment_method_manual" name="payment_method" onchange="togglePaymentDetails()">
                                        <option value="" disabled selected>Pilih Metode Manual</option>
                                        <option value="transfer">Transfer Bank Manual</option>
                                        <option value="qris">QRIS (Manual Admin)</option>
                                        <option value="qris_event">QRIS UMKM (Khusus Event)</option>
                                        <option value="cod">COD (Bayar di Tempat)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Detail Rekening/QRIS -->
                            <div id="payment-details-box" class="mb-4 p-4 border rounded-4 bg-light shadow-sm text-center" style="display: none;">
                                <div id="details-transfer" style="display: none;">
                                    <h6 class="fw-bold text-success mb-3 text-center">Rekening Pembayaran</h6>
                                    <div class="row g-3 justify-content-center">
                                        @forelse($adminBanks as $bank)
                                        <div class="col-md-6">
                                            <div class="p-3 bg-white rounded-3 border shadow-sm h-100 text-center">
                                                <p class="mb-1 small text-muted text-uppercase fw-bold">{{ $bank->bank_name }}</p>
                                                <p class="mb-1 h5 fw-bold text-primary">{{ $bank->account_number }}</p>
                                                <p class="mb-0 small fw-600">a.n {{ $bank->account_holder }}</p>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="col-12 text-center py-3">
                                            <p class="text-muted mb-0 italic">Belum ada rekening bank yang dikonfigurasi.</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                                <div id="details-qris" style="display: none;">
                                    <h6 class="fw-bold mb-3">Scan QRIS Berikut</h6>
                                    @php $qris = App\Models\Setting::get('payment_qris_image'); @endphp
                                    @if($qris)
                                        <img src="{{ asset($qris) }}" class="img-fluid rounded shadow-sm" style="max-width: 250px;">
                                    @else
                                        <p class="text-muted">Gambar QRIS belum tersedia.</p>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-5">

                            <!-- Informasi Pengiriman -->
                            <h6 class="fw-bold mb-4 text-uppercase" style="letter-spacing: 1px; color: #333;"><i class="bi bi-truck me-2"></i>Informasi Pengiriman</h6>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nama Penerima <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_name" class="form-control p-3 rounded-3" value="{{ old('buyer_name', $user->name ?? '') }}" placeholder="Nama lengkap" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nomor Telepon/WA <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_phone" class="form-control p-3 rounded-3" value="{{ old('buyer_phone', $user->phone ?? '') }}" placeholder="Contoh: 08123456789" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea name="buyer_address" class="form-control p-3 rounded-3" rows="3" required placeholder="Alamat lengkap pengiriman">{{ old('buyer_address', $user->address ?? '') }}</textarea>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kecamatan/Desa <span class="text-danger">*</span></label>
                                    <input type="text" name="buyer_city" class="form-control p-3 rounded-3" value="{{ old('buyer_city', $user->city ?? '') }}" placeholder="Contoh: Teluknaga" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Kode Pos</label>
                                    <input type="text" name="buyer_postal_code" class="form-control p-3 rounded-3" value="{{ old('buyer_postal_code', $user->postal_code ?? '') }}" placeholder="Contoh: 15510">
                                </div>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fw-bold">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" class="form-control p-3 rounded-3" rows="2" placeholder="Misal: Tambah pedas, bungkus terpisah, dll">{{ old('notes') }}</textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" id="btn-submit" class="btn btn-lg py-3 fw-bold text-white shadow-sm hover-grow" style="background-color: #198754; border-radius: 12px;">
                                    <i class="bi bi-cart-check me-2"></i>Konfirmasi & Buat Pesanan
                                </button>
                                <a href="{{ isset($product) ? route('beli', $product->id) : route('cart.index') }}" class="btn btn-link text-muted mt-3">Batal dan Kembali</a>
                            </div>

                            <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0">
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar (Kanan) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top sidebar-checkout">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-success"></i>Ringkasan Pembayaran</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Harga Produk</span>
                            <span id="subtotal-display" class="fw-bold">Rp0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Jumlah Pesanan</span>
                            <span id="qty-display" class="fw-bold">1</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-muted">Subtotal Produk</span>
                            <span id="subtotal-gross-display" class="fw-bold text-dark">Rp0</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3 mb-1">
                            <span class="text-muted">Biaya Pengiriman</span>
                            <span id="delivery-fee-display" class="fw-bold text-success">Gratis</span>
                        </div>
                        <div id="delivery-breakdown" class="text-muted mb-2" style="font-size: 0.75rem; display: none;">
                            <i class="bi bi-info-circle me-1"></i> (Rp<span id="fee-per-umkm">0</span> x <span id="umkm-count-display">0</span> UMKM)
                        </div>
                        <small id="delivery-method-label" class="text-muted d-block mb-3" style="font-size: 0.75rem;">Metode: Ambil Sendiri</small>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <h5 class="fw-bold mb-0">Total Tagihan</h5>
                            <h4 id="total-display" class="fw-bold text-success mb-0">Rp0</h4>
                        </div>
                        
                        <div class="mt-4 p-3 bg-light rounded-4 border">
                            <div class="d-flex gap-2">
                                <i class="bi bi-shield-check text-success fs-5"></i>
                                <small class="text-muted" style="line-height: 1.4;">Transaksi aman. Pesanan akan diteruskan langsung ke UMKM terkait.</small>
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
    .custom-option { padding: 15px; border: 2px solid #e9ecef; border-radius: 12px; transition: all 0.2s; cursor: pointer; background: #fff; height: 100%; display: flex; flex-direction: column; justify-content: center; }
    .custom-option:hover { border-color: #667eea; background: #f8f9ff; }
    .custom-option-input:checked + .custom-option { border-color: #667eea; background: #f8f9ff; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1); }
    
    /* Specific styling for the green boxes in user image */
    .custom-option-input:checked + .custom-option[for="order_instant"],
    .custom-option-input:checked + .custom-option[for="delivery_takeaway"],
    .custom-option-input:checked + .custom-option[for="pay_manual"] {
        border-color: #667eea;
        background: #f8f9ff;
    }

    .sidebar-checkout {
        top: 130px !important;
        z-index: 900;
    }

    @media (max-width: 991.98px) {
        .sidebar-checkout {
            position: relative !important;
            top: 0 !important;
        }
    }

    .hover-grow { transition: transform 0.2s ease; }
    .hover-grow:hover { transform: translateY(-2px); }
    .last-child-no-border:last-child { border-bottom: 0 !important; }
</style>
@endpush

@push('scripts')
@php
    $isProduction = App\Models\Setting::get('midtrans_is_production', '0') == '1';
    $snapSrc = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    $clientKey = App\Models\Setting::get('midtrans_client_key', '');
@endphp
<script src="{{ $snapSrc }}" data-client-key="{{ $clientKey }}"></script>
<script>
    const productPrice = {{ isset($product) ? $product->harga : array_sum(array_map(function($i){ return $i['price']*$i['quantity']; }, session('cart', []))) }};
    const deliveryFeeFlat = {{ App\Models\Setting::get('delivery_fee_flat', 0) }};
    const deliveryFeePerKm = {{ App\Models\Setting::get('delivery_fee_per_km', 0) }};
    const deliveryFeeType = '{{ App\Models\Setting::get('delivery_fee_type', 'flat') }}';
    const umkmCount = {{ isset($product) ? 1 : count($groupedCart ?? []) }};
    const isCart = {{ isset($product) ? 'false' : 'true' }};

    function calculateTotal() {
        const qtyInput = document.getElementById('quantity');
        const qty = qtyInput ? parseInt(qtyInput.value) : 1;
        
        // Update Displays
        if(!isCart) {
            document.getElementById('qty-display').textContent = qty;
            document.getElementById('subtotal-display').textContent = formatIDR(productPrice);
            const subtotalGross = productPrice * qty;
            document.getElementById('subtotal-gross-display').textContent = formatIDR(subtotalGross);
            
            const deliveryType = document.querySelector('input[name="delivery_type"]:checked')?.value;
            let shippingFee = 0;
            if (deliveryType === 'delivery') {
                shippingFee = deliveryFeeType === 'flat' ? deliveryFeeFlat : deliveryFeePerKm;
                shippingFee = shippingFee * umkmCount;
            }
            const grandTotal = subtotalGross + shippingFee;

            document.getElementById('delivery-fee-display').textContent = shippingFee > 0 ? formatIDR(shippingFee) : 'Gratis';
            document.getElementById('delivery-method-label').textContent = 'Metode: ' + (deliveryType === 'delivery' ? 'Kirim Ke Alamat' : 'Ambil Sendiri');
            document.getElementById('delivery_fee_input').value = shippingFee;
            document.getElementById('total-display').textContent = formatIDR(grandTotal);
        } else {
            document.getElementById('qty-display').textContent = '{{ session('cart') ? count(session('cart')) : 0 }} Produk';
            document.getElementById('subtotal-display').textContent = '-';
            
            const subtotalGross = productPrice;
            document.getElementById('subtotal-gross-display').textContent = formatIDR(subtotalGross);
            
            const deliveryType = document.querySelector('input[name="delivery_type"]:checked')?.value;
            let shippingFee = 0;
            let feePerUmkm = deliveryFeeType === 'flat' ? deliveryFeeFlat : deliveryFeePerKm;
            
            if (deliveryType === 'delivery') {
                shippingFee = feePerUmkm * umkmCount;
                document.getElementById('delivery-breakdown').style.display = 'block';
                document.getElementById('fee-per-umkm').textContent = new Intl.NumberFormat('id-ID').format(feePerUmkm);
                document.getElementById('umkm-count-display').textContent = umkmCount;
            } else {
                document.getElementById('delivery-breakdown').style.display = 'none';
            }
            
            const grandTotal = subtotalGross + shippingFee;

            document.getElementById('delivery-fee-display').textContent = shippingFee > 0 ? formatIDR(shippingFee) : 'Gratis';
            document.getElementById('delivery-method-label').textContent = 'Metode: ' + (deliveryType === 'delivery' ? 'Kirim Ke Alamat' : 'Ambil Sendiri');
            document.getElementById('delivery_fee_input').value = shippingFee;
            document.getElementById('total-display').textContent = formatIDR(grandTotal);
        }
    }

    function togglePaymentType() {
        const type = document.querySelector('input[name="payment_type"]:checked')?.value;
        const wrapper = document.getElementById('metode-pembayaran-wrapper');
        const auto = document.getElementById('metode-auto');
        const manual = document.getElementById('metode-manual');
        
        if (!type) {
            if(wrapper) wrapper.style.display = 'none';
            return;
        }

        if(wrapper) wrapper.style.display = 'block';
        if (type === 'auto') {
            if(auto) auto.style.display = 'block'; 
            if(manual) manual.style.display = 'none';
            togglePaymentDetails();
        } else {
            if(auto) auto.style.display = 'none'; 
            if(manual) manual.style.display = 'block';
            togglePaymentDetails();
        }
    }

    function togglePaymentDetails() {
        const typeInput = document.querySelector('input[name="payment_type"]:checked');
        if(!typeInput) return;
        
        const type = typeInput.value;
        const methodSelection = document.getElementById('payment_method_manual');
        const method = type === 'auto' ? 'midtrans' : (methodSelection ? methodSelection.value : '');
        
        const box = document.getElementById('payment-details-box');
        const detailsTransfer = document.getElementById('details-transfer');
        const detailsQris = document.getElementById('details-qris');
        
        if(box) box.style.display = (method === 'transfer' || method === 'qris' || method === 'qris_event') ? 'block' : 'none';
        if(detailsTransfer) detailsTransfer.style.display = (method === 'transfer') ? 'block' : 'none';
        if(detailsQris) {
            detailsQris.style.display = (method === 'qris' || method === 'qris_event') ? 'block' : 'none';
            if (method === 'qris_event') {
                detailsQris.innerHTML = '<h6 class="fw-bold mb-3 text-primary"><i class="bi bi-qr-code-scan me-2"></i>Pembayaran QRIS UMKM (Event)</h6><div class="alert alert-info py-2 mb-0 small"><i class="bi bi-info-circle me-1"></i> QRIS masing-masing UMKM akan ditampilkan pada halaman selanjutnya (setelah konfirmasi pesanan).</div>';
            } else if (method === 'qris') {
                // Restore original details-qris content (we could just use a separate div but let's keep it simple)
                detailsQris.innerHTML = '<h6 class="fw-bold mb-3">Scan QRIS Berikut</h6>@php $qris = App\Models\Setting::get("payment_qris_image"); @endphp @if($qris) <img src="{{ asset($qris) }}" class="img-fluid rounded shadow-sm" style="max-width: 250px;"> @else <p class="text-muted">Gambar QRIS belum tersedia.</p> @endif';
            }
        }
    }

    function togglePoDate() {
        const typeInput = document.querySelector('input[name="order_type"]:checked');
        if(!typeInput) return;
        
        const type = typeInput.value;
        const container = document.getElementById('po_date_container');
        const poDateInput = document.getElementById('po_date');
        
        if(poDateInput) poDateInput.required = (type === 'po');
        if(container) container.style.display = (type === 'po' ? 'block' : 'none');
    }

    function formatIDR(amount) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(amount);
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateTotal();
        togglePaymentType();
        togglePoDate();
    });
</script>
@endpush

@endsection
