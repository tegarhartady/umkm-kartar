@extends('layouts.dashboard')

@section('content')
<div class="transaction-container" data-aos="fade-up">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="page-title mb-1">Riwayat Transaksi</h1>
            <p class="text-muted small mb-0">Manajemen data transaksi UMKM Karang Taruna Teluknaga</p>
        </div>
        <a href="{{ route('admin.transactions.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Tambah Transaksi
        </a>
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
                            <th>KODE TRANSAKSI</th>
                            <th>PRODUK</th>
                            <th>TOTAL HARGA</th>
                            <th>STATUS BAYAR</th>
                            <th>STATUS PENGIRIMAN</th>
                            <th class="text-end pe-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $index => $transaction)
                        <tr>
                            <td class="ps-4 text-muted small">
                                {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        @php
                                        $initials = collect(explode(' ', $transaction->buyer_name))
                                        ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                                        ->take(2)
                                        ->join('');
                                        $colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];
                                        $color = $colors[ord($transaction->buyer_name) % count($colors)];
                                        @endphp
                                        <span style="background-color: {{ $color }};">{{ $initials }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $transaction->buyer_name }}</div>
                                        <div class="text-muted small">{{ $transaction->buyer_phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code class="text-primary fw-bold">{{ $transaction->transaction_code }}</code>
                                <div class="text-muted x-small mt-1">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td>
                                @if ($transaction->product)
                                <div class="text-dark fw-500">{{ $transaction->product->nama_produk }}</div>
                                <div class="text-muted small">{{ $transaction->quantity }} {{ $transaction->product->satuan ?? 'item' }}</div>
                                @if($transaction->order_type == 'po')
                                <span class="badge badge-soft-warning x-small mt-1">PO: {{ \Carbon\Carbon::parse($transaction->po_date)->format('d/m/Y') }}</span>
                                @endif
                                @else
                                <span class="text-danger small"><i class="bi bi-exclamation-triangle me-1"></i>Produk Dihapus</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</span>
                                <div class="text-muted x-small">via {{ ucfirst($transaction->payment_method ?? 'N/A') }}</div>
                            </td>
                            <td>
                                @if ($transaction->status === 'pending')
                                <span class="badge badge-soft-warning">Belum Bayar</span>
                                @elseif ($transaction->status === 'paid' || $transaction->status === 'completed' || $transaction->status === 'proses' || $transaction->status === 'ready' || $transaction->status === 'shipping')
                                <span class="badge badge-soft-success">Berhasil</span>
                                @elseif ($transaction->status === 'cancelled')
                                <span class="badge badge-soft-secondary">Batal</span>
                                @else
                                <span class="badge badge-soft-danger">Gagal</span>
                                @endif
                            </td>
                            <td>
                                @if($transaction->delivery_status == 'delivered')
                                    <span class="badge badge-soft-success">Selesai</span>
                                @elseif($transaction->delivery_status == 'shipping')
                                    <span class="badge badge-soft-primary">Pengiriman</span>
                                @elseif($transaction->delivery_status == 'ready')
                                    <span class="badge badge-soft-info">Packing</span>
                                @elseif($transaction->status == 'proses')
                                    <span class="badge badge-soft-info">Diproses</span>
                                @else
                                    <span class="badge badge-soft-secondary">Menunggu</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($transaction->payment_proof && $transaction->status == 'pending')
                                    <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank" class="btn btn-icon btn-outline-warning" title="Lihat Bukti">
                                        <i class="bi bi-image"></i>
                                    </a>
                                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="paid">
                                        <!-- Copy other fields to satisfy validation if necessary, or simplify the controller -->
                                        <input type="hidden" name="buyer_name" value="{{ $transaction->buyer_name }}">
                                        <input type="hidden" name="buyer_phone" value="{{ $transaction->buyer_phone }}">
                                        <input type="hidden" name="buyer_address" value="{{ $transaction->buyer_address }}">
                                        <input type="hidden" name="buyer_city" value="{{ $transaction->buyer_city }}">
                                        <button type="submit" class="btn btn-icon btn-outline-success" title="Approve Bayar">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($transaction->status == 'ready' || ($transaction->status == 'ready' && $transaction->delivery_status == 'shipping'))
                                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <input type="hidden" name="delivery_status" value="delivered">
                                        <input type="hidden" name="buyer_name" value="{{ $transaction->buyer_name }}">
                                        <input type="hidden" name="buyer_phone" value="{{ $transaction->buyer_phone }}">
                                        <input type="hidden" name="buyer_address" value="{{ $transaction->buyer_address }}">
                                        <input type="hidden" name="buyer_city" value="{{ $transaction->buyer_city }}">
                                        <button type="submit" class="btn btn-icon btn-success text-white" title="Selesaikan Pesanan">
                                            <i class="bi bi-flag-fill"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                        class="btn btn-icon btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                                        class="btn btn-icon btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.transactions.destroy', $transaction->id) }}"
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
