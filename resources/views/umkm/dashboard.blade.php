@extends('layouts.app')

@section('title', 'Dashboard UMKM - ' . Auth::guard('umkm')->user()->nama_toko)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Dashboard UMKM</h1>
                    <p class="text-muted mb-0">Kelola informasi dan produk UMKM Anda</p>
                </div>
                <form action="{{ route('umkm.logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Produk</p>
                            <h3 class="mb-0 text-primary fw-bold">{{ $totalProducts }}</h3>
                        </div>
                        <i class="bi bi-box-seam text-primary" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Produk Aktif</p>
                            <h3 class="mb-0 text-success fw-bold">{{ $activeProducts }}</h3>
                        </div>
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Status</p>
                            <h5 class="mb-0">
                                @if($umkm->status === 'disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($umkm->status === 'pending')
                                    <span class="badge bg-warning">Menunggu Approval</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </h5>
                        </div>
                        <i class="bi bi-info-circle text-info" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Kategori</p>
                            <h5 class="mb-0">{{ $umkm->kategori }}</h5>
                        </div>
                        <i class="bi bi-tag text-warning" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info UMKM -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informasi UMKM</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="150">Nama Toko:</td>
                                    <td>{{ $umkm->nama_toko }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Pemilik:</td>
                                    <td>{{ $umkm->pemilik }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td>{{ $umkm->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">No. Telepon:</td>
                                    <td>{{ $umkm->phone }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold" width="150">Desa:</td>
                                    <td>{{ $umkm->desa }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Alamat:</td>
                                    <td>{{ $umkm->alamat }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Lama Usaha:</td>
                                    <td>{{ $umkm->lama_usaha ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Produk Utama:</td>
                                    <td>{{ $umkm->produk_utama }}</td>
                                </tr>
                            </table>
                        </div>
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
                        <h5 class="mb-0">Daftar Produk</h5>
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
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
