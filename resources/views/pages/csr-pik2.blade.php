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
            <div class="col-lg-6" data-aos="fade-right">
                <img src="https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=600" alt="CSR PIK2" class="img-fluid rounded-4 shadow-lg">
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-badge">Tentang Program</span>
                <h2 class="section-title-lg mb-4">
                    Apa itu <span class="text-primary">CSR PIK2?</span>
                </h2>
                <p class="text-muted mb-4">
                    CSR PIK2 (Corporate Social Responsibility Pusat Industri Kecil 2) adalah program tanggung jawab sosial perusahaan yang berfokus pada pemberdayaan Usaha Mikro, Kecil, dan Menengah (UMKM) di wilayah pesisir Teluknaga.
                </p>
                <p class="text-muted mb-4">
                    Melalui kolaborasi dengan Karang Taruna Teluknaga, program ini memberikan berbagai bentuk dukungan untuk membantu UMKM lokal berkembang dan bersaing di era digital.
                </p>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="csr-stat-box">
                            <h3 class="text-primary">Rp 500 Jt+</h3>
                            <p>Total Bantuan</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="csr-stat-box">
                            <h3 class="text-primary">50+</h3>
                            <p>UMKM Dibantu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs -->
<section class="section-programs bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge" data-aos="fade-up">Program Kami</span>
            <h2 class="section-title-lg" data-aos="fade-up" data-aos-delay="100">
                Bentuk <span class="text-primary">Dukungan</span>
            </h2>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h4>Bantuan Modal Usaha</h4>
                    <p>Akses permodalan tanpa bunga untuk pengembangan usaha UMKM</p>
                    <ul class="program-features">
                        <li><i class="bi bi-check2"></i> Pinjaman tanpa bunga</li>
                        <li><i class="bi bi-check2"></i> Tenor fleksibel</li>
                        <li><i class="bi bi-check2"></i> Proses mudah</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h4>Pelatihan & Workshop</h4>
                    <p>Program peningkatan kapasitas dan keterampilan pelaku UMKM</p>
                    <ul class="program-features">
                        <li><i class="bi bi-check2"></i> Digital marketing</li>
                        <li><i class="bi bi-check2"></i> Manajemen usaha</li>
                        <li><i class="bi bi-check2"></i> Packaging & branding</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="bi bi-tools"></i>
                    </div>
                    <h4>Bantuan Peralatan</h4>
                    <p>Penyediaan alat produksi modern untuk meningkatkan kapasitas</p>
                    <ul class="program-features">
                        <li><i class="bi bi-check2"></i> Mesin produksi</li>
                        <li><i class="bi bi-check2"></i> Alat pengemasan</li>
                        <li><i class="bi bi-check2"></i> Peralatan pendukung</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h4>Promosi & Pemasaran</h4>
                    <p>Dukungan pemasaran produk melalui berbagai channel</p>
                    <ul class="program-features">
                        <li><i class="bi bi-check2"></i> Social media marketing</li>
                        <li><i class="bi bi-check2"></i> Event pameran</li>
                        <li><i class="bi bi-check2"></i> Platform digital</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <h4>Legalitas & Sertifikasi</h4>
                    <p>Pendampingan pengurusan izin usaha dan sertifikasi produk</p>
                    <ul class="program-features">
                        <li><i class="bi bi-check2"></i> NIB & SIUP</li>
                        <li><i class="bi bi-check2"></i> Sertifikat halal</li>
                        <li><i class="bi bi-check2"></i> BPOM & PIRT</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                <div class="program-card">
                    <div class="program-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4>Pendampingan Usaha</h4>
                    <p>Mentoring dan konsultasi bisnis berkelanjutan</p>
                    <ul class="program-features">
                        <li><i class="bi bi-check2"></i> Business coaching</li>
                        <li><i class="bi bi-check2"></i> Financial planning</li>
                        <li><i class="bi bi-check2"></i> Problem solving</li>
                    </ul>
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

.program-card {
    background: white;
    border-radius: 20px;
    padding: 32px;
    height: 100%;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
}

.program-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.program-icon {
    width: 70px;
    height: 70px;
    background: #e8eef7;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #001f5c;
    margin-bottom: 20px;
}

.program-card h4 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 12px;
}

.program-card > p {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 20px;
}

.program-features {
    list-style: none;
    padding: 0;
    margin: 0;
}

.program-features li {
    padding: 6px 0;
    font-size: 14px;
    color: var(--text-dark);
}

.program-features i {
    color: #001f5c;
    margin-right: 8px;
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
</style>
@endpush
