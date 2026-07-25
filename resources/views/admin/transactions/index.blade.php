@extends('layouts.dashboard')

@section('content')
<div class="transaction-container" data-aos="fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Riwayat Transaksi</h1>
            <p class="text-muted small mb-0">Manajemen data transaksi UMKM Karang Taruna Teluknaga</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.transactions.export', request()->all()) }}" class="btn btn-success shadow-sm text-white">
                <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
            </a>
            <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Tambah Transaksi
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari kode transaksi, checkout, atau pembeli..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Diproses</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap Diambil/Dikirim</option>
                        <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Dikirim</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal/Batal</option>
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Filter</button>
                    <a href="{{ route('admin.transactions.index') }}" class="btn btn-light"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
                </div>
            </form>
        </div>
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
            <div>{{ $message }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">NO</th>
                            <th>PEMBELI</th>
                            <th>PENJUAL</th>
                            <th>KODE</th>
                            <th>PRODUK</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                            <th class="text-end pe-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $group)
                        @php 
                            $first = $group->first();
                            $totalGroupPrice = $group->sum('total_price');
                            $checkoutCode = $first->checkout_code ?: $first->transaction_code;
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $first->buyer_name }}</div>
                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $first->buyer_phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php $umkms = $group->pluck('umkm.nama_toko')->unique()->filter(); @endphp
                                @foreach($umkms as $u)
                                    <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 mb-1 d-block text-start" style="font-size: 0.65rem;">
                                        {{ $u }}
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <div class="fw-bold text-dark" style="font-size: 0.75rem;">{{ $checkoutCode }}</div>
                                @if($group->count() > 1)
                                    <div class="badge bg-info bg-opacity-10 text-info x-small mt-1">{{ $group->count() }} Transaksi</div>
                                @endif
                            </td>
                            <td>
                                @foreach($group as $trans)
                                    @foreach($trans->items as $item)
                                        <div class="text-dark mb-1" style="font-size: 0.8rem;">
                                            • {{ $item->product_name }} <span class="text-muted">({{ $item->quantity }}x)</span>
                                        </div>
                                    @endforeach
                                    @if($trans->items->count() == 0 && $trans->product)
                                        <div class="text-dark mb-1" style="font-size: 0.8rem;">
                                            • {{ $trans->product->nama_produk }} <span class="text-muted">({{ $trans->quantity }}x)</span>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div class="fw-bold text-dark">Rp{{ number_format($totalGroupPrice, 0, ',', '.') }}</div>
                                <div class="text-muted x-small">{{ ucfirst($first->payment_method ?? 'N/A') }}</div>
                            </td>
                            <td>
                                @if ($first->status === 'pending')
                                <span class="badge badge-soft-warning">Pending</span>
                                @elseif ($first->status === 'paid' || $first->status === 'completed' || $first->status === 'proses' || $first->status === 'ready' || $first->status === 'shipping')
                                <span class="badge badge-soft-success">Paid</span>
                                @else
                                <span class="badge badge-soft-danger">{{ strtoupper($first->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($first->payment_proof && $first->status == 'pending')
                                    <a href="{{ asset('storage/' . $first->payment_proof) }}" target="_blank" class="btn btn-icon btn-outline-warning" title="Lihat Bukti">
                                        <i class="bi bi-image"></i>
                                    </a>
                                    
                                    @if($first->checkout_code)
                                    <form action="{{ route('admin.transactions.update', $first->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="bulk_approve" value="1">
                                        <button type="submit" class="btn btn-sm btn-success text-white px-2 py-1" style="font-size: 0.7rem;" title="Setujui Semua Grup">
                                            SETUJUI GRUP
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.transactions.update', $first->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="btn btn-icon btn-outline-success" title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                    @endif
                                    
                                    <a href="{{ route('admin.transactions.show', $first->id) }}"
                                        class="btn btn-icon btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.transactions.edit', $first->id) }}"
                                        class="btn btn-icon btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.transactions.destroy', $first->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-outline-danger"
                                            onclick="return confirm('Hapus data transaksi ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-receipt-cutoff text-muted display-4"></i>
                                    <p class="mt-3 text-muted">Belum ada riwayat transaksi yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($transactions->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
                </div>
                <div>
                    {{ $transactions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .transaction-container {
        padding: 1.5rem;
    }

    .page-title {
        font-weight: 800;
        color: #2d3748;
        letter-spacing: -0.5px;
    }

    /* Table Styling */
    .table thead th {
        background-color: #f8f9fa;
        color: #718096;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding-top: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #edf2f7;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background-color: rgba(247, 250, 252, 0.8);
    }

    .table td {
        padding-top: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #edf2f7;
    }

    /* Avatar Circle */
    .avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 13px;
        flex-shrink: 0;
    }

    /* Badges */
    .badge {
        padding: 6px 12px;
        font-weight: 600;
        font-size: 11px;
        border-radius: 6px;
    }

    .badge-soft-success {
        background-color: #e6fffa;
        color: #38a169;
    }

    .badge-soft-warning {
        background-color: #fffaf0;
        color: #dd6b20;
    }

    .badge-soft-danger {
        background-color: #fff5f5;
        color: #e53e3e;
    }

    .badge-soft-secondary {
        background-color: #f7fafc;
        color: #718096;
    }

    .badge-soft-primary {
        background-color: #ebf8ff;
        color: #3182ce;
    }

    .badge-soft-info {
        background-color: #e6fffa;
        color: #319795;
    }

    /* Action Buttons */
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .btn-outline-primary {
        border-color: #4299e1;
        color: #4299e1;
    }

    .btn-outline-primary:hover {
        background-color: #4299e1;
        color: white;
    }

    .btn-outline-danger {
        border-color: #feb2b2;
        color: #e53e3e;
    }

    .btn-outline-danger:hover {
        background-color: #e53e3e;
        color: white;
        border-color: #e53e3e;
    }

    .btn-outline-info {
        border-color: #bee3f8;
        color: #3182ce;
    }

    .btn-outline-info:hover {
        background-color: #3182ce;
        color: white;
        border-color: #3182ce;
    }

    /* Typography */
    .fw-500 {
        font-weight: 500;
    }

    .x-small {
        font-size: 10px;
    }

    /* Pagination Override */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px !important;
        margin: 0 2px;
        color: #4a5568;
        border: 1px solid #edf2f7;
    }

    .page-item.active .page-link {
        background-color: #4299e1;
        border-color: #4299e1;
    }

    @media (max-width: 768px) {
        .transaction-container {
            padding: 1rem;
        }

        .table-responsive {
            border-radius: 0;
        }
    }
</style>
@endpush
@endsection
