@extends('layouts.app')

@section('title', 'Desa Mitra - Karang Taruna Teluknaga')

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="section-badge" data-aos="fade-up">Desa Mitra</span>
                <h1 class="page-title" data-aos="fade-up" data-aos-delay="100">
                    Kemitraan <span class="text-primary">Desa Pesisir</span>
                </h1>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Bersama membangun ekonomi desa melalui kolaborasi dan pemberdayaan UMKM
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Desa Mitra List -->
<section class="section-desa-mitra">
    <div class="container">
        <div class="row g-4">
            @php
            $desaList = [
                ['nama' => 'Desa Teluknaga', 'umkm' => 15, 'produk' => 30, 'deskripsi' => 'Pusat kegiatan Karang Taruna dengan berbagai produk unggulan hasil laut'],
                ['nama' => 'Desa Tanjung Pasir', 'umkm' => 12, 'produk' => 25, 'deskripsi' => 'Terkenal dengan produk kerupuk dan ikan asin berkualitas tinggi'],
                ['nama' => 'Desa Muara', 'umkm' => 10, 'produk' => 20, 'deskripsi' => 'Penghasil terasi dan bumbu dapur tradisional khas pesisir'],
                ['nama' => 'Desa Lemo', 'umkm' => 8, 'produk' => 15, 'deskripsi' => 'Sentra kerajinan dan olahan makanan laut tradisional'],
                ['nama' => 'Desa Pangkalan', 'umkm' => 5, 'produk' => 10, 'deskripsi' => 'Penghasil produk kuliner dan makanan olahan seafood'],
            ];
            @endphp
            
            @foreach($desaList as $index => $desa)
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="desa-card">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <div class="desa-image">
                                <img src="https://images.unsplash.com/photo-{{ 1520000000000 + $index * 100000 }}?w=400" alt="{{ $desa['nama'] }}" class="img-fluid" onerror="this.src='https://via.placeholder.com/400x300?text={{ urlencode($desa['nama']) }}'">
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="desa-body">
                                <h4 class="desa-name">{{ $desa['nama'] }}</h4>
                                <p class="desa-desc">{{ $desa['deskripsi'] }}</p>
                                <div class="desa-stats d-flex gap-4 mb-3">
                                    <div>
                                        <span class="stat-number">{{ $desa['umkm'] }}</span>
                                        <span class="stat-label">UMKM</span>
                                    </div>
                                    <div>
                                        <span class="stat-number">{{ $desa['produk'] }}</span>
                                        <span class="stat-label">Produk</span>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-sm btn-primary rounded-pill">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="section-map bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge" data-aos="fade-up">Lokasi</span>
            <h2 class="section-title-lg" data-aos="fade-up" data-aos-delay="100">
                Peta <span class="text-primary">Desa Mitra</span>
            </h2>
        </div>
        
        <div class="map-wrapper rounded-4 overflow-hidden shadow-lg" data-aos="fade-up" data-aos-delay="200">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63459.35247519685!2d106.62!3d-6.1!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f8!2sTeluknaga%2C%20Tangerang!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.page-header {
    padding: 140px 0 60px;
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
}

.page-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 800;
    margin-bottom: 16px;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
}

.section-desa-mitra {
    padding: 60px 0 100px;
}

.desa-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: var(--transition-base);
    height: 100%;
}

.desa-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.desa-image {
    height: 100%;
    min-height: 200px;
}

.desa-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.desa-body {
    padding: 24px;
}

.desa-name {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.desa-desc {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 16px;
}

.desa-stats .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    display: block;
}

.desa-stats .stat-label {
    font-size: 12px;
    color: var(--text-muted);
}

.section-map {
    padding: 100px 0;
}
</style>
@endpush
