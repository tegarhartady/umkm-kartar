@extends('layouts.app')

@section('content')

<!-- Banner Iklan -->
<section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="text-white fw-bold mb-2">🎉 Floating Market UMKM Sunset Pier</h2>
                <p class="text-white fs-5 mb-4">Ayo datang ke floating market UMKM sunset pier dan nikmati berbagai produk lokal berkualitas!</p>
                <div class="d-flex gap-3">
                    <a href="/katalog" class="btn btn-light btn-lg">
                        <i class="bi bi-shop"></i> Jelajahi Katalog
                    </a>
                    <a href="/desa-mitra" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-map"></i> Lihat Lokasi
                    </a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <i class="bi bi-shop" style="font-size: 6rem; color: rgba(255,255,255,0.3);"></i>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container py-5">
    <!-- Stats -->
    <div class="row mb-5">
        <div class="col-md-3 text-center">
            <h3 class="text-primary fw-bold">{{ $totalUmkm ?? 0 }}</h3>
            <p class="text-muted">UMKM Terdaftar</p>
        </div>
        <div class="col-md-3 text-center">
            <h3 class="text-success fw-bold">{{ $totalDesa ?? 0 }}</h3>
            <p class="text-muted">Desa Mitra</p>
        </div>
        <div class="col-md-3 text-center">
            <h3 class="text-info fw-bold">{{ $totalProduk ?? 0 }}</h3>
            <p class="text-muted">Produk Tersedia</p>
        </div>
        <div class="col-md-3 text-center">
            <h3 class="text-warning fw-bold">6</h3>
            <p class="text-muted">Kategori Produk</p>
        </div>
    </div>

    <!-- Featured UMKM -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h2 class="fw-bold">UMKM Unggulan</h2>
            <p class="text-muted">Produk terbaik dari UMKM Teluknaga</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse($featuredUmkm ?? [] as $umkm)
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $umkm->nama_toko }}</h5>
                        <p class="text-muted small">{{ $umkm->pemilik }}</p>
                        <p class="card-text">{{ Str::limit($umkm->deskripsi, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-info">{{ $umkm->kategori }}</span>
                            <a href="/katalog?umkm={{ $umkm->id }}" class="btn btn-sm btn-primary">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted">Belum ada UMKM unggulan</p>
            </div>
        @endforelse
    </div>

    <!-- CTA Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body py-5 text-center">
                    <h3 class="fw-bold mb-3">Ingin Bergabung dengan Kami?</h3>
                    <p class="mb-4">Daftarkan UMKM Anda dan raih peluang bisnis lebih besar di Teluknaga</p>
                    <a href="/daftar-umkm" class="btn btn-light btn-lg">
                        <i class="bi bi-pencil"></i> Daftar Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection