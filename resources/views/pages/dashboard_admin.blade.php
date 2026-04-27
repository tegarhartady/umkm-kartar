@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><i class="bi bi-house me-1"></i>Dashboard</li>
            <li class="breadcrumb-item active">Overview</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-subtitle">
                Selamat datang kembali, <strong>{{ auth()->user()->name }}</strong>! 
                Berikut adalah ringkasan aktivitas UMKM hari ini.
            </p>
        </div>
        <div class="col-auto">
            <div class="page-actions">
                <a href="#" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Export Data
                </a>
                <a href="{{ route('admin.umkm.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah UMKM
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Overview Cards -->
<div class="overview-section">
    <div class="row g-4 mb-4">
        <!-- Total Omzet -->
        <div class="col-lg-3 col-md-6" data-aos="fade-up">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $data['total_omzet'] }}M</h3>
                    <p class="stats-label">Total Omzet Global</p>
                    <span class="stats-growth positive">+12.5%</span>
                </div>
            </div>
        </div>

        <!-- UMKM Terdaftar -->
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $data['umkm_terdaftar'] }}</h3>
                    <p class="stats-label">UMKM Terdaftar</p>
                    <span class="stats-growth positive">+{{ $data['umkm_baru'] }} UMKM</span>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="stats-card">
                <div class="stats-icon bg-info">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $data['total_transaksi'] }}</h3>
                    <p class="stats-label">Total Transaksi</p>
                    <span class="stats-growth positive">✓ {{ $data['transaksi_berhasil'] }} Berhasil</span>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">Rp{{ number_format($data['total_revenue'] / 1000000, 1) }}M</h3>
                    <p class="stats-label">Total Revenue (Transaksi)</p>
                    <span class="stats-growth positive">Dari checkout online</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data & Verification Section -->
