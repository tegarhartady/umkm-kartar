@extends('layouts.dashboard')

@section('title', 'Dashboard UMKM')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><i class="bi bi-house me-1"></i>Dashboard</li>
            <li class="breadcrumb-item active">UMKM</li>
        </ol>
    </nav>
@endsection

@section('content')

@if(!$hasUmkm)
    <!-- No UMKM Message -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg text-center py-5">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="bi bi-shop display-1 text-muted"></i>
                    </div>
                    <h4 class="card-title mb-3">Belum Ada UMKM</h4>
                    <p class="text-muted mb-4">
                        Anda belum memiliki UMKM terdaftar. Silakan hubungi admin untuk mendaftarkan UMKM Anda.
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-primary">
                        <i class="bi bi-house me-2"></i>Kembali ke Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Dashboard UMKM</h1>
            <p class="page-subtitle">
                Selamat datang, <strong>{{ auth()->user()->name }}</strong>! 
                Kelola <strong>{{ $data['nama_toko'] ?? 'toko' }}</strong> Anda di sini.
            </p>
        </div>
        <div class="col-auto">
            <div class="page-actions">
                @if($data['umkm_status'] === 'rejected')
                    <span class="badge bg-danger">Status: Ditolak</span>
                @elseif($data['umkm_status'] === 'pending')
                    <span class="badge bg-warning">Status: Menunggu Verifikasi</span>
                @else
                    <span class="badge bg-success">Status: Aktif</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Overview Cards -->
<div class="overview-section">
    <div class="row g-4 mb-4">
        <!-- Total Penjualan -->
        <div class="col-lg-3 col-md-6" data-aos="fade-up">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">Rp {{ number_format($data['total_sales'] ?? 0, 0, ',', '.') }}</h3>
                    <p class="stats-label">Total Penjualan</p>
                    <span class="stats-growth positive">{{ $data['monthly_growth'] ?? 0 }}% bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $data['produk_aktif'] ?? 0 }}</h3>
                    <p class="stats-label">Total Produk Aktif</p>
                    <span class="stats-growth neutral">Dari {{ $data['total_produk'] ?? 0 }} produk</span>
                </div>
            </div>
        </div>

        <!-- Total Pesanan -->
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="stats-card">
                <div class="stats-icon bg-info">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $data['total_orders'] ?? 0 }}</h3>
                    <p class="stats-label">Total Pesanan</p>
                    <span class="stats-growth neutral">Sepanjang waktu</span>
                </div>
            </div>
        </div>

        <!-- Rating Toko -->
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $data['shop_rating'] ?? 0 }}</h3>
                    <p class="stats-label">Rating Toko</p>
                    <span class="stats-growth positive">Dari review pelanggan</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Sections -->
<div class="data-section">
    <div class="row g-4">
        <!-- Produk Terbaru -->
        <div class="col-lg-8" data-aos="fade-up">
            <div class="data-card">
                <div class="data-header">
                    <h5><i class="bi bi-box-seam me-2"></i>Produk Terbaru</h5>
                    <small class="text-muted">{{ count($data['recent_products'] ?? []) }} produk terakhir yang ditambahkan</small>
                </div>
                <div class="data-body">
                    @forelse($data['recent_products'] ?? [] as $product)
                    <div class="product-item">
                        <div class="product-image">
                            @if($product['image'])
                                <img src="{{ asset($product['image']) }}" alt="{{ $product['nama_produk'] }}">
                            @else
                                <img src="https://via.placeholder.com/60" alt="{{ $product['nama_produk'] }}">
                            @endif
                        </div>
                        <div class="product-info">
                            <h6>{{ $product['nama_produk'] }}</h6>
                            <p>Rp {{ number_format($product['harga'] ?? 0, 0, ',', '.') }} 
                                <span class="badge">
                                    {{ $product['status'] === 'published' ? 'Aktif' : 'Draft' }}
                                </span>
                            </p>
                        </div>
                        <div class="product-stats">
                            <span>Stok: {{ $product['stok'] ?? 0 }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-muted">Belum ada produk</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="data-card">
                <div class="data-header">
                    <h5><i class="bi bi-exclamation-triangle me-2"></i>Stok Menipis</h5>
                    <small class="text-muted">Produk dengan stok < 5</small>
                </div>
                <div class="data-body activity-list">
                    @forelse($data['low_stock_products'] ?? [] as $product)
                    <div class="activity-item">
                        <div class="activity-icon" style="background: linear-gradient(135deg, #f6ad55, #ed8936);">
                            <i class="bi bi-exclamation-lg"></i>
                        </div>
                        <div class="activity-content">
                            <p>{{ $product['nama_produk'] }}</p>
                            <small>Stok: {{ $product['stok'] }} - Rp {{ number_format($product['harga'] ?? 0, 0, ',', '.') }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-muted">Semua produk stok aman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Products Alert -->
@if(($data['produk_pending'] ?? 0) > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <strong>Perhatian!</strong> Anda memiliki {{ $data['produk_pending'] }} produk yang menunggu verifikasi.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
</div>
@endif

@endif

@endsection

@push('styles')
<style>
/* ========== Page Header ========== */
.page-header {
    margin-bottom: 32px;
    animation: slideDown 0.6s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 8px;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 15px;
    margin-bottom: 0;
}

.page-actions {
    display: flex;
    gap: 12px;
}

.page-actions .btn {
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.page-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.page-actions .btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border: none;
}

.page-actions .btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
}

/* ========== Stats Cards ========== */
.stats-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    overflow: hidden;
    border: 1px solid #f0f0f0;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: -50%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.5), transparent);
    transition: all 0.6s ease;
}

.stats-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.stats-card:hover::before {
    right: -10%;
}

.stats-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    margin-bottom: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.stats-icon.bg-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
}

.stats-icon.bg-success {
    background: linear-gradient(135deg, #48bb78, #38a169);
}

.stats-icon.bg-info {
    background: linear-gradient(135deg, #4299e1, #3182ce);
}

.stats-icon.bg-warning {
    background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
}

.stats-number {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 4px;
    line-height: 1;
}

.stats-label {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 8px;
    font-weight: 500;
}

.stats-growth {
    font-size: 12px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 12px;
    display: inline-block;
}

.stats-growth.positive {
    background: rgba(72, 187, 120, 0.1);
    color: #22543d;
}

.stats-growth.neutral {
    background: rgba(160, 174, 192, 0.1);
    color: #2d3748;
}

/* ========== Data Cards ========== */
.data-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
    height: 100%;
    border: 1px solid #f0f0f0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.data-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
}

.data-header {
    padding: 24px 24px 16px;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.02), rgba(118, 75, 162, 0.02));
}

.data-header h5 {
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.data-header h5 i {
    color: var(--primary-color);
    font-size: 18px;
}

.data-header small {
    color: var(--text-muted);
    font-size: 12px;
}

.data-body {
    padding: 20px 24px 24px;
}

/* ========== Product Items ========== */
.product-item {
    display: flex;
    align-items: center;
    padding: 16px;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(247, 250, 252, 0.5), rgba(237, 242, 247, 0.5));
}

.product-item:last-child {
    margin-bottom: 0;
}

.product-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-color: #cbd5e0;
}

.product-image {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    margin-right: 12px;
    overflow: hidden;
    background: #f0f0f0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    flex: 1;
}

.product-info h6 {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 14px;
}

.product-info p {
    font-size: 13px;
    color: var(--text-muted);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.product-info .badge {
    font-size: 10px;
    padding: 3px 8px;
    background: rgba(72, 187, 120, 0.1);
    color: #22543d;
    border: 1px solid rgba(72, 187, 120, 0.2);
}

.product-stats {
    font-size: 13px;
    color: var(--primary-color);
    font-weight: 600;
    min-width: 80px;
    text-align: right;
}

/* ========== Activity Items ========== */
.activity-list {
    padding: 0;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 16px;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(247, 250, 252, 0.5), rgba(237, 242, 247, 0.5));
}

.activity-item:last-child {
    margin-bottom: 0;
}

.activity-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-color: #cbd5e0;
}

.activity-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    margin-right: 12px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.activity-content p {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 14px;
}

.activity-content small {
    color: var(--text-muted);
    font-size: 12px;
}

/* ========== Responsive ========== */
@media (max-width: 991.98px) {
    .page-title {
        font-size: 24px;
    }

    .stats-card {
        padding: 20px;
    }

    .data-card .data-header,
    .data-card .data-body {
        padding: 16px 20px;
    }
}

@media (max-width: 767.98px) {
    .page-title {
        font-size: 20px;
    }

    .page-actions {
        flex-direction: column;
        width: 100%;
    }

    .page-actions .btn {
        width: 100%;
    }

    .product-item {
        padding: 12px;
    }

    .product-image {
        width: 50px;
        height: 50px;
    }

    .activity-item {
        padding: 12px;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Initialize animations with AOS
document.addEventListener('DOMContentLoaded', function() {
    if (typeof AOS !== 'undefined') {
        AOS.init();
    }
});
</script>
@endpush