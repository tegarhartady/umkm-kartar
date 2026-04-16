@extends('layouts.app')

@section('title', 'Pembayaran - Karang Taruna Teluknaga')

@section('content')
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #333;">
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
    </nav>

    <div class="payment-container">
        <div class="container">
            <div class="payment-card">
                <div class="payment-header">
                    <h2 class="mb-2"><i class="bi bi-credit-card me-3"></i>Pembayaran</h2>
                    <p class="mb-0 opacity-75">Order #{{ $order->order_number }}</p>
                </div>
                
                <div class="payment-body">
                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-receipt me-2 text-primary"></i>Detail Pesanan
                        </h5>
                        <div class="order-details">
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>Produk:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $order->product->nama_produk }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>UMKM:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $order->product->umkm->nama_toko }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>Jumlah:</strong>
                                </div>
                                <div class="col-6">
                                    {{ $order->quantity }} {{ $order->product->satuan }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>Harga Satuan:</strong>
                                </div>
                                <div class="col-6">
                                    Rp {{ number_format($order->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <strong class="fs-5">Total Pembayaran:</strong>
                                </div>
                                <div class="col-6">
                                    <strong class="fs-5 text-success">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-person me-2 text-primary"></i>Data Pembeli
                        </h5>
                        <div class="row">
                            <div class="col-6">
                                <strong>Nama:</strong><br>
                                <span class="text-muted">{{ $order->customer_name }}</span>
                            </div>
                            <div class="col-6">
                                <strong>Email:</strong><br>
                                <span class="text-muted">{{ $order->customer_email }}</span>
                            </div>
                            <div class="col-6 mt-3">
                                <strong>WhatsApp:</strong><br>
                                <span class="text-muted">{{ $order->customer_phone }}</span>
                            </div>
                            <div class="col-6 mt-3">
                                <strong>Alamat:</strong><br>
                                <span class="text-muted">{{ Str::limit($order->customer_address, 50) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-wallet2 me-2 text-primary"></i>Pilih Metode Pembayaran
                    </h5>

                    <form action="{{ route('catalog.payment.process', $order) }}" method="POST">
                        @csrf
                        
                        <div class="payment-methods">
                            <div class="payment-method" onclick="selectPaymentMethod('bank_transfer')">
                                <input type="radio" name="payment_method" value="bank_transfer" id="bank_transfer" style="display: none;" checked>
                                <i class="bi bi-bank"></i>
                                <h6 class="fw-bold">Transfer Bank</h6>
                                <p class="text-muted mb-0 small">BCA, Mandiri, BRI, BNI</p>
                            </div>
                            
                            <div class="payment-method" onclick="selectPaymentMethod('e_wallet')">
                                <input type="radio" name="payment_method" value="e_wallet" id="e_wallet" style="display: none;">
                                <i class="bi bi-phone"></i>
                                <h6 class="fw-bold">E-Wallet</h6>
                                <p class="text-muted mb-0 small">GoPay, OVO, DANA, ShopeePay</p>
                            </div>
                            
                            <div class="payment-method" onclick="selectPaymentMethod('cod')">
                                <input type="radio" name="payment_method" value="cod" id="cod" style="display: none;">
                                <i class="bi bi-cash"></i>
                                <h6 class="fw-bold">Bayar di Tempat</h6>
                                <p class="text-muted mb-0 small">Cash on Delivery (COD)</p>
                            </div>
                        </div>

                        <div class="security-badge">
                            <i class="bi bi-shield-check text-success fs-4"></i>
                            <div class="mt-2">
                                <strong>Pembayaran Aman</strong>
                                <p class="mb-0 small text-muted">Transaksi Anda dilindungi dengan enkripsi SSL</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('catalog.checkout', $order->product) }}" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-pay btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Bayar Sekarang
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
    .payment-container {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .payment-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 25px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .payment-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 2.5rem;
        text-align: center;
    }
    
    .payment-body {
        padding: 3rem;
    }
    
    .order-summary {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .payment-methods {
        display: grid;
        gap: 1rem;
        margin: 2rem 0;
    }
    
    .payment-method {
        border: 3px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .payment-method:hover {
        border-color: #4facfe;
        background: rgba(79, 172, 254, 0.1);
    }
    
    .payment-method.selected {
        border-color: #4facfe;
        background: rgba(79, 172, 254, 0.2);
    }
    
    .payment-method i {
        font-size: 2rem;
        color: #4facfe;
        margin-bottom: 1rem;
    }
    
    .btn-pay {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
    
    .btn-pay:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(79, 172, 254, 0.4);
        color: white;
    }
    
    .security-badge {
        background: #e8f5e8;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1.5rem;
        text-align: center;
    }
    
    .order-details {
        border-left: 4px solid #4facfe;
        padding-left: 1rem;
    }
</style>
@endpush

@push('scripts')
<script>
    function selectPaymentMethod(method) {
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });
        
        event.currentTarget.classList.add('selected');
        document.getElementById(method).checked = true;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        selectPaymentMethod('bank_transfer');
    });
</script>
@endpush
