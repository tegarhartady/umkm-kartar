@extends('layouts.dashboard')

@section('title', 'Kelola Desa')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Kelola Desa</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title"><i class="bi bi-geo-alt me-2"></i>Kelola Desa</h1>
            <p class="page-subtitle">
                Kelola data desa dan kecamatan untuk UMKM
            </p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.desa.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Desa
            </a>
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
    <div class="row g-3">
        <div class="col-md-7">
            <div class="input-group" style="border-radius: 12px; overflow: hidden;">
                <span class="input-group-text border-0" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control border-0" id="searchDesa" placeholder="Cari nama desa..." 
                       style="border-radius: 0; padding: 12px;">
            </div>
        </div>
        <div class="col-md-5">
            <select class="form-select" id="filterKecamatan" style="border-radius: 12px; border: 2px solid #e2e8f0; height: 48px;">
                <option value="">Semua Kecamatan</option>
                <option value="Teluknaga">Teluknaga</option>
                <option value="Muara">Muara</option>
                <option value="Tanjung Pasir">Tanjung Pasir</option>
            </select>
        </div>
    </div>
</div>

<!-- Desa Cards Grid -->
@if($desas->count() > 0)
    <div class="desa-grid">
        @foreach($desas as $desa)
        <div class="desa-card" data-aos="fade-up" data-kecamatan="{{ $desa->kecamatan }}">
            <div class="desa-header">
                <div class="desa-icon">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div class="desa-title-section">
                    <h5 class="desa-name">{{ $desa->nama_desa }}</h5>
                    <p class="desa-location">
                        <i class="bi bi-map me-1"></i>{{ $desa->kecamatan }}, {{ $desa->kabupaten }}
                    </p>
                </div>
            </div>

            @if($desa->deskripsi)
            <div class="desa-description">
                {{ Str::limit($desa->deskripsi, 100) }}
            </div>
            @endif

            <div class="desa-stats">
                <div class="stat-item">
                    <div class="stat-number">{{ $desa->umkms_count ?? 0 }}</div>
                    <div class="stat-text">UMKM Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $desa->jumlah_umkm ?? 0 }}</div>
                    <div class="stat-text">Total UMKM</div>
                </div>
            </div>

            <div class="desa-actions">
                <a href="{{ route('admin.desa.show', $desa->id) ?? '#' }}" class="btn-action" title="Lihat Detail">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.desa.edit', $desa->id) }}" class="btn-action" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <form method="POST" action="{{ route('admin.desa.destroy', $desa->id) ?? '#' }}" class="d-inline"
                      onsubmit="return confirm('Yakin ingin menghapus desa ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action delete" title="Hapus">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($desas->hasPages())
    <div class="pagination-wrapper mt-5">
        <div class="pagination-info">
            Menampilkan {{ $desas->firstItem() }}-{{ $desas->lastItem() }} dari {{ $desas->total() }} data
        </div>
        <nav>
            {{ $desas->links('pagination::bootstrap-5') }}
        </nav>
    </div>
    @endif
@else
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-geo-alt"></i>
        </div>
        <h4>Belum ada desa terdaftar</h4>
        <p class="text-muted">Mulai tambahkan desa pertama Anda</p>
        <a href="{{ route('admin.desa.create') }}" class="btn btn-primary mt-3">
            <i class="bi bi-plus-lg me-2"></i>Tambah Desa
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

/* ========== Desa Grid ========== */
.desa-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.desa-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
}

.desa-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    border-color: #e2e8f0;
}

.desa-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.desa-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.desa-title-section {
    flex: 1;
}

.desa-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.3rem;
    line-height: 1.3;
}

.desa-location {
    font-size: 0.85rem;
    color: #718096;
    margin-bottom: 0;
}

.desa-description {
    padding: 1rem 1.5rem;
    font-size: 0.9rem;
    color: #4a5568;
    line-height: 1.5;
    border-bottom: 1px solid #f0f0f0;
}

.desa-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    padding: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 10px;
    transition: all 0.3s ease;
    border-left: 3px solid #667eea;
}

.stat-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: #667eea;
    line-height: 1;
    margin-bottom: 0.3rem;
}

.stat-text {
    font-size: 0.8rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.desa-actions {
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

/* ========== Empty State ========== */
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
    margin-bottom: 1rem;
    color: var(--text-muted);
}

/* ========== Pagination ========== */
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

@media (max-width: 768px) {
    .desa-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .desa-stats {
        grid-template-columns: 1fr;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        gap: 1rem;
    }

    .page-title {
        font-size: 22px;
    }
}
</style>
@endpush
{{--                         
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $desas->firstItem() }} sampai {{ $desas->lastItem() }} 
                    dari {{ $desas->total() }} desa
                </div>
                {{ $desas->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-geo-alt fs-1 text-muted d-block mb-3"></i>
                <h5>Belum ada data desa</h5>
                <p class="text-muted">Klik tombol "Tambah Desa" untuk menambah data desa pertama</p>
                <a href="{{ route('admin.desa.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus me-2"></i>Tambah Desa
                </a>
            </div>
        @endif
    </div>
</div> --}}

<!-- Modal Konfirmasi Hapus -->
{{-- <div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus desa ini?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchDesa');
    const filterKecamatan = document.getElementById('filterKecamatan');
    const cards = document.querySelectorAll('.desa-card');

    function filterCards() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const kecamatanTerm = filterKecamatan ? filterKecamatan.value : '';

        cards.forEach(card => {
            const desaName = card.querySelector('.desa-name')?.textContent.toLowerCase() || '';
            const kecamatan = card.dataset.kecamatan;

            const matchesSearch = desaName.includes(searchTerm);
            const matchesKecamatan = !kecamatanTerm || kecamatan === kecamatanTerm;

            card.style.display = matchesSearch && matchesKecamatan ? 'block' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('keyup', filterCards);
    if (filterKecamatan) filterKecamatan.addEventListener('change', filterCards);
});

function confirmDelete(desaId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/admin/desa/${desaId}`;
    modal.show();
}
</script>
@endpush
