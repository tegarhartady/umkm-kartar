@extends('layouts.app')

@section('title', 'CSR PIK2 - Karang Taruna Teluknaga')

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="section-badge" data-aos="fade-up">CSR PIK2</span>
                <h1 class="page-title" data-aos="fade-up" data-aos-delay="100">
                    Program <span class="text-primary">CSR PIK2</span>
                </h1>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Kolaborasi untuk pemberdayaan ekonomi masyarakat pesisir Teluknaga
                </p>
            </div>
        </div>
    </div>
</section>

<!-- About CSR -->
<section class="section-about-csr">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                <img src="{{ asset('images/IMG_6410.JPG') }}" alt="CSR PIK2" class="img-fluid rounded-4 shadow-lg">
            </div>
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                <span class="section-badge">Tentang Program</span>
                <h2 class="section-title-lg mb-4">
                    Akselerasi UMKM Binaan <span class="text-primary">CSR PIK2</span>
                </h2>
                
                <h5 class="mb-3">Program Inkubasi UMKM</h5>
                <p class="text-muted mb-4">
                    Peningkatan kapasitas dan digitalisasi UMKM melalui pelatihan, pendampingan, dan platform website — mendorong UMKM naik kelas dan mendukung ekonomi lokal.
                </p>

            </div>
        </div>
    </div>
</section>

<!-- About CSR 2 - Gambar di Kiri -->
<section class="section-about-csr">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 order-lg-2" data-aos="fade-left" data-aos-delay="100">
                <img src="{{ asset('images/yangkedua.jpeg') }}" alt="CSR PIK2 2" class="img-fluid rounded-4 shadow-lg">
            </div>
            <div class="col-lg-6 order-lg-1" data-aos="fade-right" data-aos-delay="100">
                <h2 class="section-title-lg mb-4">
                    Mengapa Perlu <span class="text-primary">Akselerasi UMKM?</span>
                </h2>
                
                <p class="text-muted mb-4">
                    UMKM memiliki peran penting dalam perekonomian, namun masih menghadapi kendala dalam keuangan dan pemasaran digital. Program ini hadir untuk meningkatkan kapasitas dan digitalisasi UMKM melalui pelatihan dan platform website.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Why Acceleration Section -->
<section class="section-why-acceleration bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="section-badge" data-aos="fade-up">Manfaat Program</span>
                <h2 class="section-title-lg" data-aos="fade-up" data-aos-delay="100">
                    Manfaat Bagi <span class="text-primary">UMKM</span>
                </h2>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Dapatkan berbagai keuntungan dan dukungan untuk mengembangkan bisnis Anda
                </p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Row 1: 3 cards -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5>Kapasitas UMKM</h5>
                    <p class="text-muted mb-0">Meningkatkan kapasitas UMKM binaan</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h5>Digitalisasi</h5>
                    <p class="text-muted mb-0">Mendorong digitalisasi UMKM</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-shop"></i>
                    </div>
                    <h5>Akses Pasar</h5>
                    <p class="text-muted mb-0">Meningkatkan akses pasar</p>
                </div>
            </div>

            <!-- Row 2: 2 cards -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-star"></i>
                    </div>
                    <h5>UMKM Unggulan</h5>
                    <p class="text-muted mb-0">Menciptakan UMKM unggulan</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5>Ekonomi Lokal</h5>
                    <p class="text-muted mb-0">Mendukung ekonomi lokal</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Sasaran Program Section -->
