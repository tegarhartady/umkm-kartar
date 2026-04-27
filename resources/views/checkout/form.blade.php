@extends('layouts.app')

@section('title', 'Checkout - Karang Taruna Teluknaga')

@section('content')

<section class="py-5 checkout-section">
    <div class="container">
        <div class="row g-4 mt-3">
            <!-- Form Checkout -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-bag-check"></i> Formulir Pemesanan</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST">
                            @csrf

                            <!-- Informasi Produk -->
                            <div class="mb-4 p-3 bg-light rounded">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="height: 100px;">
                                                <i class="bi bi-image text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-9">
                                        <h6 class="fw-bold mb-1">{{ $product->nama_produk }}</h6>
                                        <p class="text-muted small mb-2">{{ $product->umkm->nama_toko ?? 'UMKM' }}</p>
                                        <p class="mb-0"><span class="h6 fw-bold text-success">Rp{{ number_format($product->harga, 0, ',', '.') }}</span> / {{ $product->satuan ?? 'pcs' }}</p>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <div class="row g-3 mb-4">
                                <!-- Jenis Pesanan -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Jenis Pesanan <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input class="d-none custom-option-input" type="radio" name="order_type" id="order_instant" value="langsung" checked>
                                            <label class="custom-option text-center d-flex flex-column" for="order_instant">
                                                <span class="fw-bold">Langsung Kirim</span>
                                                <small class="text-muted">Stok tersedia</small>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input class="d-none custom-option-input" type="radio" name="order_type" id="order_po" value="po">
                                            <label class="custom-option text-center d-flex flex-column" for="order_po">
                                                <span class="fw-bold">Pre-Order (PO)</span>
                                                <small class="text-muted">Dibuat sesuai pesanan</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Metode Distribusi -->
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Metode Pengambilan <span class="text-danger">*</span></label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input class="d-none custom-option-input" type="radio" name="delivery_type" id="delivery_takeaway" value="take_away" checked onchange="updateDeliveryFee()">
                                            <label class="custom-option text-center d-flex flex-column" for="delivery_takeaway">
                                                <span class="fw-bold">Take Away</span>
                                                <small class="text-muted">Ambil sendiri</small>
                                            </label>
                                        </div>
                                        @if(($settings['delivery_enabled']?->value ?? '0') == '1')
                                        <div class="col-6">
                                            <input class="d-none custom-option-input" type="radio" name="delivery_type" id="delivery_shipping" value="delivery" onchange="updateDeliveryFee()">
                                            <label class="custom-option text-center d-flex flex-column" for="delivery_shipping">
                                                <span class="fw-bold">Diantar</span>
                                                <small class="text-muted">Kirim ke alamat</small>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Jumlah Pembelian -->
                            <div class="mb-4">
                                <label for="quantity" class="form-label fw-bold">Jumlah Pembelian <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                           id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                                           min="1" max="{{ $product->stok }}" required onchange="calculateTotal()">
                                    <span class="input-group-text bg-white">/ {{ $product->satuan ?? 'pcs' }}</span>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle"></i> Stok tersedia: <strong>{{ $product->stok }}</strong> {{ $product->satuan ?? 'pcs' }}
                                </small>
                                @error('quantity')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipe Pembayaran -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Pilih Tipe Pembayaran <span class="text-danger">*</span></label>
                                <div class="row g-3" id="payment-type-selection">
                                    @if(($settings['payment_midtrans_enabled']?->value ?? '0') == '1')
                                    <div class="col-md-6">
                                        <input class="d-none custom-option-input" type="radio" name="payment_type" id="pay_auto" value="auto" onchange="togglePaymentType()">
                                        <label class="custom-option d-flex align-items-center p-3" for="pay_auto">
                                            <div class="me-3 fs-3 text-primary"><i class="bi bi-lightning-charge-fill"></i></div>
                                            <div>
                                                <div class="fw-bold">Pembayaran Otomatis</div>
                                                <small class="text-muted">Konfirmasi instan via Midtrans</small>
                                            </div>
                                        </label>
                                    </div>
                                    @endif

                                    @if(($settings['payment_manual_enabled']?->value ?? '1') == '1')
                                    <div class="col-md-6">
                                        <input class="d-none custom-option-input" type="radio" name="payment_type" id="pay_manual" value="manual" onchange="togglePaymentType()">
                                        <label class="custom-option d-flex align-items-center p-3" for="pay_manual">
                                            <div class="me-3 fs-3 text-secondary"><i class="bi bi-wallet2"></i></div>
                                            <div>
                                                <div class="fw-bold">Pembayaran Manual</div>
                                                <small class="text-muted">Transfer Bank, QRIS, atau COD</small>
                                            </div>
                                        </label>
                                    </div>
                                    @endif
                                </div>
                                @if(($settings['payment_midtrans_enabled']?->value ?? '0') == '0' && ($settings['payment_manual_enabled']?->value ?? '1') == '0')
                                    <div class="alert alert-warning mt-2 small">
                                        <i class="bi bi-exclamation-triangle me-2"></i> Belum ada metode pembayaran yang diaktifkan oleh admin.
                                    </div>
                                @endif
                            </div>

                            <!-- Metode Pembayaran Spesifik -->
                            <div id="metode-pembayaran-wrapper" style="display: none;" class="mb-4">
                                <label for="payment_method" class="form-label fw-bold">Pilih Metode <span class="text-danger">*</span></label>
                                <div id="metode-auto" style="display: none;">
                                    <div class="p-3 border rounded bg-light d-flex align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" id="method_midtrans" value="midtrans" onchange="togglePaymentDetails()">
                                            <label class="form-check-label fw-bold" for="method_midtrans">
                                                Midtrans (VA, GoPay, ShopeePay, Kartu Kredit)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="metode-manual" style="display: none;">
                                    <select class="form-select form-select-lg @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" name="payment_method" onchange="togglePaymentDetails()">
                                         <option value="" disabled selected>Pilih Metode Manual</option>
                                         <option value="transfer">Transfer Bank Manual</option>
                                         <option value="qris">QRIS (Manual)</option>
                                         <option value="cod">COD (Bayar di Tempat)</option>
                                     </select>
                                </div>
                                @error('payment_method')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Payment Details Box -->
                            <div id="payment-details-box" class="mb-4 p-3 border rounded bg-light" style="display: none;">
                                <!-- Bank Transfer Info -->
                                <div id="details-transfer" style="display: none;">
                                    <h6 class="fw-bold mb-2"><i class="bi bi-bank me-2"></i>Informasi Rekening</h6>
                                    <div class="p-2 bg-white rounded border">
                                        <p class="mb-1 small text-muted">Bank: <strong>{{ $settings['payment_bank_name']?->value ?? '-' }}</strong></p>
                                        <p class="mb-1 h6 fw-bold text-primary">{{ $settings['payment_bank_account']?->value ?? '-' }}</p>
                                        <p class="mb-0 small">a.n {{ $settings['payment_bank_holder']?->value ?? '-' }}</p>
                                    </div>
                                </div>

                                <!-- QRIS Info -->
                                <div id="details-qris" class="text-center" style="display: none;">
                                    <h6 class="fw-bold mb-3"><i class="bi bi-qr-code-scan me-2"></i>Scan QRIS untuk Membayar</h6>
                                    @if(isset($settings['payment_qris_image']))
                                        <img src="{{ asset($settings['payment_qris_image']->value) }}" class="img-fluid rounded mb-2 shadow-sm" style="max-width: 250px;">
                                        <p class="small text-muted mb-0">Silakan scan menggunakan aplikasi pembayaran Anda</p>
                                    @else
                                        <div class="alert alert-warning py-2 mb-0 small">QRIS belum dikonfigurasi admin</div>
                                    @endif
                                </div>

                                <!-- Midtrans Info -->
                                <div id="details-midtrans" style="display: none;">
                                    <div class="alert alert-primary border-0 shadow-sm mb-0">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                <i class="bi bi-shield-check fs-2"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1">Pembayaran Otomatis</h6>
                                                <p class="small mb-0">Anda akan diarahkan ke halaman pembayaran aman Midtrans. Mendukung VA Bank, GoPay, ShopeePay, dan Kartu Kredit.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Data Pembeli -->
                            <h6 class="fw-bold mb-3 text-uppercase" style="letter-spacing: 1px;"><i class="bi bi-person-check me-2"></i>Informasi Pengiriman</h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="buyer_name" class="form-label">Nama Penerima <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="buyer_name" name="buyer_name" 
                                           value="{{ old('buyer_name', $user->name) }}" required placeholder="Nama lengkap penerima">
                                </div>

                                <div class="col-md-6">
                                    <label for="buyer_phone" class="form-label">Nomor Telepon/WA <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="buyer_phone" name="buyer_phone" 
                                           value="{{ old('buyer_phone', $user->phone) }}" required placeholder="Contoh: 08123456789">
                                </div>
                            </div>

                            <div class="mb-3" id="address-container">
                                <label for="buyer_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control rounded-3" id="buyer_address" name="buyer_address" 
                                          rows="3" required placeholder="Alamat lengkap pengiriman">{{ old('buyer_address', $user->address) }}</textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="buyer_city" class="form-label">Kecamatan/Desa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3" id="buyer_city" name="buyer_city" 
                                           value="{{ old('buyer_city', $user->city) }}" required placeholder="Contoh: Tangerang">
                                </div>

                                <div class="col-md-6">
                                    <label for="buyer_postal_code" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control rounded-3" id="buyer_postal_code" name="buyer_postal_code" 
                                           value="{{ old('buyer_postal_code', $user->postal_code) }}" placeholder="Contoh: 15510">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">Catatan Tambahan (Opsional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2" 
                                          placeholder="Misal: Tambah pedas, bungkus terpisah, dll">{{ old('notes') }}</textarea>
                            </div>

                            <input type="hidden" name="delivery_fee" id="delivery_fee_input" value="0">

                            <div class="d-grid gap-2">
                                <button type="submit" id="btn-submit-order" class="btn btn-success btn-lg shadow-sm py-3 fw-bold">
                                    <i class="bi bi-cart-check-fill me-2"></i>Konfirmasi & Buat Pesanan
                                </button>
                                <a href="/beli/{{ $product->id }}" class="btn btn-light btn-sm text-muted">
                                    Batal dan Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Ringkasan Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <!-- Item -->
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Harga Produk</span>
                                <strong>Rp{{ number_format($product->harga, 0, ',', '.') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Jumlah Pesanan</span>
                                <strong id="qty-display">1</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Subtotal Produk</span>
                                <strong id="subtotal-product">Rp{{ number_format($product->harga, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <!-- Delivery -->
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Biaya Pengiriman</span>
                                <strong id="delivery-fee-display" class="text-primary">Gratis</strong>
                            </div>
                            <small class="text-muted x-small" id="delivery-note">Metode: Ambil Sendiri</small>
                        </div>

                        <!-- Total -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h6 fw-bold mb-0">Total Tagihan</span>
                                <span class="h4 fw-bold text-success mb-0" id="grand-total">Rp{{ number_format($product->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded mb-0">
                            <p class="small text-muted mb-0"><i class="bi bi-shield-check text-success me-1"></i>Transaksi aman. Pesanan akan diteruskan langsung ke UMKM terkait.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
@php
    $isProduction = ($settings['midtrans_is_production']?->value ?? '0') == '1';
    $snapSrc = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    $clientKey = $settings['midtrans_client_key']?->value ?? '';
@endphp
<script src="{{ $snapSrc }}" data-client-key="{{ $clientKey }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const firstPaymentType = document.querySelector('input[name="payment_type"]');
        if (firstPaymentType) {
            firstPaymentType.checked = true;
            togglePaymentType();
        }

        // Handle Form Submission
        const checkoutForm = document.getElementById('checkoutForm');
        const submitBtn = document.getElementById('btn-submit-order');

        checkoutForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Set Loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses Pesanan...';

            const formData = new FormData(checkoutForm);

            fetch(checkoutForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                const isJson = response.headers.get('content-type')?.includes('application/json');
                const data = isJson ? await response.json() : null;

                if (!response.ok) {
                    if (response.status === 422 && data) {
                        // Validation error
                        let errorMsg = 'Data tidak valid:\n';
                        for (const key in data.errors) {
                            errorMsg += `- ${data.errors[key][0]}\n`;
                        }
                        throw new Error(errorMsg);
                    }
                    throw new Error(data?.message || 'Server mengalami gangguan (Error ' + response.status + ')');
                }
                return data;
            })
            .then(data => {
                if (data.snap_token) {
                    // Trigger Midtrans Snap
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = data.redirect_url;
                        },
                        onPending: function(result) {
                            window.location.href = data.redirect_url;
                        },
                        onError: function(result) {
                            alert("Pembayaran gagal!");
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="bi bi-cart-check-fill me-2"></i>Konfirmasi & Buat Pesanan';
                        },
                        onClose: function() {
                            window.location.href = data.redirect_url;
                        }
                    });
                } else if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Terjadi kesalahan sistem.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-cart-check-fill me-2"></i>Konfirmasi & Buat Pesanan';
            });
        });
    });

    const productPrice = {{ $product->harga }};
    const deliveryEnabled = {{ ($settings['delivery_enabled']?->value ?? '0') == '1' ? 'true' : 'false' }};
    const deliveryFeeType = "{{ $settings['delivery_fee_type']?->value ?? 'flat' }}";
    const deliveryFeeFlat = {{ $settings['delivery_fee_flat']?->value ?? '0' }};
    const deliveryFeePerKm = {{ $settings['delivery_fee_per_km']?->value ?? '0' }};
    const deliveryMinDist = {{ $settings['delivery_min_distance']?->value ?? '0' }};

    function calculateTotal() {
        const quantity = parseInt(document.getElementById('quantity').value) || 1;
        const subtotal = productPrice * quantity;
        
        let deliveryFee = 0;
        const isDelivery = document.querySelector('input[name="delivery_type"]:checked').value === 'delivery';

        if (isDelivery) {
            if (deliveryFeeType === 'flat') {
                deliveryFee = deliveryFeeFlat;
            } else {
                // For distance, we'll use a fixed distance of 1 for now or simulate it
                // Real implementation would need a map/distance API
                deliveryFee = deliveryFeePerKm; 
            }
        }

        const grandTotal = subtotal + deliveryFee;

        // Update displays
        document.getElementById('qty-display').textContent = quantity;
        document.getElementById('subtotal-product').textContent = formatIDR(subtotal);
        document.getElementById('delivery-fee-display').textContent = deliveryFee > 0 ? formatIDR(deliveryFee) : 'Gratis';
        document.getElementById('delivery_fee_input').value = deliveryFee;
        document.getElementById('grand-total').textContent = formatIDR(grandTotal);
        
        document.getElementById('delivery-note').textContent = isDelivery ? 'Metode: Pengantaran' : 'Metode: Ambil Sendiri';
    }

    function togglePaymentType() {
        const type = document.querySelector('input[name="payment_type"]:checked').value;
        const wrapper = document.getElementById('metode-pembayaran-wrapper');
        const auto = document.getElementById('metode-auto');
        const manual = document.getElementById('metode-manual');
        const methodSelect = document.getElementById('payment_method');
        const methodMidtrans = document.getElementById('method_midtrans');

        wrapper.style.display = 'block';
        if (type === 'auto') {
            auto.style.display = 'block';
            manual.style.display = 'none';
            methodMidtrans.checked = true;
            methodSelect.value = ""; // Clear manual select
            togglePaymentDetails(); // Trigger details show for midtrans
        } else {
            auto.style.display = 'none';
            manual.style.display = 'block';
            methodMidtrans.checked = false;
            togglePaymentDetails(); // Trigger hide all details until manual select is chosen
        }
    }

    function togglePaymentDetails() {
        let method = "";
        const type = document.querySelector('input[name="payment_type"]:checked')?.value;
        
        if (type === 'auto') {
            method = "midtrans";
        } else {
            method = document.getElementById('payment_method').value;
        }

        const box = document.getElementById('payment-details-box');
        const detailsTransfer = document.getElementById('details-transfer');
        const detailsQris = document.getElementById('details-qris');
        const detailsMidtrans = document.getElementById('details-midtrans');

        box.style.display = 'none';
        detailsTransfer.style.display = 'none';
        detailsQris.style.display = 'none';
        detailsMidtrans.style.display = 'none';

        if (method === 'transfer' || method === 'qris' || method === 'midtrans') {
            box.style.display = 'block';
            if (method === 'transfer') detailsTransfer.style.display = 'block';
            if (method === 'qris') detailsQris.style.display = 'block';
            if (method === 'midtrans') detailsMidtrans.style.display = 'block';
        }
    }

    function updateDeliveryFee() {
        calculateTotal();
    }

    function formatIDR(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        calculateTotal();
        togglePaymentDetails();
    });
</script>
@endpush

@push('styles')
<style>
    .custom-option {
        padding: 12px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.2s;
        cursor: pointer;
        height: 100%;
        margin-bottom: 0;
    }
    .custom-option:hover { 
        border-color: #667eea; 
        background: #f8fafc; 
    }
    .custom-option-input:checked + .custom-option { 
        border-color: #667eea; 
        background: #f0f4ff;
        box-shadow: 0 0 0 1px #667eea;
    }
    .custom-option-input:checked + .custom-option .fw-bold {
        color: #667eea;
    }
    
    .x-small { font-size: 11px; }
    .grow { transition: transform 0.2s; }
    .grow:hover { transform: scale(1.02); }
    
    .checkout-section {
        padding-top: 100px !important;
    }

    .sticky-top { 
        top: 110px !important; 
        z-index: 900;
    }

    @media (max-width: 768px) {
        .sticky-top { position: static !important; margin-top: 2rem; }
        .custom-option { padding: 8px 12px; }
    }
</style>
@endpush