<div class="data-section">
    <div class="row g-4">
        <!-- Recent Transactions -->
        <div class="col-lg-8" data-aos="fade-up">
            <div class="data-card">
                <div class="data-header">
                    <h5><i class="bi bi-receipt me-2"></i>Transaksi Terbaru</h5>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="data-body">
                    @forelse($data['transaksi_terbaru'] as $txn)
                    <div class="transaction-item">
                        <div class="txn-info">
                            <h6 class="txn-code">{{ $txn->transaction_code }}</h6>
                            <p class="txn-buyer">{{ $txn->buyer_name }}</p>
                            <small class="text-muted">{{ $txn->product->nama_produk ?? 'Produk' }} • {{ $txn->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <div class="txn-status">
                            @if ($txn->status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @elseif ($txn->status === 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @elseif ($txn->status === 'cancelled')
                                <span class="badge bg-secondary">Dibatalkan</span>
                            @else
                                <span class="badge bg-danger">Gagal</span>
                            @endif
                        </div>
                        <div class="txn-amount">
                            <strong>Rp{{ number_format($txn->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mt-2">Belum ada transaksi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Peningkatan Penjualan Desa -->
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="data-card">
                <div class="data-header">
                    <h5><i class="bi bi-graph-up me-2"></i>Top Desa</h5>
                    <small class="text-muted">Desa dengan penjualan tertinggi</small>
                </div>
                <div class="data-body">
                    @foreach($data['peningkatan_desa'] as $index => $desa)
                    @if ($index < 5)
                    <div class="desa-item-compact">
                        <div class="desa-rank">#{{ $index + 1 }}</div>
                        <div class="desa-info">
                            <h6 class="desa-name">{{ $desa['nama'] }}</h6>
                            <p class="desa-transactions">{{ $desa['transaksi'] }} transaksi</p>
                            <span class="omzet-amount">Rp{{ number_format($desa['omzet'], 1) }}M</span>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-2">
        <!-- Peningkatan Penjualan Desa -->
        <div class="col-lg-8" data-aos="fade-up">
            <div class="data-card">
                <div class="data-header">
                    <h5><i class="bi bi-graph-up me-2"></i>Peningkatan Penjualan Desa (30 Hari Terakhir)</h5>
                    <small class="text-muted">Transaksi terbanyak per desa</small>
                </div>
                <div class="data-body">
                    @foreach($data['peningkatan_desa'] as $index => $desa)
                    <div class="desa-item">
                        <div class="desa-rank">#{{ $index + 1 }}</div>
                        <div class="desa-info">
                            <h6 class="desa-name">{{ $desa['nama'] }}</h6>
                            <p class="desa-transactions">{{ $desa['transaksi'] }} transaksi</p>
                        </div>
                        <div class="desa-omzet">
                            <span class="omzet-amount">Rp {{ number_format($desa['omzet'], 1) }}M</span>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ ($desa['omzet'] / 50) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Verifikasi UMKM -->
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="data-card">
                <div class="data-header">
                    <h5><i class="bi bi-check-circle me-2"></i>Verifikasi UMKM</h5>
                    <small class="text-muted">{{ count($data['verifikasi_pending']) }} menunggu review</small>
                </div>
                <div class="data-body">
                    @foreach($data['verifikasi_pending'] as $item)
                    <div class="verification-item">
                        <div class="verification-info">
                            <h6 class="shop-name">{{ $item['nama_toko'] }}</h6>
                            <p class="owner-name">{{ $item['pemilik'] }}</p>
                            <div class="verification-meta">
                                <span class="badge bg-light text-dark">{{ $item['kategori'] }}</span>
                                <small class="text-muted">{{ $item['desa'] }} • {{ date('d M', strtotime($item['tanggal_daftar'])) }}</small>
                            </div>
                        </div>
                        <div class="verification-actions">
                            <button class="btn btn-sm btn-success" onclick="approveUMKM({{ $item['id'] }})">
                                <i class="bi bi-check"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectUMKM({{ $item['id'] }})">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

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

/* Breadcrumb */
.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
    font-size: 14px;
}

.breadcrumb-item {
    color: var(--text-muted);
}

.breadcrumb-item.active {
    color: var(--text-dark);
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    font-weight: 600;
    color: #cbd5e0;
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

.stats-growth.negative {
    background: rgba(245, 101, 101, 0.1);
    color: #742a2a;
}

.stats-growth.neutral {
    background: rgba(160, 174, 192, 0.1);
    color: #2d3748;
}

.stats-growth.warning {
    background: rgba(255, 193, 7, 0.1);
    color: #7d5d0f;
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

/* ========== Desa Performance ========== */
.desa-item {
    display: flex;
    align-items: center;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #f0f0f0;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(247, 250, 252, 0.5), rgba(237, 242, 247, 0.5));
}

.desa-item:last-child {
    margin-bottom: 0;
}

.desa-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-color: #e2e8f0;
}

.desa-rank {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    margin-right: 16px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.desa-info {
    flex: 1;
    margin-right: 16px;
}

.desa-name {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 14px;
}

.desa-transactions {
    font-size: 12px;
    color: var(--text-muted);
    margin: 0;
}

.desa-omzet {
    text-align: right;
    min-width: 140px;
}

.omzet-amount {
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 14px;
    display: block;
    margin-bottom: 6px;
}

.progress {
    height: 6px;
    background: #f0f0f0;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
    height: 100%;
    transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 0 8px rgba(102, 126, 234, 0.4);
}

/* ========== Transaction Items ========== */
.transaction-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #f0f0f0;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(247, 250, 252, 0.5), rgba(237, 242, 247, 0.5));
}

.transaction-item:last-child {
    margin-bottom: 0;
}

.transaction-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-color: #e2e8f0;
    transform: translateX(4px);
}

.txn-info {
    flex: 1;
    min-width: 0;
}

.txn-code {
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 13px;
    font-family: 'Courier New', monospace;
}

.txn-buyer {
    font-size: 12px;
    color: var(--text-muted);
    margin: 0 0 4px 0;
}

.transaction-item small {
    font-size: 11px;
    color: var(--text-muted);
}

.txn-status {
    margin: 0 16px;
    min-width: 80px;
}

.txn-amount {
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 14px;
    min-width: 120px;
    text-align: right;
}

/* Compact Desa Items */
.desa-item-compact {
    display: flex;
    align-items: flex-start;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 3px solid var(--primary-color);
    background: rgba(102, 126, 234, 0.02);
}

.desa-item-compact:last-child {
    margin-bottom: 0;
}

.desa-item-compact .desa-rank {
    width: 32px;
    height: 32px;
    font-size: 12px;
    margin-right: 12px;
}

.desa-item-compact .desa-info {
    flex: 1;
    margin-right: 0;
}

.desa-item-compact .desa-name {
    font-size: 13px;
    margin-bottom: 2px;
}

.desa-item-compact .desa-transactions {
    font-size: 11px;
    margin-bottom: 2px;
}

.desa-item-compact .omzet-amount {
    font-size: 12px;
    margin-bottom: 0;
}

/* ========== Verification ========== */
.verification-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, rgba(247, 250, 252, 0.5), rgba(237, 242, 247, 0.5));
}

.verification-item:last-child {
    margin-bottom: 0;
}

.verification-item:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
    border-color: #cbd5e0;
}

.verification-info {
    flex: 1;
    margin-right: 12px;
}

.shop-name {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
    font-size: 14px;
}

.owner-name {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.verification-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.verification-meta .badge {
    font-size: 10px;
    padding: 4px 10px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
    color: var(--primary-color);
    border: 1px solid rgba(102, 126, 234, 0.2);
}

.verification-meta small {
    font-size: 11px;
    color: var(--text-muted);
}

.verification-actions {
    display: flex;
    gap: 8px;
}

.verification-actions .btn {
    width: 36px;
    height: 36px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    transition: all 0.3s ease;
    border: none;
}

.verification-actions .btn-success {
    background: rgba(72, 187, 120, 0.1);
    color: #22543d;
}

.verification-actions .btn-success:hover {
    background: #48bb78;
    color: white;
    transform: translateY(-2px);
}

.verification-actions .btn-danger {
    background: rgba(245, 101, 101, 0.1);
    color: #742a2a;
}

.verification-actions .btn-danger:hover {
    background: #f56565;
    color: white;
    transform: translateY(-2px);
}

/* ========== Responsive ========== */
@media (max-width: 991.98px) {
    .page-header {
        margin-bottom: 24px;
    }

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

    .desa-item {
        padding: 12px;
        flex-wrap: wrap;
    }

    .desa-rank {
        width: 36px;
        height: 36px;
        font-size: 12px;
        margin-right: 12px;
    }

    .desa-omzet {
        min-width: 100%;
        margin-top: 8px;
        order: 3;
    }

    .verification-item {
        padding: 12px;
        flex-wrap: wrap;
    }

    .verification-info {
        width: 100%;
        margin-right: 0;
        margin-bottom: 12px;
    }

    .verification-actions {
        width: 100%;
        justify-content: flex-end;
    }
}
</style>
@endpush

@push('scripts')
<script>
function approveUMKM(id) {
    if (confirm('Approve UMKM ini?')) {
        // Implementasi approve UMKM
        alert('UMKM berhasil diapprove!');
        location.reload();
    }
}

function rejectUMKM(id) {
    if (confirm('Tolak pendaftaran UMKM ini?')) {
        // Implementasi reject UMKM
        alert('UMKM berhasil ditolak!');
        location.reload();
    }
}
</script>
@endpush
