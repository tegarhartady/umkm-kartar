@extends('layouts.dashboard')

@section('title', 'Daftar UMKM')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><i class="bi bi-house me-1"></i>Dashboard</li>
            <li class="breadcrumb-item">Kelola UMKM</li>
            <li class="breadcrumb-item active">Daftar UMKM</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Daftar UMKM</h1>
            <p class="page-subtitle">
                Kelola semua UMKM yang terdaftar di platform
            </p>
        </div>
        <div class="col-auto">
            <div class="page-actions">
                <a href="{{ route('admin.umkm.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah UMKM
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter & Search -->
<div class="filter-section mb-4">
    <form action="{{ route('admin.umkm.index') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-md-5">
            <div class="input-group input-group-lg search-box">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama toko, pemilik, atau desa..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select form-select-lg">
                <option value="">Semua Status</option>
                <option value="disetujui" {{ in_array(request('status'), ['disetujui', 'approved']) ? 'selected' : '' }}>Disetujui</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="ditolak" {{ in_array(request('status'), ['ditolak', 'rejected']) ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg flex-grow-1"><i class="bi bi-filter me-1"></i>Filter</button>
            <a href="{{ route('admin.umkm.index') }}" class="btn btn-light btn-lg"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
        </div>
    </form>
</div>

<!-- UMKM Grid View -->
<div class="umkm-grid">
    @forelse($umkms as $umkm)
    <div class="umkm-card" data-aos="fade-up">
        <div class="card-header-custom">
            <div class="d-flex justify-content-between align-items-start">
                <div class="shop-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <span class="badge-status 
                    @if($umkm->status == 'approved' || $umkm->status == 'disetujui') bg-success 
                    @elseif($umkm->status == 'pending') bg-warning 
                    @else bg-danger @endif">
                    {{ $umkm->status == 'approved' || $umkm->status == 'disetujui' ? 'Disetujui' : ($umkm->status == 'pending' ? 'Menunggu' : 'Ditolak') }}
                </span>
            </div>
        </div>
        
        <div class="card-body-custom">
            <h5 class="shop-name">{{ $umkm->nama_toko }}</h5>
            <p class="shop-owner"><i class="bi bi-person-fill me-1"></i>{{ $umkm->pemilik }}</p>
            
            <div class="info-group">
                <div class="info-item">
                    <span class="label">Desa</span>
                    <span class="value">{{ $umkm->desa ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="label">Kategori</span>
                    <span class="value">{{ $umkm->kategori ?? '-' }}</span>
                </div>
            </div>
            
            <div class="stats-row">
                <div class="stat">
                    <div class="stat-value">{{ $umkm->products_count ?? 0 }}</div>
                    <div class="stat-label">Produk</div>
                </div>
                <div class="stat">
                    <div class="stat-value email-value" title="{{ $umkm->email }}">{{ $umkm->email }}</div>
                    <div class="stat-label">Email</div>
                </div>
            </div>
        </div>
        
        <div class="card-footer-custom">
            <a href="{{ route('admin.umkm.show', $umkm) }}" class="btn-action" title="Lihat Detail">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('admin.umkm.edit', $umkm) }}" class="btn-action" title="Edit">
                <i class="bi bi-pencil-square"></i>
            </a>
            <form method="POST" action="{{ route('admin.umkm.destroy', $umkm) }}" class="d-inline" 
                  onsubmit="return confirm('Yakin ingin menghapus UMKM ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-action delete" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state col-12">
        <div class="empty-icon">
            <i class="bi bi-inbox"></i>
        </div>
        <h4>Belum ada UMKM terdaftar</h4>
        <p class="text-muted">Mulai tambahkan UMKM pertama Anda</p>
        <a href="{{ route('admin.umkm.create') }}" class="btn btn-primary mt-3">
            <i class="bi bi-plus-lg me-2"></i>Tambah UMKM
        </a>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($umkms->hasPages())
<div class="pagination-wrapper mt-5">
    <div class="pagination-info">
        Menampilkan {{ $umkms->firstItem() }}-{{ $umkms->lastItem() }} dari {{ $umkms->total() }} data
    </div>
    <nav>
        {{ $umkms->links('pagination::bootstrap-5') }}
    </nav>
</div>
@endif

@endsection

@push('styles')
<style>
/* Filter Section */
.filter-section {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
}

.search-box .input-group-text {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    color: #667eea;
}

.search-box .form-control {
    border: 2px solid #e9ecef;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-box .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-select-lg {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-select-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* UMKM Grid */
.umkm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.umkm-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
}

.umkm-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: #e2e8f0;
}

.card-header-custom {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    padding: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
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
}

.badge-status {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    text-transform: capitalize;
}

.card-body-custom {
    padding: 1.5rem;
    flex-grow: 1;
}

.shop-name {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.shop-owner {
    font-size: 0.9rem;
    color: #718096;
    margin-bottom: 1rem;
}

.info-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f0f0f0;
}

.info-item .label {
    display: block;
    font-size: 0.8rem;
    color: #a0aec0;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.info-item .value {
    display: block;
    font-size: 0.95rem;
    color: #2d3748;
    font-weight: 600;
}

.stats-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat {
    text-align: center;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.stat:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.8rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.email-value {
    font-size: 0.9rem !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: block;
    width: 100%;
    padding: 0 5px;
}

.card-footer-custom {
    padding: 1rem 1.5rem;
    background: #f7fafc;
    border-top: 1px solid #f0f0f0;
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

.btn-action {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    color: #667eea;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
}

.btn-action:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
}

.btn-action.delete {
    color: #f56565;
}

.btn-action.delete:hover {
    background: #f56565;
    color: white;
    border-color: #f56565;
}

/* Empty State */
.empty-state {
    background: white;
    border-radius: 16px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #2d3748;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.empty-state p {
    margin-bottom: 0;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
}

.pagination-info {
    font-size: 0.9rem;
    color: #718096;
}

.pagination-wrapper nav {
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .umkm-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .filter-section {
        padding: 1rem;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        gap: 1rem;
    }
    
    .info-group {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush


