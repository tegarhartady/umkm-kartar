@extends('layouts.app')

@section('content')

<!-- Header -->
<section class="py-5 bg-light">
    <div class="container">
        <h1 class="h2 fw-bold mb-2">Katalog UMKM & Desa Mitra</h1>
        <p class="text-muted">Jelajahi produk dan lokasi UMKM di Teluknaga</p>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <!-- Filter Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Filter</h5>
                </div>
                <div class="card-body">
                    <form method="GET" id="filterForm">
                        <!-- Kategori -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Kategori</label>
                            <div class="list-group list-group-flush">
                                <a href="?kategori=" class="list-group-item list-group-item-action {{ !request('kategori') ? 'active' : '' }}">
                                    Semua Kategori
                                </a>
                                <a href="?kategori=Makanan Olahan" class="list-group-item list-group-item-action {{ request('kategori') == 'Makanan Olahan' ? 'active' : '' }}">
                                    Makanan Olahan
                                </a>
                                <a href="?kategori=Kerajinan" class="list-group-item list-group-item-action {{ request('kategori') == 'Kerajinan' ? 'active' : '' }}">
                                    Kerajinan
                                </a>
                                <a href="?kategori=Hasil Laut" class="list-group-item list-group-item-action {{ request('kategori') == 'Hasil Laut' ? 'active' : '' }}">
                                    Hasil Laut
                                </a>
                                <a href="?kategori=Hasil Perkebunan" class="list-group-item list-group-item-action {{ request('kategori') == 'Hasil Perkebunan' ? 'active' : '' }}">
                                    Hasil Perkebunan
                                </a>
                                <a href="?kategori=Fashion" class="list-group-item list-group-item-action {{ request('kategori') == 'Fashion' ? 'active' : '' }}">
                                    Fashion
                                </a>
                            </div>
                        </div>

                        <!-- Desa -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Desa</label>
                            <div class="list-group list-group-flush">
                                <a href="?desa=" class="list-group-item list-group-item-action {{ !request('desa') ? 'active' : '' }}">
                                    Semua Desa
                                </a>
                                @foreach($desas ?? [] as $d)
                                    <a href="?desa={{ $d->id }}" class="list-group-item list-group-item-action {{ request('desa') == $d->id ? 'active' : '' }}">
                                        {{ $d->nama_desa }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- UMKM Katalog -->
            <div class="mb-5">
                <h3 class="fw-bold mb-4">Daftar UMKM</h3>
                
                @if($umkms->count() > 0)
                    <div class="row g-4">
                        @foreach($umkms as $umkm)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $umkm->nama_toko }}</h5>
                                        <p class="text-muted small mb-2">{{ $umkm->pemilik }}</p>
                                        
                                        <div class="mb-3">
                                            <span class="badge bg-info">{{ $umkm->kategori }}</span>
                                            <span class="badge bg-light text-dark">{{ $umkm->desa }}</span>
                                        </div>

                                        <p class="card-text small">{{ Str::limit($umkm->deskripsi, 60) }}</p>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-box"></i> {{ $umkm->products()->count() }} produk
                                            </small>
                                            <a href="/katalog/{{ $umkm->id }}" class="btn btn-sm btn-primary">Lihat</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $umkms->links() }}
                    </div>
                @else
                    <div class="alert alert-info">Tidak ada UMKM yang sesuai dengan filter</div>
                @endif
            </div>

            <!-- Desa Mitra Section -->
            <div class="mt-5 pt-5 border-top">
                <h3 class="fw-bold mb-4">Desa Mitra</h3>

                @if($desas->count() > 0)
                    <div class="row g-4">
                        @foreach($desas as $desa)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $desa->nama_desa }}</h5>
                                        <p class="text-muted small mb-2">
                                            <i class="bi bi-geo-alt"></i> {{ $desa->kecamatan }}, {{ $desa->kabupaten }}
                                        </p>

                                        <p class="card-text small">{{ Str::limit($desa->deskripsi, 80) }}</p>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="bi bi-shop"></i> {{ $desa->jumlah_umkm }} UMKM
                                            </small>
                                            <a href="?desa={{ $desa->id }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">Tidak ada desa mitra</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection