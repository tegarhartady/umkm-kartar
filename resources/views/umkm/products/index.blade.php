@extends('layouts.app')

@section('title', 'Kelola Produk - UMKM')

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-box-seam me-2"></i>Kelola Produk
            </h1>
            <p class="text-muted small"><a href="{{ route('umkm.dashboard') }}">Dashboard</a> / Produk</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('umkm.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Produk Baru
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($products->count() > 0)
        <div class="row">
            @foreach ($products as $product)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    @if ($product->foto)
                        <img src="{{ asset('storage/' . $product->foto) }}" class="card-img-top" alt="{{ $product->nama_produk }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ $product->nama_produk }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($product->deskripsi, 60) }}</p>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h6 mb-0">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            @if ($product->status === 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>

                        <p class="small text-muted mb-2">
                            <i class="bi bi-box me-1"></i>Stok: {{ $product->stok }} {{ $product->satuan }}
                        </p>

                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('umkm.products.edit', $product) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                            <form method="POST" action="{{ route('umkm.products.destroy', $product) }}"
                                  class="d-inline flex-grow-1"
                                  onsubmit="return confirm('Hapus produk ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($products instanceof \Illuminate\Pagination\Paginator)
            <div class="row mt-4">
                <div class="col-12">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-info text-center py-5">
            <i class="bi bi-inbox" style="font-size: 2.5rem;"></i>
            <p class="mt-3 mb-0">Belum ada produk</p>
            <p class="text-muted small mb-3">Mulai tambahkan produk UMKM Anda sekarang</p>
            <a href="{{ route('umkm.products.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Tambah Produk Pertama
            </a>
        </div>
    @endif
</div>

@endsection
