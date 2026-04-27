@extends('layouts.app')

@section('title', $product->nama_produk . ' - Karang Taruna Teluknaga')

@section('content')

<!-- Product Detail -->
<section class="py-5">
    <div class="container">
        <div class="row g-5 mt-4">
            <!-- Product Image -->
            <div class="col-lg-5">
                <div style="height: 400px; border-radius: 20px; overflow: hidden; background: linear-gradient(45deg, #f8f9fa, #e9ecef);">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->nama_produk }}" class="w-100 h-100" style="object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center">
                                <i class="bi bi-image" style="font-size: 4rem; color: #6c757d; opacity: 0.5;"></i>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-7">
                <!-- Breadcrumb -->
                {{-- <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-primary text-decoration-none">Katalog</a></li>
                        <li class="breadcrumb-item active">{{ $product->nama_produk }}</li>
                    </ol>
                </nav> --}}

                <!-- Category Badge -->
                <span class="badge bg-success mb-3">{{ $product->kategori }}</span>

                <!-- Title -->
                <h1 class="fw-bold mb-3">{{ $product->nama_produk }}</h1>

                <!-- UMKM Info -->
                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                    <i class="bi bi-shop text-success me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="mb-0">{{ $product->umkm->nama_toko ?? 'UMKM' }}</h6>
                        <small class="text-muted">{{ $product->umkm->desa ?? '' }}</small>
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <span class="h2 fw-bold text-success">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                    <span class="text-muted ms-3">/ {{ $product->satuan ?? 'pcs' }}</span>
                </div>

                <!-- Stock Info -->
                <div class="mb-4">
                    <p class="mb-2">
                        <i class="bi bi-box-seam me-2 text-success"></i>
                        <strong>Stok: {{ $product->stok }} {{ $product->satuan ?? 'pcs' }}</strong>
                    </p>
                    @if($product->stok < 5)
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <div>Stok terbatas! Segera pesan sebelum kehabisan.</div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <h6 class="fw-bold mb-3">Deskripsi Produk</h6>
                    <p class="text-muted lh-lg">{{ $product->deskripsi }}</p>
                </div>

                <!-- CTA Buttons -->
                <div class="d-flex gap-3">
                    <a href="{{ route('checkout.show', $product->id) }}" class="btn btn-success btn-lg grow">
                        <i class="bi bi-cart me-2"></i>Beli Sekarang
                    </a>
                    {{-- <a href="{{ route('katalog') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a> --}}
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="row mt-5 pt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">Produk Terkait</h3>
                </div>
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col">
                            <a href="/beli/{{ $related->id }}" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm hover-shadow">
                                    <div style="height: 200px; overflow: hidden; background: linear-gradient(45deg, #f8f9fa, #e9ecef);">
                                        @if($related->image)
                                            <img src="{{ asset($related->image) }}" alt="{{ $related->nama_produk }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center h-100">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold text-dark">{{ Str::limit($related->nama_produk, 30) }}</h6>
                                        <p class="card-text text-muted small mb-2">{{ $related->umkm->nama_toko ?? 'UMKM' }}</p>
                                        <p class="card-text fw-bold text-success">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
@endpush
