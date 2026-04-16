@extends('layouts.dashboard')

@section('title', 'Moderasi Produk')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Moderasi Produk</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title"><i class="bi bi-shield-check me-2"></i>Moderasi Produk</h1>
            <p class="page-subtitle">
                Review dan verifikasi produk yang menunggu persetujuan
            </p>
        </div>
        <div class="col-auto">
            <span class="badge-count">{{ $products->total() }}</span>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter & Search -->
<div class="filter-section mb-4">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="input-group" style="border-radius: 12px; overflow: hidden;">
                <span class="input-group-text border-0" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control border-0" id="searchInput" placeholder="Cari produk atau UMKM..." 
                       style="border-radius: 0; padding: 12px;">
            </div>
        </div>
        <div class="col-md-6">
            <select class="form-select" id="filterKategori" style="border-radius: 12px; border: 2px solid #e2e8f0; height: 48px;">
                <option value="">Semua Kategori</option>
                <option value="Hasil Laut">Hasil Laut</option>
                <option value="Makanan Olahan">Makanan Olahan</option>
                <option value="Bumbu Dapur">Bumbu Dapur</option>
                <option value="Kerajinan">Kerajinan</option>
                <option value="Kuliner">Kuliner</option>
            </select>
        </div>
    </div>
</div>

@if($products->count() > 0)
    <!-- Moderasi Grid -->
    <div class="moderasi-grid">
        @foreach($products as $product)
        <div class="moderasi-card" data-kategori="{{ $product->kategori }}">
            <div class="card-image">
                @if($product->image && file_exists(public_path($product->image)))
                    <img src="{{ asset($product->image) }}" alt="{{ $product->nama_produk }}" class="product-img">
                @else
                    <div class="placeholder-img">
                        <i class="bi bi-image"></i>
                    </div>
                @endif
                <div class="badge-status">
                    <span class="badge-pending">Pending Review</span>
                </div>
            </div>

            <div class="card-body-custom">
                <h5 class="product-name">{{ Str::limit($product->nama_produk, 30) }}</h5>
                <p class="store-name">{{ $product->umkm->nama_toko ?? 'UMKM Tidak Terdaftar' }}</p>

                <div class="product-details">
                    <div class="detail-item">
                        <span class="label">Harga</span>
                        <span class="value">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Stok</span>
                        <span class="value">{{ $product->stok }} unit</span>
                    </div>
                </div>

                @if($product->kategori)
                <div class="kategori-badge">
                    <span>{{ $product->kategori }}</span>
                </div>
                @endif

                @if($product->deskripsi)
                <div class="description-box">
                    <p>{{ Str::limit($product->deskripsi, 80) }}</p>
                </div>
                @endif

                <div class="meta-info">
                    <small><i class="bi bi-calendar me-1"></i>{{ $product->created_at->format('d M Y') }}</small>
                </div>
            </div>

            <div class="card-footer-custom">
                <a href="{{ route('admin.products.show', $product->id) }}" class="btn-action" title="Lihat Detail">
                    <i class="bi bi-eye"></i>
                </a>
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="published">
                    <button type="submit" class="btn-approve w-100" onclick="return confirm('Setujui produk ini?')">
                        <i class="bi bi-check me-1"></i>Setujui
                    </button>
                </form>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-reject w-100" onclick="return confirm('Tolak produk ini?')">
                        <i class="bi bi-x me-1"></i>Tolak
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="pagination-wrapper">
        {{ $products->links() }}
    </div>
    @endif
@else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h4>Tidak ada produk yang menunggu moderasi</h4>
        <p class="text-muted">Semua produk sudah diverifikasi.</p>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Produk
        </a>
    </div>
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

.badge-count {
    background: linear-gradient(135deg, #ffd700, #ffa500);
    color: white;
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
}

/* ========== Filter Section ========== */
.filter-section {
    background: white;
    padding: 20px;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
    border: 1px solid #f0f0f0;
}

.form-control {
    border: none !important;
    background: transparent !important;
    font-size: 0.95rem;
}

.form-control::placeholder {
    color: var(--text-muted);
}

.form-select {
    border: 2px solid #e2e8f0 !important;
    background: white !important;
}

.form-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
}

/* ========== Moderasi Grid ========== */
.moderasi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.moderasi-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid #f0f0f0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.moderasi-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: #e2e8f0;
}

/* Card Image */
.card-image {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    height: 200px;
}

.product-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.moderasi-card:hover .product-img {
    transform: scale(1.05);
}

.placeholder-img {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f0f0f0, #e0e0e0);
}

.placeholder-img i {
    font-size: 3rem;
    color: #ccc;
}

.badge-status {
    position: absolute;
    top: 12px;
    right: 12px;
}

.badge-pending {
    background: linear-gradient(135deg, #ffd700, #ffa500);
    color: #7d5d0f;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
    box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
}

/* Card Body */
.card-body-custom {
    padding: 16px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-name {
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 15px;
}

.store-name {
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 12px;
}

.product-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 12px;
}

.detail-item {
    background: #f7fafc;
    padding: 10px;
    border-radius: 8px;
    border-left: 3px solid #667eea;
}

.detail-item .label {
    display: block;
    color: var(--text-muted);
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 4px;
}

.detail-item .value {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 13px;
}

.kategori-badge {
    display: inline-block;
    background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(56, 161, 105, 0.1));
    color: #22543d;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid rgba(72, 187, 120, 0.3);
    margin-bottom: 12px;
}

.description-box {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 12px;
    flex: 1;
    border-left: 3px solid #667eea;
}

.description-box p {
    color: var(--text-dark);
    font-size: 12px;
    line-height: 1.3;
    margin: 0;
}

.meta-info {
    color: var(--text-muted);
    font-size: 12px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}

/* Card Footer */
.card-footer-custom {
    padding: 12px 16px;
    background: #f7fafc;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 8px;
    align-items: center;
}

.btn-action {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    color: #667eea;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 16px;
    flex-shrink: 0;
}

.btn-action:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.btn-approve {
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    font-weight: 600;
    font-size: 12px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-approve:hover {
    background: linear-gradient(135deg, #38a169, #2f855a);
    transform: translateY(-2px);
}

.btn-reject {
    background: linear-gradient(135deg, #f56565, #e53e3e);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    font-weight: 600;
    font-size: 12px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #e53e3e, #c53030);
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 40px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
    border: 1px solid #f0f0f0;
}

.empty-icon {
    font-size: 72px;
    color: #48bb78;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: var(--text-dark);
    font-weight: 700;
    margin-bottom: 8px;
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 24px;
}

/* Pagination Wrapper */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

@media (max-width: 767.98px) {
    .page-title {
        font-size: 22px;
    }

    .moderasi-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .product-details {
        grid-template-columns: 1fr;
    }

    .card-footer-custom {
        flex-wrap: wrap;
    }

    .btn-action {
        width: 100%;
    }

    .btn-approve,
    .btn-reject {
        width: calc(50% - 4px);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterKategori = document.getElementById('filterKategori');
    const cards = document.querySelectorAll('.moderasi-card');

    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const kategoriTerm = filterKategori ? filterKategori.value : '';

        cards.forEach(card => {
            const productName = card.querySelector('.product-name')?.textContent.toLowerCase() || '';
            const storeName = card.querySelector('.store-name')?.textContent.toLowerCase() || '';
            const kategori = card.dataset.kategori;

            const matchesSearch = productName.includes(searchTerm) || storeName.includes(searchTerm);
            const matchesKategori = !kategoriTerm || kategori === kategoriTerm;

            card.style.display = matchesSearch && matchesKategori ? 'block' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('keyup', filterCards);
    if (filterKategori) filterKategori.addEventListener('change', filterCards);
});
</script>
@endpush
