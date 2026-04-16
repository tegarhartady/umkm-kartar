@extends('layouts.app')

@section('title', 'Dashboard UMKM - ' . Auth::guard('umkm')->user()->nama_toko)

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-shop me-2"></i>Dashboard UMKM
            </h1>
            <p class="text-muted small">Selamat datang, <strong>{{ Auth::guard('umkm')->user()->pemilik }}</strong></p>
        </div>
        <div class="col-md-4 text-end">
            <form method="POST" action="{{ route('umkm.logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Produk</p>
                            <h3 class="mb-0">{{ $totalProducts }}</h3>
                        </div>
                        <i class="bi bi-box-seam text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Produk Aktif</p>
                            <h3 class="mb-0 text-success">{{ $activeProducts }}</h3>
                        </div>
                        <i class="bi bi-check-circle text-success" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Pending Review</p>
                            <h3 class="mb-0 text-warning">{{ $totalProducts - $activeProducts }}</h3>
                        </div>
                        <i class="bi bi-clock text-warning" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Status Toko</p>
                            <h5 class="mb-0">
                                <span class="badge bg-success">Aktif</span>
                            </h5>
                        </div>
                        <i class="bi bi-shop text-success" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Kolom Kiri - Info Toko -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Informasi Toko
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small d-block">Nama Toko</label>
                        <p class="fw-semibold mb-0">{{ $umkm->nama_toko }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Pemilik</label>
                        <p class="fw-semibold mb-0">{{ $umkm->pemilik }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Email</label>
                        <p class="fw-semibold mb-0">{{ $umkm->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Telepon</label>
                        <p class="fw-semibold mb-0">{{ $umkm->phone }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Kategori</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-primary">{{ $umkm->kategori }}</span>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Desa</label>
                        <p class="fw-semibold mb-0">{{ $umkm->desa }}</p>
                    </div>
                    <div>
                        <label class="text-muted small d-block">Alamat</label>
                        <p class="fw-semibold mb-0 small">{{ $umkm->alamat }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-lightning me-2"></i>Menu Cepat
                    </h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('umkm.products.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Produk Baru
                    </a>
                    <a href="{{ route('umkm.products.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-list me-1"></i>Lihat Semua Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan - Daftar Produk -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>Produk Saya
                    </h6>
                    <a href="{{ route('umkm.products.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus me-1"></i>Tambah
                    </a>
                </div>
                <div class="card-body">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <strong>{{ $product->nama_produk }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($product->deskripsi, 40) }}</small>
                                        </td>
                                        <td><small>{{ $product->kategori ?? 'N/A' }}</small></td>
                                        <td><strong>Rp {{ number_format($product->harga, 0, ',', '.') }}</strong></td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $product->stok }} {{ $product->satuan ?? 'pcs' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($product->status === 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('umkm.products.edit', $product) }}"
                                                   class="btn btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('umkm.products.destroy', $product) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Hapus produk ini? Tindakan ini tidak bisa dibatalkan.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($products instanceof \Illuminate\Pagination\Paginator)
                            <div class="mt-3">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 2.5rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Belum ada produk</p>
                            <a href="{{ route('umkm.products.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Tambah Produk Pertama Anda
                            </a>
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
