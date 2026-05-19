@extends('layouts.app')

@section('title', $promotion->title . ' - Event Spesial')

@section('content')

@push('styles')
<style>
    .event-hero {
        position: relative;
        padding: 160px 0 100px 0;
        background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
        color: white;
        text-align: center;
        overflow: hidden;
    }

    .event-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><path fill="rgba(255,255,255,0.05)" d="M42.7,-64.1C55.6,-57.4,66.6,-45.5,73.4,-31.6C80.2,-17.7,82.8,-1.8,79.5,12.7C76.2,27.2,67.1,40.3,55.3,50.7C43.5,61.1,29,68.8,13.6,73C-1.8,77.2,-18.2,77.9,-32.8,72.6C-47.4,67.3,-60.2,56,-69.1,41.9C-78,27.8,-83,10.9,-81.4,-5.4C-79.8,-21.7,-71.6,-37.4,-60,-49.2C-48.4,-61,-33.4,-68.9,-18.6,-70.6C-3.8,-72.3,10.8,-67.8,24.9,-63L42.7,-64.1Z" transform="translate(100 100) scale(1.1)"/></svg>') no-repeat center center;
        background-size: cover;
        opacity: 0.8;
    }

    .event-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .event-subtitle {
        font-size: 1.5rem;
        font-weight: 300;
        color: rgba(255, 255, 255, 0.8);
        position: relative;
        z-index: 1;
        margin-bottom: 2rem;
    }

    .event-description {
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .umkm-section {
        padding: 80px 0;
        background-color: #f8f9fa;
    }

    .umkm-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 40px;
        border-top: 4px solid #f8fbffff;
    }

    .umkm-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .umkm-icon {
        width: 60px;
        height: 60px;
        background: #e8eef7;
        color: #001f5c;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-right: 20px;
    }

    .umkm-info h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: #ffffffff;
    }

    .umkm-info p {
        margin: 0;
        color: #718096;
        font-size: 0.95rem;
    }

    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        background: white;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

<!-- Event Hero Section -->
<section class="page-header event-hero">
    <div class="container">
        <h1 class="event-title text-light" data-aos="fade-up">{{ $promotion->title }}</h1>
        <h3 class="event-subtitle" data-aos="fade-up" data-aos-delay="100">{{ $promotion->subtitle }}</h3>
        @if($promotion->description)
        <div class="event-description" data-aos="fade-up" data-aos-delay="200">
            {{ $promotion->description }}
        </div>
        @endif
    </div>
</section>

<!-- UMKM & Products Section -->
<section class="umkm-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="fw-bold" style="color: #001f5c;">UMKM Partisipan</h2>
            <p class="text-muted">Temukan berbagai produk unggulan dari UMKM yang tergabung dalam event ini</p>
        </div>

        @forelse($promotion->umkms as $umkm)
        <div class="umkm-card" data-aos="fade-up">
            <div class="umkm-header">
                <div class="umkm-icon">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="umkm-info">
                    <h3>{{ $umkm->nama_toko }}</h3>
                    <p><i class="bi bi-person me-1"></i> {{ $umkm->pemilik }} &bull; <i class="bi bi-tag me-1"></i> {{ $umkm->kategori }}</p>
                </div>
                <div class="ms-auto text-end">
                    <a href="{{ url('/katalog?search=' . urlencode($umkm->nama_toko)) }}" class="btn btn-outline-primary btn-sm rounded-pill">
                        Lihat Semua Produk
                    </a>
                </div>
            </div>

            <div class="row g-4">
                @forelse($umkm->products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400' }}"
                            alt="{{ $product->nama_produk }}"
                            style="width: 100%; height: 150px; object-fit: cover;">
                        <div class="p-3">
                            <h6 class="fw-bold mb-1 text-truncate" title="{{ $product->nama_produk }}">{{ $product->nama_produk }}</h6>
                            <p class="text-primary fw-bold mb-2 small">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('beli', $product->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-cart-plus"></i> Beli
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted py-4">
                    <i class="bi bi-box-seam fs-2 mb-2 d-block text-light"></i>
                    Belum ada produk yang tersedia dari UMKM ini.
                </div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-shop-window text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">Belum ada UMKM yang tergabung dalam event ini.</h4>
        </div>
        @endforelse
    </div>
</section>

@endsection
