@extends('layouts.dashboard')

@section('title', 'Verifikasi UMKM')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item">Kelola UMKM</li>
            <li class="breadcrumb-item active">Verifikasi</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title"><i class="bi bi-check-circle me-2"></i>Verifikasi UMKM</h1>
            <p class="page-subtitle">
                Review dan verifikasi pendaftaran UMKM baru yang menunggu persetujuan
            </p>
        </div>
        <div class="col-auto">
            <span class="badge-count">{{ $umkms->total() }}</span>
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
                <input type="text" class="form-control border-0" id="searchInput" placeholder="Cari UMKM atau pemilik..." 
                       style="border-radius: 0; padding: 12px;">
            </div>
        </div>
        <div class="col-md-6">
            <select class="form-select" id="filterDesa" style="border-radius: 12px; border: 2px solid #e2e8f0; height: 48px;">
                <option value="">Semua Desa</option>
                <option value="Teluknaga">Teluknaga</option>
                <option value="Tanjung Pasir">Tanjung Pasir</option>
                <option value="Muara">Muara</option>
                <option value="Lemo">Lemo</option>
                <option value="Pangkalan">Pangkalan</option>
            </select>
        </div>
    </div>
</div>

<!-- Verifikasi Grid -->
<div class="verifikasi-grid">
    @forelse($umkms as $umkm)
    <div class="verifikasi-card" data-desa="{{ $umkm->desa }}">
        <div class="card-header-custom">
            <div class="shop-icon">
                <i class="bi bi-shop"></i>
            </div>
            <div class="header-content">
                <h5 class="shop-name">{{ $umkm->nama_toko }}</h5>
                <p class="owner-name">{{ $umkm->pemilik }}</p>
            </div>
            <span class="badge-pending">Pending</span>
        </div>

        <div class="card-body-custom">
            <div class="info-section">
                <div class="info-row">
                    <span class="label"><i class="bi bi-envelope me-1"></i>Email</span>
                    <span class="value">{{ $umkm->email }}</span>
                </div>
                <div class="info-row">
                    <span class="label"><i class="bi bi-telephone me-1"></i>Telepon</span>
                    <span class="value">{{ $umkm->phone }}</span>
                </div>
                <div class="info-row">
                    <span class="label"><i class="bi bi-geo-alt me-1"></i>Lokasi</span>
                    <span class="value">{{ $umkm->desa }}</span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-item">
                    <span class="detail-label">Kategori</span>
                    <span class="badge-kategori">{{ $umkm->kategori }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Omzet Bulanan</span>
                    <span class="detail-value">
                        @if($umkm->omzet_bulanan)
                            Rp {{ number_format($umkm->omzet_bulanan, 0, ',', '.') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </span>
                </div>
            </div>

            @if($umkm->deskripsi)
            <div class="description-box">
                <small class="text-muted">Deskripsi:</small>
                <p class="mb-0">{{ Str::limit($umkm->deskripsi, 120) }}</p>
            </div>
            @endif

            <div class="meta-info">
                <small><i class="bi bi-calendar me-1"></i>{{ $umkm->created_at->format('d M Y H:i') }}</small>
            </div>
        </div>

        <div class="card-footer-custom">
            <a href="{{ route('admin.umkm.show', $umkm) }}" class="btn-action" title="Lihat Detail">
                <i class="bi bi-eye"></i>
            </a>
            <form method="POST" action="{{ route('admin.umkm.approve', $umkm) }}" class="d-inline" style="flex: 1;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-approve w-100" onclick="return confirm('Setujui pendaftaran UMKM ini?')">
                    <i class="bi bi-check me-1"></i>Setujui
                </button>
            </form>
            <form method="POST" action="{{ route('admin.umkm.reject', $umkm) }}" class="d-inline" style="flex: 1;">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn-reject w-100" onclick="return confirm('Tolak pendaftaran UMKM ini?')">
                    <i class="bi bi-x me-1"></i>Tolak
                </button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column: 1 / -1;">
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <h4>Tidak ada UMKM yang menunggu verifikasi</h4>
            <p class="text-muted">Semua pendaftaran UMKM sudah diverifikasi.</p>
            <a href="{{ route('admin.umkm.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar UMKM
            </a>
        </div>
    </div>
    @endforelse
</div>

@if($umkms->hasPages())
<div class="pagination-wrapper">
    {{ $umkms->links() }}
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
    display: flex;
    align-items: center;
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

.input-group-text {
    background: linear-gradient(135deg, #667eea, #764ba2) !important;
    border: none !important;
    color: white !important;
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

/* ========== Verifikasi Grid ========== */
.verifikasi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.verifikasi-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid #f0f0f0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.verifikasi-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: #e2e8f0;
}

/* Card Header */
.card-header-custom {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    padding: 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
}

.shop-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    flex-shrink: 0;
}

.header-content {
    flex: 1;
    min-width: 0;
}

.shop-name {
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 16px;
}

.owner-name {
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 0;
}

.badge-pending {
    background: linear-gradient(135deg, #ffd700, #ffa500);
    color: #7d5d0f;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

/* Card Body */
.card-body-custom {
    padding: 20px;
    flex: 1;
}

.info-section {
    margin-bottom: 16px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding: 8px 12px;
    background: #f7fafc;
    border-radius: 8px;
}

.info-row .label {
    font-weight: 500;
    color: var(--text-muted);
    font-size: 13px;
    display: flex;
    align-items: center;
}

.info-row .value {
    color: var(--text-dark);
    font-size: 13px;
    font-weight: 500;
}

.detail-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 16px;
}

.detail-item {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    padding: 12px;
    border-radius: 10px;
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.detail-label {
    display: block;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 6px;
}

.badge-kategori {
    display: inline-block;
    background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(56, 161, 105, 0.1));
    color: #22543d;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid rgba(72, 187, 120, 0.3);
}

.detail-value {
    color: var(--text-dark);
    font-weight: 600;
    font-size: 13px;
}

.description-box {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 12px;
    border-left: 3px solid #667eea;
}

.description-box p {
    color: var(--text-dark);
    font-size: 13px;
    line-height: 1.4;
}

.meta-info {
    color: var(--text-muted);
    font-size: 12px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}

/* Card Footer */
.card-footer-custom {
    padding: 16px 20px;
    background: #f7fafc;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-action {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    color: #667eea;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    text-decoration: none;
    font-size: 18px;
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
    border-radius: 10px;
    padding: 10px 16px;
    font-weight: 600;
    font-size: 13px;
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
    border-radius: 10px;
    padding: 10px 16px;
    font-weight: 600;
    font-size: 13px;
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

@media (max-width: 991.98px) {
    .verifikasi-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}

@media (max-width: 767.98px) {
    .page-title {
        font-size: 22px;
    }

    .verifikasi-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .detail-row {
        grid-template-columns: 1fr;
    }

    .card-footer-custom {
        flex-direction: column;
    }

    .btn-action {
        width: 100%;
    }

    .btn-approve,
    .btn-reject {
        width: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterDesa = document.getElementById('filterDesa');
    const cards = document.querySelectorAll('.verifikasi-card');

    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const desaTerm = filterDesa ? filterDesa.value : '';

        cards.forEach(card => {
            const shopName = card.querySelector('.shop-name')?.textContent.toLowerCase() || '';
            const ownerName = card.querySelector('.owner-name')?.textContent.toLowerCase() || '';
            const desa = card.dataset.desa;

            const matchesSearch = shopName.includes(searchTerm) || ownerName.includes(searchTerm);
            const matchesDesa = !desaTerm || desa === desaTerm;

            card.style.display = matchesSearch && matchesDesa ? 'flex' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('keyup', filterCards);
    if (filterDesa) filterDesa.addEventListener('change', filterCards);
});
</script>
@endpush
