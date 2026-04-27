@extends('layouts.app')

@section('title', 'Checkout - ' . $product->nama_produk . ' - Karang Taruna Teluknaga')

@section('content')
    <!-- Navigation -->
    {{-- <nav class="navbar navbar-expand-lg navbar-dark" style="background: #333;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-shop me-2"></i>Kartar UMKM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('katalog') }}">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tentang') }}">Tentang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav> --}}

    <div class="checkout-container">
        <div class="container">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="checkout-card">
                <div class="checkout-header">
                    <h2 class="mb-2"><i class="bi bi-cart-check me-3"></i>Checkout Produk</h2>
                    <p class="mb-0 opacity-75">Lengkapi data untuk melanjutkan pembelian</p>
                </div>
                
                <div class="checkout-body">
                    <!-- Product Summary -->
                    <div class="product-summary">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-box me-2 text-primary"></i>Ringkasan Produk
                        </h5>
                        <div class="row align-items-center">
                            <div class="col-auto">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" class="product-image-small">
                                @else
                                    <div class="product-image-small bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col">
                                <h6 class="fw-bold mb-1">{{ $product->nama_produk }}</h6>
                                <p class="text-muted mb-1 small">{{ $product->umkm->nama_toko }}</p>
                                <div class="text-success fw-bold">Rp {{ number_format($product->harga, 0, ',', '.') }} / {{ $product->satuan }}</div>
                            </div>
                        </div>

                        <div class="quantity-selector">
                            <label class="fw-bold">Jumlah:</label>
                            <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ $product->stok }}" class="quantity-input" onchange="updateTotal()">
                            <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                            <span class="text-muted small">Stok: {{ $product->stok }}</span>
                        </div>

                        <div class="total-display">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span id="subtotal">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong id="total" class="text-success fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Form -->
                    <form action="/order/{{ $product->id }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="quantity" id="quantityInput" value="1">
                        
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-person me-2 text-primary"></i>Data Pembeli
                        </h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" value="{{ old('customer_name', $formData['customer_name'] ?? '') }}" placeholder="Nama Lengkap" required>
                                    <label for="customer_name">Nama Lengkap *</label>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" 
                                           id="customer_email" value="{{ old('customer_email', $formData['customer_email'] ?? '') }}" placeholder="Email" required>
                                    <label for="customer_email">Email *</label>
                                    @error('customer_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" 
                                   id="customer_phone" value="{{ old('customer_phone', $formData['customer_phone'] ?? '') }}" placeholder="Nomor WhatsApp" required>
                            <label for="customer_phone">Nomor WhatsApp Aktif *</label>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <textarea name="customer_address" class="form-control @error('customer_address') is-invalid @enderror" 
                                      id="customer_address" placeholder="Alamat Lengkap" required>{{ old('customer_address', $formData['customer_address'] ?? '') }}</textarea>
                            <label for="customer_address">Alamat Lengkap *</label>
                            @error('customer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="security-info">
                            <div class="d-flex">
                                <i class="bi bi-shield-check text-warning me-2 fs-5"></i>
                                <div>
                                    <strong>Informasi Keamanan</strong>
                                    <p class="mb-0 small">Data Anda aman. Kami akan menghubungi melalui WhatsApp untuk konfirmasi pesanan.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="/beli/{{ $product->id }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-checkout btn-lg">
                                <i class="bi bi-credit-card me-2"></i>Lanjut ke Pembayaran
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
        padding: 2rem 0;
    }
    
    .checkout-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 25px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .checkout-header {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        color: white;
        padding: 2.5rem;
        text-align: center;
    }
    
    .checkout-body {
        padding: 3rem;
    }
    
    .form-floating {
        margin-bottom: 1.5rem;
    }
    
    .form-floating > .form-control,
    .form-floating > .form-select {
        border-radius: 15px;
        border: 3px solid #f1f3f4;
        height: 65px;
        transition: all 0.3s ease;
        font-size: 1rem;
        background: #fafafa;
    }
    
    .form-floating > textarea.form-control {
        height: 120px;
        padding-top: 1.625rem;
    }
    
    .form-floating > .form-control:focus,
    .form-floating > .form-select:focus {
        border-color: #ff9a9e;
        box-shadow: 0 0 0 0.2rem rgba(255, 154, 158, 0.25);
        background: white;
    }
    
    .form-floating > label {
        color: #666;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .product-summary {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        border-left: 5px solid #ff9a9e;
    }
    
    .product-image-small {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
    }
    
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1rem 0;
    }
    
    .quantity-btn {
        width: 40px;
        height: 40px;
        border: 2px solid #ff9a9e;
        background: white;
        color: #ff9a9e;
        border-radius: 10px;
        font-weight: bold;
        transition: all 0.3s ease;
    }
    
    .quantity-btn:hover {
        background: #ff9a9e;
        color: white;
    }
    
    .quantity-input {
        width: 80px;
        text-align: center;
        border: 2px solid #f1f3f4;
        border-radius: 10px;
        height: 40px;
        font-weight: bold;
    }
    
    .btn-checkout {
        background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        border: none;
        border-radius: 15px;
        padding: 15px 40px;
        font-weight: bold;
        font-size: 1.1rem;
        color: white;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-checkout:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(255, 154, 158, 0.4);
        color: white;
    }
    
    .total-display {
        background: #e8f5e8;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }
    
    .security-info {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }
</style>
@endpush

@push('scripts')
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
        const subtotal = productPrice * quantity;
        const total = subtotal;
        
        document.getElementById('subtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('quantityInput').value = quantity;
    }

    // Format phone number
    document.getElementById('customer_phone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('0')) {
            value = '62' + value.slice(1);
        } else if (!value.startsWith('62')) {
            value = '62' + value;
        }
        e.target.value = value;
    });
</script>
@endpush
