@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Karang Taruna Teluknaga')

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

    <div class="success-container">
        <div class="container">
            <div class="success-card">
                <div class="success-header">
                    <i class="bi bi-check-circle-fill success-icon"></i>
                    <h2 class="mb-2">Pesanan Berhasil!</h2>
                    <p class="mb-0 opacity-75">Terima kasih atas pesanan Anda</p>
                </div>
                
                <div class="success-body">
                    <!-- Order Info -->
                    <div class="order-info">
                        <div class="row align-items-center mb-3">
                            <div class="col">
                                <h5 class="fw-bold mb-0">
                                    <i class="bi bi-receipt me-2"></i>Nomor Pesanan: #{{ $order->order_number }}
                                </h5>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-success fs-6">Berhasil Dibuat</span>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Produk:</strong><br>
                                <span class="text-muted">{{ $order->product->nama_produk }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>UMKM:</strong><br>
                                <span class="text-muted">{{ $order->product->umkm->nama_toko }}</span>
                            </div>
                            <div class="col-md-6 mt-3">
                                <strong>Jumlah:</strong><br>
                                <span class="text-muted">{{ $order->quantity }} {{ $order->product->satuan }}</span>
                            </div>
                            <div class="col-md-6 mt-3">
                                <strong>Total:</strong><br>
                                <span class="text-success fw-bold fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="next-steps">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-info-circle me-2"></i>Langkah Selanjutnya
                        </h5>
                        <div class="order-timeline">
                            <div class="timeline-item active">
                                <strong>Pesanan Diterima</strong>
                                <p class="text-muted mb-0 small">Pesanan Anda telah berhasil dibuat dan diterima sistem.</p>
                            </div>
                            <div class="timeline-item">
                                <strong>Konfirmasi UMKM</strong>
                                <p class="text-muted mb-0 small">UMKM akan mengkonfirmasi ketersediaan produk.</p>
                            </div>
                            <div class="timeline-item">
                                <strong>Proses & Pengiriman</strong>
                                <p class="text-muted mb-0 small">Produk akan diproses dan dikirim sesuai alamat.</p>
                            </div>
                            <div class="timeline-item">
                                <strong>Pesanan Selesai</strong>
                                <p class="text-muted mb-0 small">Produk diterima dan transaksi selesai.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="alert alert-info">
                        <h6 class="fw-bold">
                            <i class="bi bi-telephone me-2"></i>Informasi Kontak
                        </h6>
                        <p class="mb-2">UMKM akan menghubungi Anda melalui WhatsApp untuk konfirmasi pesanan dan detail pengiriman.</p>
                        <p class="mb-0"><strong>Nomor WhatsApp Anda:</strong> {{ $order->customer_phone }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <a href="https://wa.me/{{ $order->product->umkm->phone }}?text=Halo, saya ingin bertanya tentang pesanan #{{ $order->order_number }}" 
                               class="whatsapp-btn d-block text-center" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>Hubungi UMKM
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('katalog') }}" class="btn-success-action d-block text-center">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
                            </a>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-4 text-center">
                        <p class="text-muted">
                            <i class="bi bi-clock me-2"></i>
                            Pesanan dibuat pada {{ $order->created_at->format('d M Y, H:i') }}
                        </p>
                        <small class="text-muted">
                            Jika ada pertanyaan, jangan ragu untuk menghubungi UMKM terkait atau admin sistem.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .success-container {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .success-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 25px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .success-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 3rem;
        text-align: center;
    }
    
    .success-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        animation: bounceIn 1s ease-out;
    }
    
    @keyframes bounceIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .success-body {
        padding: 3rem;
    }
    
    .order-info {
        background: #e8f5e8;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        border-left: 5px solid #28a745;
    }
    
    .next-steps {
        background: #fff3cd;
        border: 2px solid #ffeaa7;
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
    }
    
    .btn-success-action {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: bold;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-success-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(40, 167, 69, 0.3);
        color: white;
    }
    
    .whatsapp-btn {
        background: #25D366;
        color: white;
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .whatsapp-btn:hover {
        background: #128C7E;
        color: white;
        transform: translateY(-2px);
    }
    
    .order-timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }
    
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #28a745;
    }
    
    .timeline-item:after {
        content: '';
        position: absolute;
        left: -1.75rem;
        top: 1.2rem;
        width: 2px;
        height: calc(100% - 0.7rem);
        background: #dee2e6;
    }
    
    .timeline-item:last-child:after {
        display: none;
    }
    
    .timeline-item.active:before {
        background: #28a745;
    }
    
    .timeline-item:not(.active):before {
        background: #dee2e6;
    }
</style>
@endpush

@push('scripts')
@endpush
