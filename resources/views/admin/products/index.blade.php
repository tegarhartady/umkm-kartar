@extends('layouts.dashboard')

@section('title', 'Semua Produk')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item">Produk</li>
            <li class="breadcrumb-item active">Semua Produk</li>
        </ol>
    </nav>
@endsection

@section('styles')
<style>
.product-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border-radius: 15px;
    overflow: hidden;
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}
.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #f8f9fa;
}
.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.product-card:hover .product-image img {
    transform: scale(1.05);
}
.no-image {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #6c757d;
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
}
.no-image i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}
.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.product-card:hover .product-overlay {
    opacity: 1;
}
.product-actions {
    display: flex;
    gap: 0.5rem;
}
.product-title {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}
.product-description {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 1rem;
}
.product-meta {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 0.75rem;
}
.price {
    color: #28a745;
    font-size: 1.1rem;
}
.stock {
    margin-left: auto;
}
.umkm-info {
    border-top: 1px solid #e9ecef;
    padding-top: 0.75rem;
}
</style>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Semua Produk</h1>
            <p class="page-subtitle">
                Kelola semua produk UMKM yang terdaftar
            </p>
        </div>
        <div class="col-auto">
            <div class="page-actions">
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Produk
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Products Grid -->
<div class="row g-4">
    @forelse($products as $product)
    <div class="col-lg-4 col-md-6">
        <div class="card product-card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">{{ $product->nama_produk }}</h6>
                    <small class="text-muted">{{ $product->umkm->nama_toko }}</small>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('admin.products.show', $product) }}">
                            <i class="bi bi-eye me-2"></i>Lihat Detail</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.products.edit', $product) }}">
                            <i class="bi bi-pencil me-2"></i>Edit</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger" 
                                        onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                    <i class="bi bi-trash me-2"></i>Hapus
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <!-- Product Image -->
                <div class="mb-3">
                    @if($product->image && file_exists(public_path($product->image)))
                        <img src="{{ asset($product->image) }}" alt="{{ $product->nama_produk }}" class="img-fluid rounded" style="max-height: 200px; object-fit: cover; width: 100%;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                            <div class="text-center">
                                <i class="bi bi-image" style="font-size: 2rem; color: #ccc;"></i>
                                <p class="text-muted small mt-2 mb-0">Tidak ada gambar</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row">
                    <div class="col-6">
                        <small class="text-muted">Harga</small>
                        <div class="fw-bold text-primary">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        <small class="text-muted">per {{ $product->satuan }}</small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">Stok</small>
                        <div class="fw-bold">{{ $product->stok }} {{ $product->satuan }}</div>
                        @if($product->stok < 10)
                            <small class="text-danger">Stok menipis</small>
                        @else
                            <small class="text-success">Stok tersedia</small>
                        @endif
                    </div>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">Kategori</small>
                    <div><span class="badge bg-light text-dark">{{ $product->kategori }}</span></div>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">Deskripsi</small>
                    <p class="small mb-0">{{ Str::limit($product->deskripsi, 80) }}</p>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if($product->status == 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Non-aktif</span>
                        @endif
                    </div>
                    <small class="text-muted">{{ $product->umkm->desa }}</small>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-box-seam display-1 text-muted mb-3"></i>
                <h4>Belum ada produk terdaftar</h4>
                <p class="text-muted">Mulai tambahkan produk pertama</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Produk
                </a>
            </div>
        </div>
    </div>
    @endforelse
</div>

@if($products->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@endif

@endsection

@push('styles')
<style>
.product-card {
    transition: all 0.2s ease;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
</style>
@endpush
