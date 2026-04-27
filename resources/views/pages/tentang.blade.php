@extends('layouts.app')

@section('title', 'Tentang Kami - Karang Taruna Teluknaga')

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="section-badge" data-aos="fade-up">Tentang Kami</span>
                <h1 class="page-title" data-aos="fade-up" data-aos-delay="100">
                    Karang Taruna <span class="text-primary">Teluknaga</span>
                </h1>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Membangun ekonomi desa melalui digitalisasi dan pemberdayaan UMKM
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Vision Mission -->
<section class="section-vision">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="vision-card">
                    <div class="vision-icon">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h3>Visi</h3>
                    <p>Menjadi wadah pemberdayaan UMKM terdepan yang mengintegrasikan kearifan lokal dengan teknologi digital untuk meningkatkan kesejahteraan masyarakat pesisir Teluknaga.</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="vision-card">
                    <div class="vision-icon">
                        <i class="bi bi-bullseye"></i>
                    </div>
                    <h3>Misi</h3>
                    <ul class="mission-list">
                        <li>Memberdayakan UMKM melalui pelatihan dan pendampingan</li>
                        <li>Memfasilitasi akses permodalan dan pasar</li>
                        <li>Mengembangkan platform digital untuk promosi produk lokal</li>
                        <li>Membangun kemitraan dengan berbagai pihak</li>
                        <li>Melestarikan produk khas pesisir Teluknaga</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section-team bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge" data-aos="fade-up">Tim Kami</span>
            <h2 class="section-title-lg" data-aos="fade-up" data-aos-delay="100">
                Pengurus <span class="text-primary">Karang Taruna</span>
            </h2>
        </div>
        
        <div class="row g-4 justify-content-center">
            @php
            $team = [
                ['nama' => 'Ahmad Fadli', 'jabatan' => 'Ketua', 'foto' => '12'],
                ['nama' => 'Siti Nurhaliza', 'jabatan' => 'Wakil Ketua', 'foto' => '5'],
                ['nama' => 'Budi Santoso', 'jabatan' => 'Sekretaris', 'foto' => '11'],
                ['nama' => 'Dewi Anggraini', 'jabatan' => 'Bendahara', 'foto' => '9'],
            ];
            @endphp
            
            @foreach($team as $index => $member)
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="team-card">
                    <div class="team-image">
                        <img src="https://i.pravatar.cc/300?img={{ $member['foto'] }}" alt="{{ $member['nama'] }}" class="img-fluid">
                        <div class="team-social">
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                    <div class="team-body">
                        <h5>{{ $member['nama'] }}</h5>
                        <p>{{ $member['jabatan'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Timeline -->
<section class="section-timeline">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge" data-aos="fade-up">Perjalanan Kami</span>
            <h2 class="section-title-lg" data-aos="fade-up" data-aos-delay="100">
                Milestone <span class="text-primary">Pencapaian</span>
            </h2>
        </div>
        
        <div class="timeline">
            <div class="timeline-item" data-aos="fade-up">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2020</span>
                    <h4>Pendirian</h4>
                    <p>Karang Taruna Teluknaga didirikan dengan fokus pemberdayaan pemuda</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2021</span>
                    <h4>Program UMKM</h4>
                    <p>Memulai program pendampingan UMKM dengan 10 pelaku usaha pertama</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2022</span>
                    <h4>Kemitraan CSR PIK2</h4>
                    <p>Menjalin kerjasama dengan CSR PIK2 untuk akses permodalan</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2023</span>
                    <h4>Platform Digital</h4>
                    <p>Peluncuran platform digital untuk promosi produk UMKM</p>
                </div>
            </div>
            <div class="timeline-item" data-aos="fade-up" data-aos-delay="400">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <span class="timeline-date">2024</span>
                    <h4>50+ UMKM</h4>
                    <p>Berhasil memberdayakan lebih dari 50 UMKM di 5 desa mitra</p>
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

.section-vision {
    padding: 60px 0 100px;
}

.vision-card {
    background: var(--bg-light);
    border-radius: 24px;
    padding: 40px;
    height: 100%;
}

.vision-icon {
    width: 70px;
    height: 70px;
    background: var(--primary-color);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    margin-bottom: 24px;
}

.vision-card h3 {
    font-size: 1.5rem;
    margin-bottom: 16px;
}

.vision-card p {
    color: var(--text-muted);
    line-height: 1.7;
}

.mission-list {
    padding-left: 20px;
    color: var(--text-muted);
}

.mission-list li {
    margin-bottom: 12px;
    line-height: 1.6;
}

.section-team {
    padding: 100px 0;
}

.team-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
}

.team-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.team-image {
    position: relative;
    overflow: hidden;
}

.team-image img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
}

.team-social {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    display: flex;
    justify-content: center;
    gap: 12px;
    opacity: 0;
    transform: translateY(20px);
    transition: var(--transition-base);
}

.team-card:hover .team-social {
    opacity: 1;
    transform: translateY(0);
}

.team-social a {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-dark);
    transition: var(--transition-base);
}

.team-social a:hover {
    background: var(--primary-color);
    color: white;
}

.team-body {
    padding: 24px;
    text-align: center;
}

.team-body h5 {
    font-size: 1.1rem;
    margin-bottom: 4px;
}

.team-body p {
    color: var(--primary-color);
    font-size: 14px;
    margin: 0;
}

.section-timeline {
    padding: 100px 0;
}

.timeline {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
    padding-left: 40px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: var(--border-color);
}

.timeline-item {
    position: relative;
    padding-bottom: 40px;
}

.timeline-marker {
    position: absolute;
    left: -40px;
    width: 24px;
    height: 24px;
    background: var(--primary-color);
    border-radius: 50%;
    border: 4px solid white;
    box-shadow: var(--shadow-md);
}

.timeline-content {
    background: var(--bg-light);
    padding: 24px;
    border-radius: 16px;
}

.timeline-date {
    display: inline-block;
    padding: 4px 12px;
    background: var(--primary-light);
    color: var(--primary-color);
    border-radius: 100px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
}

.timeline-content h4 {
    font-size: 1.1rem;
    margin-bottom: 8px;
}

.timeline-content p {
    color: var(--text-muted);
    margin: 0;
    font-size: 14px;
}
</style>
@endpush
