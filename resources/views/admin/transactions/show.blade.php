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
                                @elseif ($transaction->status === 'paid')
                                <span class="badge bg-success">Dibayar</span>
                                @elseif ($transaction->status === 'proses')
                                <span class="badge bg-info">Dalam Pembuatan</span>
                                @elseif ($transaction->status === 'ready')
                                <span class="badge bg-info">Siap Dikirim/Diambil</span>
                                @elseif ($transaction->status === 'shipping')
                                <span class="badge bg-primary">Sudah di Pick Up</span>
                                @elseif ($transaction->status === 'delivered')
                                <span class="badge bg-success">Sampai Tujuan</span>
                                @elseif ($transaction->status === 'selesai')
                                <span class="badge bg-success">Selesai</span>
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
                                    @php
                                        $methods = [
                                            'midtrans' => 'Otomatis (Midtrans)',
                                            'transfer' => 'Transfer Bank Manual',
                                            'qris' => 'QRIS (Manual)',
                                            'cod' => 'COD (Bayar di Tempat)',
                                            'ewallet' => 'E-Wallet'
                                        ];
                                    @endphp
                                    <span class="badge bg-light text-dark border">
                                        {{ $methods[$transaction->payment_method] ?? strtoupper($transaction->payment_method) }}
                                    </span>
                                @else
                                    <span class="text-muted italic">Belum dipilih</span>
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
                        <i class="bi bi-box-seam"></i> Daftar Produk
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end pe-4">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $grandTotalItems = 0;
                                    $grandTotalShipping = 0;
                                @endphp
                                @foreach($groupTransactions as $gt)
                                    @php $grandTotalShipping += ($gt->delivery_fee ?? 0); @endphp
                                    @forelse($gt->items as $item)
                                        @php $grandTotalItems += $item->subtotal; @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <span class="d-block">{{ $item->product_name }}</span>
                                                        <small class="text-primary" style="font-size: 0.7rem;">Penjual: {{ $gt->umkm->nama_toko ?? 'UMKM' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-end pe-4 fw-bold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        @if($gt->product)
                                            @php $grandTotalItems += ($gt->price * $gt->quantity); @endphp
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        @if($gt->product->image)
                                                            <img src="{{ asset('storage/' . $gt->product->image) }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @endif
                                                        <div>
                                                            <span class="d-block">{{ $gt->product->nama_produk }}</span>
                                                            <small class="text-primary" style="font-size: 0.7rem;">Penjual: {{ $gt->umkm->nama_toko ?? 'UMKM' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $gt->quantity }}</td>
                                                <td class="text-end">Rp{{ number_format($gt->price, 0, ',', '.') }}</td>
                                                <td class="text-end pe-4 fw-bold">Rp{{ number_format($gt->price * $gt->quantity, 0, ',', '.') }}</td>
                                            </tr>
                                        @endif
                                    @endforelse
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal Produk:</td>
                                    <td class="text-end pe-4 fw-bold">Rp{{ number_format($grandTotalItems, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total Biaya Pengiriman:</td>
                                    <td class="text-end pe-4 fw-bold">Rp{{ number_format($grandTotalShipping, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="3" class="text-end fw-bold">Total Tagihan (Satu Checkout):</td>
                                    <td class="text-end pe-4 fw-bold" style="font-size: 1.1rem;">Rp{{ number_format(($grandTotalItems + $grandTotalShipping), 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-check"></i> Bukti Pembayaran
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if($transaction->payment_proof)
                        <div class="mb-3">
                            <p class="text-muted small">Klik gambar untuk memperbesar</p>
                            <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank">
                                <img src="{{ asset('storage/' . $transaction->payment_proof) }}" class="img-fluid rounded border shadow-sm" style="max-height: 400px;">
                            </a>
                        </div>
                    @else
                        <div class="py-4 text-muted">
                            <i class="bi bi-image fs-1 d-block mb-2"></i>
                            <p>Belum ada bukti pembayaran yang diunggah.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Status & Alur Proses
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ in_array($transaction->status, ['pending', 'paid', 'proses', 'ready', 'shipping', 'delivered', 'completed', 'selesai']) ? 'active' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pesanan Dibuat</h6>
                                <small class="text-muted">{{ $transaction->created_at->format('d M Y H:i') }}</small>
                            </div>
                        </div>

                        @if($transaction->payment_method != 'cod')
                        <div class="timeline-item {{ in_array($transaction->status, ['paid', 'proses', 'ready', 'shipping', 'delivered', 'completed', 'selesai']) ? 'active' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pembayaran Diterima</h6>
                                <small class="text-muted">{{ $transaction->paid_at ? $transaction->paid_at->format('d M Y H:i') : 'Menunggu' }}</small>
                            </div>
                        </div>
                        @endif

                        <div class="timeline-item {{ in_array($transaction->status, ['proses', 'ready', 'shipping', 'delivered', 'completed', 'selesai']) ? 'active' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Diproses UMKM</h6>
                                <small class="text-muted">{{ in_array($transaction->status, ['proses', 'ready', 'shipping', 'delivered', 'completed', 'selesai']) ? 'Berlangsung' : '-' }}</small>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($transaction->status, ['ready', 'shipping', 'delivered', 'completed', 'selesai']) ? 'active' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Siap Dikirim</h6>
                                <small class="text-muted">{{ in_array($transaction->status, ['ready', 'shipping', 'delivered', 'completed', 'selesai']) ? 'Tersedia' : '-' }}</small>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($transaction->status, ['shipping', 'delivered', 'completed', 'selesai']) ? 'active' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Dalam Pengiriman</h6>
                                <small class="text-muted">{{ in_array($transaction->status, ['shipping', 'delivered', 'completed', 'selesai']) ? 'Oleh Kurir' : '-' }}</small>
                            </div>
                        </div>

                        <div class="timeline-item {{ in_array($transaction->status, ['delivered', 'completed', 'selesai']) ? 'active' : '' }}">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Pesanan Selesai</h6>
                                <small class="text-muted">{{ $transaction->completed_at ? $transaction->completed_at->format('d M Y H:i') : '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-check"></i> Informasi Pembeli
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nama:</strong>
                        <p class="text-muted mb-1">{{ $transaction->buyer_name }}</p>
                        @if($transaction->user)
                            <small class="badge bg-light text-dark border">User ID: #{{ $transaction->user->id }}</small>
                        @endif
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
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        border: none;
        padding: 1rem 1.25rem;
    }

    strong {
        color: #495057;
        font-weight: 600;
        display: block;
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .text-muted {
        color: #6c757d !important;
    }

    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -26px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #dee2e6;
        border: 2px solid #fff;
        z-index: 1;
    }

    .timeline-item.active .timeline-dot {
        background: #28a745;
        box-shadow: 0 0 0 4px rgba(40, 167, 69, 0.2);
    }

    .timeline-item.active h6 {
        color: #28a745;
        font-weight: 700;
    }

    .timeline-content h6 {
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
