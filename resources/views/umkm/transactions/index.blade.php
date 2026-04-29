@extends('layouts.dashboard-umkm')

@section('title', 'Daftar Transaksi - ' . Auth::guard('umkm')->user()->nama_toko)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('umkm.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Transaksi</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Daftar Transaksi</h1>
                    <p class="text-muted mb-0">Kelola pesanan dari pelanggan Anda</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Pesanan Masuk</h5>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Pembeli</th>
                                        <th>Harga Total</th>
                                        <th>Status Bayar</th>
                                        <th>Status Pengiriman</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $t)
                                        <tr>
                                            <td><span class="badge bg-light text-dark fw-bold border">{{ $t->transaction_code }}</span></td>
                                            <td>{{ $t->created_at->format('d M Y, H:i') }}</td>
                                            <td>
                                                <div class="fw-bold">{{ $t->product->nama_produk ?? 'Produk Dihapus' }}</div>
                                                <small class="text-muted d-block">{{ $t->quantity }} {{ $t->product->satuan ?? 'pcs' }}</small>
                                                @if($t->order_type == 'po')
                                                    <span class="badge bg-warning text-dark">Pre-Order (Target: {{ \Carbon\Carbon::parse($t->po_date)->format('d/m/Y') }})</span>
                                                @else
                                                    <span class="badge bg-info text-white">Langsung</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $t->buyer_name ?? ($t->user->name ?? '-') }}</div>
                                                <small class="text-muted">{{ $t->buyer_phone ?? '-' }}</small>
                                            </td>
                                            <td class="fw-bold text-success">Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                @if($t->status == 'completed')
                                                    <span class="badge bg-success bg-opacity-10 text-success border-success border-opacity-25 px-2 py-1">Selesai</span>
                                                @elseif($t->status == 'paid' || $t->status == 'proses' || $t->status == 'ready' || $t->status == 'shipping')
                                                    <span class="badge bg-success bg-opacity-10 text-success border-success border-opacity-25 px-2 py-1">Berhasil</span>
                                                @elseif($t->status == 'pending')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border-warning border-opacity-25 px-2 py-1">Belum Bayar</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 px-2 py-1">{{ ucfirst($t->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($t->delivery_status == 'delivered')
                                                    <span class="badge bg-success bg-opacity-10 text-success border-success border-opacity-25 px-2 py-1">Selesai</span>
                                                @elseif($t->delivery_status == 'shipping')
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 px-2 py-1">Pengiriman</span>
                                                @elseif($t->delivery_status == 'ready')
                                                    <span class="badge bg-info bg-opacity-10 text-info border-info border-opacity-25 px-2 py-1">Packing</span>
                                                @elseif($t->status == 'proses')
                                                    <span class="badge bg-info bg-opacity-10 text-info border-info border-opacity-25 px-2 py-1">Diproses</span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border-secondary border-opacity-25 px-2 py-1">Menunggu</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @if($t->status == 'paid')
                                                        <form action="{{ route('umkm.transactions.update_status', $t->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="proses">
                                                            <button type="submit" class="btn btn-sm btn-primary">Terima & Buat</button>
                                                        </form>
                                                    @elseif($t->status == 'proses')
                                                        <form action="{{ route('umkm.transactions.update_status', $t->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="ready">
                                                            <input type="hidden" name="delivery_status" value="ready">
                                                            <button type="submit" class="btn btn-sm btn-success">Selesai Buat</button>
                                                        </form>
                                                    @elseif($t->status == 'ready' && $t->delivery_status == 'ready' && $t->delivery_type == 'delivery')
                                                        <form action="{{ route('umkm.transactions.update_status', $t->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="ready">
                                                            <input type="hidden" name="delivery_status" value="shipping">
                                                            <button type="submit" class="btn btn-sm btn-warning">Pick Up</button>
                                                        </form>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $t->id }}">Detail</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($transactions->hasPages())
                            <div class="d-flex justify-content-center py-3">
                                {{ $transactions->links() }}
                            </div>
                        @endif

                        <!-- Modals -->
                        @foreach($transactions as $t)
                            <div class="modal fade" id="detailModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header bg-light border-0">
                                            <h5 class="modal-title fw-bold">Detail Pesanan #{{ $t->transaction_code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Nama:</small>
                                                        <span class="fw-bold">{{ $t->buyer_name }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Telepon:</small>
                                                        <span class="fw-bold">{{ $t->buyer_phone }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Alamat:</small>
                                                        <span>{{ $t->buyer_address }}, {{ $t->buyer_city }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Metode Pengiriman:</small>
                                                        <span class="badge bg-info bg-opacity-10 text-info">{{ $t->delivery_type == 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold mb-3">Bukti Pembayaran</h6>
                                                    @if($t->payment_proof)
                                                        <a href="{{ asset('storage/' . $t->payment_proof) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $t->payment_proof) }}" class="img-fluid rounded border shadow-sm" style="max-height: 200px;">
                                                        </a>
                                                        <div class="mt-2 text-center">
                                                            <small class="text-muted">Klik gambar untuk memperbesar</small>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-light border text-center py-4">
                                                            <i class="bi bi-image text-muted d-block fs-2 mb-2"></i>
                                                            <span class="text-muted small">Belum ada bukti pembayaran</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-12">
                                                    <hr class="my-2 opacity-50">
                                                    <h6 class="fw-bold mb-3">Rincian Produk</h6>
                                                    <div class="d-flex align-items-center mb-3">
                                                        <div class="flex-shrink-0 me-3">
                                                            @php $foto = $t->product->foto_produk ? json_decode($t->product->foto_produk)[0] : 'default.jpg'; @endphp
                                                            <img src="{{ asset('storage/' . $foto) }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-bold">{{ $t->product->nama_produk }}</div>
                                                            <small class="text-muted">{{ $t->quantity }} {{ $t->product->satuan }} x Rp{{ number_format($t->price, 0, ',', '.') }}</small>
                                                        </div>
                                                        <div class="text-end fw-bold text-success">
                                                            Rp{{ number_format($t->total_price, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                    @if($t->notes)
                                                        <div class="bg-light p-3 rounded-3 mt-2">
                                                            <small class="text-muted d-block fw-bold mb-1">Catatan:</small>
                                                            <span class="small">"{{ $t->notes }}"</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-receipt" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
