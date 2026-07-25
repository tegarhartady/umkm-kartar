@extends('layouts.dashboard')

@section('title', 'Laporan Transaksi per Desa')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.laporan.index') }}">Laporan</a></li>
            <li class="breadcrumb-item active">Transaksi per Desa</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Filter Transaksi Berdasarkan Desa</h5>
                        <a href="{{ route('admin.laporan.transaksi_desa.export', request()->all()) }}" class="btn btn-success shadow-sm text-white">
                            <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
                        </a>
                    </div>
                    
                    <form action="{{ route('admin.laporan.transaksi_desa') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-6 col-lg-4">
                            <label for="desa" class="form-label text-muted small fw-bold text-uppercase">Pilih Desa</label>
                            <select name="desa" id="desa" class="form-select border-0 bg-light" onchange="this.form.submit()">
                                <option value="">-- Semua Desa --</option>
                                @foreach($desaList as $desa)
                                    <option value="{{ $desa }}" {{ $selectedDesa == $desa ? 'selected' : '' }}>
                                        Desa {{ $desa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary px-4 rounded-3">
                                <i class="bi bi-filter me-2"></i> Terapkan Filter
                            </button>
                            @if($selectedDesa)
                                <a href="{{ route('admin.laporan.transaksi_desa') }}" class="btn btn-light px-4 rounded-3 ms-2">
                                    <i class="bi bi-x-circle me-1"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    @if($selectedDesa)
    <div class="row mb-4 g-3">
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="me-4 rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-shop fs-3"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Total Transaksi</p>
                        <h3 class="mb-0 fw-bold">{{ $transactions->total() }} Transaksi</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white h-100">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="me-4 rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                        <i class="bi bi-cash-stack fs-3"></i>
                    </div>
                    <div>
                        <p class="mb-1 text-white-50 fw-semibold text-uppercase small">Total Pendapatan</p>
                        <h3 class="mb-0 fw-bold">
                            @php
                                $totalPendapatan = \App\Models\Transaction::join('umkms', 'transactions.umkm_id', '=', 'umkms.id')
                                    ->where('umkms.desa', $selectedDesa)
                                    ->where('transactions.status', 'completed')
                                    ->sum('transactions.total_price');
                            @endphp
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold mb-0">
                        @if($selectedDesa)
                            Daftar Transaksi di Desa {{ $selectedDesa }}
                        @else
                            Daftar Semua Transaksi (Semua Desa)
                        @endif
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="rounded-start">Tgl Transaksi</th>
                                    <th>Kode Ref</th>
                                    <th>UMKM / Desa</th>
                                    <th>Pembeli</th>
                                    <th>Total Tagihan</th>
                                    <th class="rounded-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $txn)
                                <tr>
                                    <td>
                                        <span class="fw-medium text-dark">{{ $txn->created_at->format('d M Y') }}</span><br>
                                        <small class="text-muted">{{ $txn->created_at->format('H:i') }} WIB</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-monospace border">{{ $txn->transaction_code }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded px-2 py-1 me-2">
                                                <i class="bi bi-shop"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $txn->umkm->nama_toko ?? '-' }}</div>
                                                <small class="text-muted">{{ $txn->umkm->desa ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $txn->buyer_name ?? ($txn->user->name ?? '-') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">Rp {{ number_format($txn->total_price, 0, ',', '.') }}</div>
                                        <small class="text-muted">{{ $txn->quantity ?? 1 }} item</small>
                                    </td>
                                    <td>
                                        @if($txn->status == 'completed')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Selesai</span>
                                        @elseif($txn->status == 'pending')
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill">Pending</span>
                                        @elseif($txn->status == 'processing')
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill">Diproses</span>
                                        @elseif($txn->status == 'cancelled' || $txn->status == 'failed')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">Gagal/Batal</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">{{ ucfirst($txn->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-muted mb-3">
                                            <i class="bi bi-inbox fs-1"></i>
                                        </div>
                                        <h6 class="fw-bold">Belum Ada Transaksi</h6>
                                        <p class="mb-0 text-muted small">Tidak ditemukan data transaksi untuk desa ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($transactions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->links('pagination::bootstrap-5') }}
                    </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
