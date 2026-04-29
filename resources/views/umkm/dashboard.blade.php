@extends('layouts.dashboard-umkm')

@section('title', 'Dashboard UMKM - ' . Auth::guard('umkm')->user()->nama_toko)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active"><i class="bi bi-house me-1"></i>Dashboard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Dashboard UMKM</h1>
                    <p class="text-muted mb-0">Kelola informasi dan produk UMKM Anda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Produk</p>
                            <h3 class="mb-0 text-primary fw-bold">{{ $totalProducts ?? 0 }}</h3>
                        </div>
                        <i class="bi bi-box-seam text-primary" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Transaksi</p>
                            <h3 class="mb-0 text-info fw-bold">{{ $totalTransaksi ?? 0 }}</h3>
                        </div>
                        <i class="bi bi-receipt text-info" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Pendapatan</p>
                            <h3 class="mb-0 text-success fw-bold">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <i class="bi bi-currency-dollar text-success" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Status UMKM</p>
                            <h5 class="mb-0">
                                @if($umkm->status === 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                                @elseif($umkm->status === 'pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($umkm->status === 'proses')
                                <span class="badge bg-warning text-dark">Dalam Pembuatan</span>
                                @elseif($umkm->status === 'paid')
                                <span class="badge bg-warning text-dark">Sudah Dibayar</span>
                                @elseif($umkm->status === 'ready')
                                <span class="badge bg-warning text-dark">Siap Dikirim/Diambil</span>
                                @elseif($umkm->status === 'shipping')
                                <span class="badge bg-warning text-dark">Sudah di Pick Up</span>
                                @elseif($umkm->status === 'selesai')
                                <span class="badge bg-warning text-dark">Selesai</span>
                                @else

                                <span class="badge bg-danger">Gagal</span>
                                @endif
                            </h5>
                        </div>
                        <i class="bi bi-info-circle text-warning" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info UMKM -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Profil UMKM</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Nama Toko</label>
                        <span class="fw-bold">{{ $umkm->nama_toko ?? '-' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Pemilik</label>
                        <span>{{ $umkm->pemilik ?? '-' }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Kategori</label>
                        <span class="badge bg-light text-dark">{{ $umkm->kategori ?? '-' }}</span>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small d-block">Alamat</label>
                        <small>{{ $umkm->alamat ?? '-' }}, {{ $umkm->desa ?? '' }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Transaksi Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Produk</th>
                                    <th>Pembeli</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $txn)
                                <tr>
                                    <td><small class="fw-bold">{{ $txn->transaction_code }}</small></td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $txn->product->nama_produk ?? 'Produk' }}</div>
                                        @if($txn->order_type == 'po')
                                        <span class="badge bg-warning text-dark x-small">PO: {{ \Carbon\Carbon::parse($txn->po_date)->format('d M Y') }}</span>
                                        @else
                                        <span class="badge bg-info text-white x-small">Langsung</span>
                                        @endif
                                    </td>
                                    <td>{{ $txn->buyer_name }}</td>
                                    <td>Rp {{ number_format($txn->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($txn->status === 'pending')
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-2">Belum Bayar</span>
                                        @elseif($txn->status === 'completed')
                                        <span class="badge bg-success bg-opacity-10 text-success px-2">Selesai</span>
                                        @elseif($txn->status === 'paid' || $txn->status === 'proses' || $txn->status === 'ready' || $txn->status === 'shipping')
                                        <span class="badge bg-success bg-opacity-10 text-success px-2">Berhasil</span>
                                        @elseif($txn->status === 'cancelled')
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2">Batal</span>
                                        @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2">Gagal</span>
                                        @endif
                                    </td>
                                    <td><small>{{ $txn->created_at->format('d/m/Y') }}</small></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-box me-2"></i>Daftar Produk</h5>
                        @if($umkm->status === 'disetujui')
                        <a href="{{ route('umkm.products.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td><span class="badge bg-light text-dark">{{ $loop->iteration }}</span></td>
                                    <td>
                                        <div class="fw-bold">{{ $product->nama_produk }}</div>
                                        <small class="text-muted">{{ Str::limit($product->deskripsi, 50) }}</small>
                                    </td>
                                    <td>{{ $product->kategori }}</td>
                                    <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $product->stok > 0 ? 'success' : 'danger' }}">
                                            {{ $product->stok }} {{ $product->satuan }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($product->status === 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                        @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('umkm.products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('umkm.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center py-3">
                        {{ $products->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-3">Belum ada produk</p>
                        @if($umkm->status === 'disetujui')
                        <a href="{{ route('umkm.products.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endsection