<section class="section-programs">
    <div class="container">
        <div class="row align-items-center g-5">
            <!-- Left: Interactive Map -->
            <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                <h2 class="section-title-lg mb-5">
                    Sasaran <span class="text-primary">Program</span>
                </h2>
                <div id="map" class="map-container rounded-4 shadow-lg"></div>
            </div>

            <!-- Right: Desa List -->
            <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                <div class="sasaran-info mb-5">
                    <p class="text-muted mb-2">Program menargetkan <strong class="text-dark">40-50 UMKM</strong> dari <strong class="text-dark">9 kecamatan</strong> di wilayah PIK2:</p>
                </div>

                <div class="desa-list">
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="100" data-lat="-6.2961" data-lng="106.5649" data-name="Kronjo">
                        <span>Kronjo</span>
                    </button>
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="150" data-lat="-6.1892" data-lng="106.5328" data-name="Mauk">
                        <span>Mauk</span>
                    </button>
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="200" data-lat="-6.2089" data-lng="106.5541" data-name="Kemiri">
                        <span>Kemiri</span>
                    </button>
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="250" data-lat="-6.1708" data-lng="106.5956" data-name="Teluknaga">
                        <span>Teluknaga</span>
                    </button>
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="300" data-lat="-6.2201" data-lng="106.5845" data-name="Kosambi">
                        <span>Kosambi</span>
                    </button>
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="350" data-lat="-6.2432" data-lng="106.5693" data-name="Pakuhaji">
                        <span>Pakuhaji</span>
                    </button>
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="400" data-lat="-6.2123" data-lng="106.6123" data-name="Sepatan">
                        <span>Sepatan</span>
                    </button>
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="450" data-lat="-6.2289" data-lng="106.6234" data-name="Sepatan Timur">
                        <span>Sepatan Timur</span>
                    </button>
                    <button class="desa-item" data-aos="fade-up" data-aos-delay="500" data-lat="-6.1645" data-lng="106.5672" data-name="Tanjung Pasir">
                        <span>Tanjung Pasir</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Join -->
<section class="section-cta-join">
    <div class="container">
        <div class="cta-join-wrapper rounded-4" data-aos="fade-up">
            <div class="row align-items-center">
                <div class="col-lg-8 text-center text-lg-start mb-4 mb-lg-0">
                    <h2>Tertarik Bergabung dengan Program CSR?</h2>
                    <p class="mb-0">Daftarkan UMKM Anda dan dapatkan berbagai manfaat dari program CSR PIK2</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <a href="{{ url('/daftar-umkm') }}" class="btn btn-light btn-lg px-5 rounded-pill">
                        Daftar Sekarang <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
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

.section-about-csr {
    padding: 60px 0 100px;
}

.csr-stat-box {
    background: #e8eef7;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
}

.csr-stat-box h3 {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 4px;
    color: #001f5c;
}

.csr-stat-box p {
    margin: 0;
    color: var(--text-muted);
    font-size: 14px;
}

.section-programs {
    padding: 100px 0;
}

.map-container {
    width: 100%;
    height: 450px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
}

.sasaran-info p {
    font-size: 1rem;
}

.desa-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.desa-item {
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 14px 20px;
    text-align: left;
    font-size: 1rem;
    font-weight: 500;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
}

.desa-item:hover {
    border-color: #001f5c;
    background: #f8f9fa;
    transform: translateX(8px);
}

.desa-item span {
    display: block;
}

.desa-item.active {
    background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
    border-color: #001f5c;
    color: white;
}

.section-cta-join {
    padding: 60px 0 100px;
}

.cta-join-wrapper {
    background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
    padding: 60px;
    color: white;
}

.cta-join-wrapper h2 {
    color: white;
    font-size: 2rem;
    margin-bottom: 8px;
}

.cta-join-wrapper p {
    color: rgba(255,255,255,0.85);
}

/* Why Acceleration Section */
.section-why-acceleration {
    padding: 100px 0;
}

.benefit-card {
    background: linear-gradient(135deg, #e8eef7 0%, #d5e1f2 100%);
    border-radius: 16px;
    padding: 32px;
    text-align: left;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid rgba(0, 31, 92, 0.1);
}

.benefit-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 24px rgba(0, 31, 92, 0.15);
}

.benefit-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    margin-bottom: 16px;
}

.benefit-card h5 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
}

.benefit-card p {
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 0;
}
</style>
@endpush

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Leaflet Map
    const map = L.map('map').setView([-6.2195, 106.5654], 11);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Markers data
    const markers = {};
    const desaItems = document.querySelectorAll('.desa-item');
    
    desaItems.forEach(item => {
        const lat = parseFloat(item.dataset.lat);
        const lng = parseFloat(item.dataset.lng);
        const name = item.dataset.name;
        
        // Create marker
        const marker = L.marker([lat, lng], {
            icon: L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            })
        }).bindPopup(`<div class="map-popup"><strong>${name}</strong></div>`).addTo(map);
        
        markers[name] = { marker, item };
        
        // Click event on button
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all items
            desaItems.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Pan to marker and open popup
            map.setView([lat, lng], 13);
            marker.openPopup();
        });
    });

    // Auto-select first marker on load
    if (desaItems.length > 0) {
        desaItems[0].click();
    }
});
</script>
@endpush
