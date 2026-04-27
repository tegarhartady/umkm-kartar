@extends('layouts.dashboard')

@section('content')
<div class="laporan-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Detail Transaksi</h1>
        <a href="{{ route('admin.transactions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-receipt"></i> Informasi Transaksi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Kode Transaksi:</strong>
                            <p class="text-muted">{{ $transaction->transaction_code }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p>
                                @if ($transaction->status === 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif ($transaction->status === 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif ($transaction->status === 'cancelled')
                                    <span class="badge bg-secondary">Dibatalkan</span>
                                @else
                                    <span class="badge bg-danger">Gagal</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Transaksi:</strong>
                            <p class="text-muted">{{ $transaction->created_at->format('d M Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Metode Pembayaran:</strong>
                            <p class="text-muted">
                                @if ($transaction->payment_method)
                                    @if ($transaction->payment_method === 'transfer')
                                        Transfer Bank
                                    @elseif ($transaction->payment_method === 'ewallet')
                                        E-Wallet
                                    @else
                                        COD (Bayar di Tempat)
                                    @endif
                                @else
                                    Belum dipilih
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($transaction->paid_at)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Tanggal Pembayaran:</strong>
                                <p class="text-muted">{{ $transaction->paid_at->format('d M Y H:i:s') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($transaction->completed_at)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Tanggal Selesai:</strong>
                                <p class="text-muted">{{ $transaction->completed_at->format('d M Y H:i:s') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($transaction->notes)
                        <div class="mb-3">
                            <strong>Catatan:</strong>
                            <p class="text-muted">{{ $transaction->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-box-seam"></i> Informasi Produk
                    </h5>
                </div>
                <div class="card-body">
                    @if ($transaction->product)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Nama Produk:</strong>
                                <p class="text-muted">{{ $transaction->product->nama_produk }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Harga Satuan:</strong>
                                <p class="text-muted">Rp{{ number_format($transaction->price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Jumlah:</strong>
                                <p class="text-muted">{{ $transaction->quantity }} unit</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Total Harga:</strong>
                                <p class="text-muted" style="font-size: 1.1rem; font-weight: 600; color: #28a745;">
                                    Rp{{ number_format($transaction->total_price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Produk telah dihapus
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-check"></i> Informasi Pembeli
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nama:</strong>
                        <p class="text-muted">{{ $transaction->buyer_name }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Nomor Telepon:</strong>
                        <p class="text-muted">
                            <a href="tel:{{ $transaction->buyer_phone }}" class="text-decoration-none">
                                {{ $transaction->buyer_phone }}
                            </a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong>Alamat:</strong>
                        <p class="text-muted">{{ $transaction->buyer_address }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Kota:</strong>
                        <p class="text-muted">{{ $transaction->buyer_city }}</p>
                    </div>

                    @if ($transaction->buyer_postal_code)
                        <div class="mb-3">
                            <strong>Kode Pos:</strong>
                            <p class="text-muted">{{ $transaction->buyer_postal_code }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-tools"></i> Aksi
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.transactions.edit', $transaction->id) }}" class="btn btn-warning w-100 mb-2">
                        <i class="bi bi-pencil"></i> Edit Transaksi
                    </a>
                    <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                            <i class="bi bi-trash"></i> Hapus Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .laporan-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-radius: 8px 8px 0 0 !important;
    }

    strong {
        color: #495057;
        font-weight: 600;
    }

    .text-muted {
        color: #6c757d !important;
    }

    @media (max-width: 768px) {
        .laporan-container {
            padding: 12px;
        }

        .page-title {
            font-size: 1.25rem;
        }

        .col-md-8,
        .col-md-4 {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection
