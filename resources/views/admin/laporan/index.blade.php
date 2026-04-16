@extends('layouts.dashboard')

@section('title', 'Laporan UMKM')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan</li>
        </ol>
    </nav>
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

/* ========== Stats Grid ========== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid #f0f0f0;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-icon.bg-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.stat-icon.bg-success {
    background: linear-gradient(135deg, #48bb78, #38a169);
}

.stat-icon.bg-warning {
    background: linear-gradient(135deg, #ffd700, #ffa500);
}

.stat-icon.bg-info {
    background: linear-gradient(135deg, #4299e1, #3182ce);
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
    margin-bottom: 0.3rem;
}

.stat-label {
    font-size: 0.85rem;
    color: #718096;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0;
}

/* ========== Report Cards ========== */
.report-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    border: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.report-card:hover {
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.report-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-bottom: 1px solid #f0f0f0;
}

.report-header h5 {
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0;
    display: flex;
    align-items: center;
}

.report-header i {
    color: #667eea;
}

.report-body {
    padding: 1.5rem;
}

/* ========== Tables ========== */
.report-body .table {
    margin-bottom: 0;
}

.report-body .table thead th {
    background: #f7fafc;
    border: none;
    color: #2d3748;
    font-weight: 600;
    padding: 12px;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.report-body .table tbody td {
    padding: 12px;
    border-color: #f0f0f0;
    vertical-align: middle;
}

.report-body .table tbody tr:hover {
    background: #f7fafc;
}

.badge {
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

/* ========== Top UMKM List ========== */
.top-umkm-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.top-umkm-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f7fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.top-umkm-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-color: #cbd5e0;
}

.rank-badge {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.umkm-info {
    flex: 1;
    min-width: 0;
}

.umkm-info strong {
    display: block;
    color: #2d3748;
    font-size: 14px;
    margin-bottom: 2px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.umkm-info small {
    color: #718096;
    font-size: 12px;
}

.product-badge {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    flex-shrink: 0;
}

/* ========== Progress Bar ========== */
.progress {
    background: #e2e8f0;
    border-radius: 4px;
}

.progress-bar {
    border-radius: 4px;
    transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

/* ========== Responsive ========== */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-card {
        flex-direction: column;
        text-align: center;
        padding: 1.2rem;
    }

    .stat-value {
        font-size: 1.5rem;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        font-size: 24px;
    }

    .page-title {
        font-size: 22px;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .report-header {
        padding: 1rem;
    }

    .report-body {
        padding: 1rem;
    }

    .top-umkm-item {
        padding: 10px;
    }

    .rank-badge {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}
</style>
@endpush

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Laporan UMKM</h1>
            <p class="page-subtitle">
                Statistik dan laporan data UMKM Kartar Teluknaga
            </p>
        </div>
        <div class="col-auto">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.laporan.export.pdf') ?? '#' }}"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.laporan.export.excel') ?? '#' }}"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="stats-grid mb-5">
    <div class="stat-card" data-aos="fade-up">
        <div class="stat-icon bg-primary">
            <i class="bi bi-shop"></i>
        </div>
        <div class="stat-body">
            <h3 class="stat-value">{{ $totalUmkm ?? 0 }}</h3>
            <p class="stat-label">Total UMKM</p>
        </div>
    </div>
    
    <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-icon bg-success">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-body">
            <h3 class="stat-value">{{ $umkmAktif ?? 0 }}</h3>
            <p class="stat-label">UMKM Aktif</p>
        </div>
    </div>
    
    <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-icon bg-warning">
            <i class="bi bi-clock"></i>
        </div>
        <div class="stat-body">
            <h3 class="stat-value">{{ $umkmPending ?? 0 }}</h3>
            <p class="stat-label">UMKM Pending</p>
        </div>
    </div>
    
    <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-icon bg-info">
            <i class="bi bi-box"></i>
        </div>
        <div class="stat-body">
            <h3 class="stat-value">{{ $totalProduk ?? 0 }}</h3>
            <p class="stat-label">Total Produk</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Data Per Desa -->
    <div class="col-lg-6" data-aos="fade-up">
        <div class="report-card">
            <div class="report-header">
                <h5><i class="bi bi-pie-chart me-2"></i>UMKM per Desa</h5>
            </div>
            <div class="report-body">
                @if($dataPerDesa->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Desa</th>
                                    <th class="text-end">Jumlah UMKM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPerDesa as $data)
                                <tr>
                                    <td>{{ $data->desa }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ $data->total }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bar-chart fs-1 d-block mb-3"></i>
                        <p>Belum ada data UMKM per desa</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Data Per Kategori -->
    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
        <div class="report-card">
            <div class="report-header">
                <h5><i class="bi bi-bar-chart me-2"></i>Produk per Kategori</h5>
            </div>
            <div class="report-body">
                @if($dataPerKategori->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th class="text-end">Jumlah Produk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPerKategori as $data)
                                <tr>
                                    <td>{{ $data->kategori }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-info">{{ $data->total }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-pie-chart fs-1 d-block mb-3"></i>
                        <p>Belum ada data produk per kategori</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- UMKM Pendaftar per Bulan -->
    <div class="col-lg-8" data-aos="fade-up">
        <div class="report-card">
            <div class="report-header">
                <h5><i class="bi bi-graph-up me-2"></i>UMKM Pendaftar (6 Bulan Terakhir)</h5>
            </div>
            <div class="report-body">
                @if(count($umkmPerBulan) > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-end">Jumlah Pendaftar</th>
                                    <th width="40%">Grafik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maxTotal = max($umkmPerBulan) ?: 1;
                                @endphp
                                @foreach($umkmPerBulan as $month => $total)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($month)->format('M Y') }}</td>
                                    <td class="text-end">{{ $total }}</td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ ($total / $maxTotal * 100) }}%; background: linear-gradient(90deg, #667eea, #764ba2);"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-graph-up fs-1 d-block mb-3"></i>
                        <p>Belum ada data pendaftaran UMKM</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top UMKM -->
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="report-card">
            <div class="report-header">
                <h5><i class="bi bi-trophy me-2"></i>Top UMKM</h5>
            </div>
            <div class="report-body">
                @if($topUmkm->count() > 0)
                    <div class="top-umkm-list">
                        @foreach($topUmkm as $index => $umkm)
                        <div class="top-umkm-item">
                            <div class="rank-badge">{{ $index + 1 }}</div>
                            <div class="umkm-info">
                                <strong>{{ $umkm->nama_toko }}</strong>
                                <small class="text-muted d-block">{{ $umkm->pemilik }}</small>
                            </div>
                            <span class="product-badge">{{ $umkm->products_count }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-trophy fs-1 d-block mb-3"></i>
                        <p>Belum ada data UMKM</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
