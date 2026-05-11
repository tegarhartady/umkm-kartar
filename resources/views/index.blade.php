@extends('layouts.app')

@section('title', 'Sedayumart UMKM - Akselerasi UMKM Dampingan CSR PIK2')

@section('content')

@push('styles')
<style>
    /* Hero Section with Advanced Background Animation */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 120px 0 80px 0;
        overflow: hidden;
        background: linear-gradient(135deg, #001f5c 0%, #000f3d 50%, #001f5c 100%);
        background-size: 200% 200%;
        animation: gradientShift 8s ease infinite;
    }

    @keyframes gradientShift {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    /* Animated Background Shapes */
    .hero-section::before {
        content: '';
        position: absolute;
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        top: -200px;
        right: -200px;
        animation: float1 8s ease-in-out infinite;
        z-index: 0;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        bottom: -150px;
        left: -150px;
        animation: float2 10s ease-in-out infinite;
        z-index: 0;
    }

    @keyframes float1 {

        0%,
        100% {
            transform: translateY(0px) translateX(0px);
        }

        25% {
            transform: translateY(-30px) translateX(20px);
        }

        50% {
            transform: translateY(-50px) translateX(0px);
        }

        75% {
            transform: translateY(-30px) translateX(-20px);
        }
    }

    @keyframes float2 {

        0%,
        100% {
            transform: translateY(0px) translateX(0px);
        }

        25% {
            transform: translateY(30px) translateX(-20px);
        }

        50% {
            transform: translateY(50px) translateX(0px);
        }

        75% {
            transform: translateY(30px) translateX(20px);
        }
    }

    .hero-section .container {
        position: relative;
        z-index: 2;
    }

    /* Hero Content */
    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1px solid rgba(255, 255, 255, 0.3);
        animation: slideUpBadge 0.8s ease;
    }

    @keyframes slideUpBadge {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-title {
        font-size: clamp(2.5rem, 8vw, 4.5rem);
        font-weight: 900;
        color: white;
        line-height: 1.1;
        margin: 2rem 0;
        animation: slideDownTitle 0.8s ease 0.1s backwards;
        letter-spacing: -1px;
    }

    @keyframes slideDownTitle {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-subtitle {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.95);
        max-width: 600px;
        margin: 1.5rem auto;
        animation: slideUpSubtitle 0.8s ease 0.2s backwards;
        font-weight: 500;
        font-style: italic;
    }

    @keyframes slideUpSubtitle {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-buttons {
        animation: slideUpButtons 0.8s ease 0.3s backwards;
    }

    @keyframes slideUpButtons {
        from {
            opacity: 0;
            transform: translateY(40px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-buttons .btn {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        font-weight: 600;
        font-size: 1.1rem;
        padding: 14px 40px;
    }

    .hero-buttons .btn-primary {
        background: white;
        color: #001f5c;
        border: none;
    }

    .hero-buttons .btn-primary:hover {
        background: #001f5c;
        color: white;
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0, 31, 92, 0.4);
    }

    .hero-buttons .btn-outline-dark {
        color: white;
        border-color: white;
        border-width: 2px;
    }

    .hero-buttons .btn-outline-dark:hover {
        background: white;
        color: #001f5c;
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(255, 255, 255, 0.2);
    }

    /* Wave Animation - Lengkungan Gunung */
    .hero-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 1;
        max-height: 150px;
        overflow: hidden;
    }

    .hero-wave svg {
        width: 100%;
        height: 100%;
        display: block;
        animation: wave 8s linear infinite;
    }

    @keyframes wave {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(100px);
        }
    }

    .hero-wave svg path {
        animation: wavePath 15s ease-in-out infinite;
    }

    @keyframes wavePath {

        0%,
        100% {
            d: path('M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z');
        }

        50% {
            d: path('M0 110L60 95C120 80 240 50 360 55C480 60 600 40 720 45C840 50 960 70 1080 75C1200 80 1320 80 1380 80L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z');
        }
    }

    /* Multiple Wave Layers */
    .hero-wave::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 200%;
        height: 100%;
        background: url('data:image/svg+xml,<svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 60 Q360 30 720 60 T1440 60 L1440 120 L0 120 Z" fill="%23f8f9fa" opacity="0.1"/></svg>');
        background-repeat: repeat-x;
        animation: wave 12s linear infinite reverse;
        opacity: 0.3;
    }

    /* Animated Illustrations Background */
    .hero-illustrations {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 1;
        opacity: 0.15;
    }

    .illustration-shopper {
        position: absolute;
        animation: walk 8s ease-in-out infinite;
    }

    .illustration-shopper-1 {
        left: 5%;
        top: 20%;
        animation: walk1 8s ease-in-out infinite;
        transform: scaleX(-1);
    }

    .illustration-shopper-2 {
        right: 8%;
        top: 40%;
        animation: walk2 10s ease-in-out infinite;
    }

    @keyframes walk1 {

        0%,
        100% {
            transform: translateX(0) scaleX(-1);
        }

        50% {
            transform: translateX(40px) scaleX(-1);
        }
    }

    @keyframes walk2 {

        0%,
        100% {
            transform: translateX(0);
        }

        50% {
            transform: translateX(-50px);
        }
    }

    /* SVG Animation */
    .shopper-bag {
        animation: swing 2s ease-in-out infinite;
        transform-origin: top center;
    }

    @keyframes swing {

        0%,
        100% {
            transform: rotate(-15deg);
        }

        50% {
            transform: rotate(15deg);
        }
    }

    /* Decorative Elements */
    .hero-section .text-primary {
        background: linear-gradient(120deg, #001f5c, #000f3d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 900;
    }

    /* Contact Form Custom Styles */
    .contact-form .btn-primary {
        background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
        border: none;
    }

    .contact-form .btn-primary:hover {
        background: linear-gradient(135deg, #000f3d 0%, #001f5c 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 31, 92, 0.3);
    }

    .contact-form .form-control:focus {
        border-color: #001f5c;
        box-shadow: 0 0 0 0.2rem rgba(0, 31, 92, 0.15);
    }

    .text-primary {
        color: #001f5c !important;
    }

    .section-badge {
        background: #e8eef7;
        color: #001f5c;
    }

    .category-card {
        border-color: #001f5c;
    }

    .category-card:hover {
        border-color: #001f5c;
        background: #e8eef7;
    }

    .category-icon {
        color: #001f5c;
    }

    .cta-wrapper {
        background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
    }

    /* Button Styles */
    .btn-primary {
        background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
        border: none;
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #000f3d 0%, #001f5c 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 31, 92, 0.3);
    }

    .btn-sm.btn-primary {
        background: #001f5c;
        font-size: 0.85rem;
    }

    .btn-sm.btn-primary:hover {
        background: #000f3d;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navbar-brand img {
            height: 45px !important;
        }

        .hero-section {
            min-height: auto;
            padding: 130px 0 60px 0;
        }

        .hero-title {
            font-size: 1.85rem;
            margin: 1rem 0;
            line-height: 1.3;
        }

        .hero-subtitle {
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .hero-buttons {
            flex-direction: column;
            width: 100%;
            padding: 0 15px;
        }

        .hero-buttons .btn {
            width: 100%;
            margin-bottom: 10px;
            padding: 12px 20px;
            font-size: 1rem;
        }

        .hero-wave {
            max-height: 100px;
        }

        .hero-illustrations {
            display: none;
        }

        .testimonial-card {
            padding: 25px;
        }

        .section-testimonials,
        .section-contact {
            padding: 60px 0;
        }

        .floating-shape,
        .hero-wave {
            display: none;
        }
    }

    /* Testimonials Section */
    .section-testimonials {
        padding: 100px 0;
        background: #fff;
    }

    .testimonial-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        height: 100%;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .testimonial-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .testimonial-text {
        font-size: 1.1rem;
        color: #4a5568;
        font-style: italic;
        line-height: 1.7;
    }

    /* Contact Section */
    .section-contact {
        padding: 100px 0;
        background: #f8f9fa;
    }

    .contact-info .contact-icon {
        width: 50px;
        height: 50px;
        background: #e8eef7;
        color: #001f5c;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .contact-form-wrapper {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .contact-form .form-control {
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    .contact-form .form-label {
        font-weight: 600;
        color: #2d3748;
    }

    .contact-form .btn-primary {
        background: #001f5c;
        border: none;
    }
</style>
@endpush

<!-- Hero Section -->
<section class="hero-section">
    <!-- Animated Illustrations -->
    <div class="hero-illustrations">
        <!-- Shopper 1 -->
        <svg class="illustration-shopper illustration-shopper-1" viewBox="0 0 100 150" width="120" height="150">
            <!-- Head -->
            <circle cx="50" cy="25" r="12" fill="#fff" />
            <!-- Body -->
            <rect x="45" y="40" width="10" height="30" fill="#fff" />
            <!-- Arms -->
            <line x1="45" y1="45" x2="30" y2="60" stroke="#fff" stroke-width="3" stroke-linecap="round" />
            <line x1="55" y1="45" x2="75" y2="50" stroke="#fff" stroke-width="3" stroke-linecap="round" />
            <!-- Legs -->
            <line x1="48" y1="70" x2="45" y2="100" stroke="#fff" stroke-width="3" stroke-linecap="round" />
            <line x1="52" y1="70" x2="55" y2="100" stroke="#fff" stroke-width="3" stroke-linecap="round" />
            <!-- Shopping Bag -->
            <g class="shopper-bag">
                <rect x="70" y="55" width="18" height="25" fill="#ffd700" stroke="#fff" stroke-width="1.5" rx="2" />
                <path d="M 75 55 Q 78 48 79 55" stroke="#fff" stroke-width="1.5" fill="none" stroke-linecap="round" />
            </g>
        </svg>

        <!-- Shopper 2 -->
        <svg class="illustration-shopper illustration-shopper-2" viewBox="0 0 100 150" width="100" height="140">
            <!-- Head -->
            <circle cx="50" cy="28" r="10" fill="#fff" />
            <!-- Body -->
            <path d="M 45 40 Q 50 45 55 40 L 54 65 L 46 65 Z" fill="#fff" />
            <!-- Left Arm -->
            <line x1="46" y1="43" x2="25" y2="55" stroke="#fff" stroke-width="2.5" stroke-linecap="round" />
            <!-- Right Arm with Basket -->
            <line x1="54" y1="43" x2="72" y2="45" stroke="#fff" stroke-width="2.5" stroke-linecap="round" />
            <!-- Legs -->
            <line x1="48" y1="65" x2="46" y2="95" stroke="#fff" stroke-width="2.5" stroke-linecap="round" />
            <line x1="52" y1="65" x2="54" y2="95" stroke="#fff" stroke-width="2.5" stroke-linecap="round" />
            <!-- Basket -->
            <g class="shopper-bag">
                <rect x="68" y="40" width="16" height="20" fill="#764ba2" stroke="#fff" stroke-width="1.5" rx="1" />
                <path d="M 72 40 L 68 35 M 76 40 L 80 35 M 80 40 L 84 35" stroke="#fff" stroke-width="1" stroke-linecap="round" />
                <path d="M 68 45 L 84 45 M 68 50 L 84 50" stroke="#fff" stroke-width="0.8" opacity="0.6" />
            </g>
        </svg>

        <!-- Shopper 3 (coming from right) -->
        <svg class="illustration-shopper" viewBox="0 0 100 150" width="110" height="160" style="left: 85%; top: 15%; animation: walk3 9s ease-in-out infinite;">
            <!-- Head -->
            <circle cx="50" cy="22" r="11" fill="#fff" />
            <!-- Body -->
            <ellipse cx="50" cy="42" rx="8" ry="20" fill="#fff" />
            <!-- Left Arm -->
            <line x1="42" y1="40" x2="20" y2="50" stroke="#fff" stroke-width="2.5" stroke-linecap="round" />
            <!-- Right Arm with Bag -->
            <line x1="58" y1="40" x2="78" y2="35" stroke="#fff" stroke-width="2.5" stroke-linecap="round" />
            <!-- Legs (walking pose) -->
            <line x1="47" y1="62" x2="44" y2="100" stroke="#fff" stroke-width="2.5" stroke-linecap="round" />
            <line x1="53" y1="62" x2="57" y2="100" stroke="#fff" stroke-width="2.5" stroke-linecap="round" />
            <!-- Paper Bag -->
            <g class="shopper-bag">
                <rect x="75" y="25" width="14" height="22" fill="#ffa500" stroke="#fff" stroke-width="1.5" rx="1" />
                <path d="M 78 25 L 75 18 M 82 25 L 82 18" stroke="#fff" stroke-width="1.5" stroke-linecap="round" />
            </g>
        </svg>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <!-- Badge -->
                <div class="hero-badge mb-4" data-aos="fade-up">
                    <span>{{ App\Models\Setting::get('company_hero_badge', 'AKSELERASI UMKM Binaan CSR PIK2') }}</span>
                </div>

                <!-- Main Heading -->
                <h1 class="hero-title" data-aos="fade-up" data-aos-delay="100">
                    {!! App\Models\Setting::get('company_hero_title', 'Dukung<br>Produk Lokal<br>Pesisir.') !!}
                </h1>

                <!-- Subtitle -->
                <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                    "{{ App\Models\Setting::get('company_hero_subtitle', 'Membangun Ekonomi Pesisir Melalui Inkubasi dan Digitalisasi UMKM.') }}"
                </p>

                <!-- CTA Buttons -->
                <div class="hero-buttons d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ url('/katalog') }}" class="btn btn-primary btn-lg px-5 rounded-pill">
                        Jelajah Produk <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ url('/daftar-umkm') }}" class="btn btn-outline-dark btn-lg px-5 rounded-pill">
                        Daftar UMKM
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Wave -->
    <div class="hero-wave">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f8f9fa" />
        </svg>
    </div>
</section>

<!-- Recommended Products Section -->
<section class="section-products bg-light">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="section-title" data-aos="fade-right">
                    Rekomendasi <span class="text-primary">Terbaik</span>
                </h2>
            </div>
            <a href="{{ url('/katalog') }}" class="section-link text-primary" data-aos="fade-left">
                Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse($recommendedProducts as $index => $product)
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="product-card position-relative">
                    <div class="product-image">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400' }}" alt="{{ $product->nama_produk }}" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;">
                        @if($product->is_best_seller)
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill shadow-sm fw-bold">
                            <i class="bi bi-fire me-1"></i> Best Seller
                        </span>
                        @endif
                    </div>
                    <div class="product-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="product-category">{{ $product->kategori }}</span>
                            <div class="text-warning small">
                                @php $rating = $product->averageRating(); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}"></i>
                                    @endfor
                                    <span class="text-muted ms-1">({{ $product->reviews->count() }})</span>
                            </div>
                        </div>
                        <h5 class="product-title">{{ Str::limit($product->nama_produk, 30) }}</h5>
                        <p class="product-seller text-muted small mb-3"><i class="bi bi-shop me-1"></i> {{ $product->umkm->nama_toko ?? 'UMKM Pesisir' }}</p>
                        <div class="product-footer d-flex justify-content-between align-items-center mt-auto gap-2">
                            <span class="product-price fw-bold text-primary">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            <div class="d-flex gap-1">
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-2">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>
                                <a href="{{ route('beli', $product->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center text-muted">Tidak ada produk yang tersedia saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- About Section -->
<section class="section-about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about-image-wrapper">
                    <img src="{{ asset('images/IMG_0054.JPG') }}" alt="About Us" class="img-fluid rounded-4 shadow-lg">
                    <!--<div class="about-stats">-->
                    <!--    <div class="stat-item">-->
                    <!--        <h3>50+</h3>-->
                    <!--        <p>UMKM Terdaftar</p>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <span class="section-badge">Tentang Kami</span>
                <h2 class="section-title-lg mb-4">
                    {{ App\Models\Setting::get('about_heading', 'Manfaat UMKM') }}
                </h2>
                <p class="text-muted mb-4">
                    {{ App\Models\Setting::get('about_text', 'CSR PIK2 bekerja sama dengan Karang Taruna Teluknaga terus menunjukkan komitmennya dalam memberdayakan UMKM desa pesisir melalui digitalisasi dan pendampingan usaha, untuk membangun ekonomi yang berkelanjutan.') }}
                </p>
                <div class="about-features">
                    <div class="feature-item d-flex mb-3">
                        <div class="feature-icon me-3">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Pendampingan UMKM</h6>
                            <p class="text-muted small mb-0">Pelatihan dan pendampingan untuk pengembangan usaha</p>
                        </div>
                    </div>
                    <div class="feature-item d-flex mb-3">
                        <div class="feature-icon me-3">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Pemasaran Digital</h6>
                            <p class="text-muted small mb-0">Promosi produk melalui platform digital</p>
                        </div>
                    </div>
                    <div class="feature-item d-flex mb-3">
                        <div class="feature-icon me-3">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Akses Modal Usaha</h6>
                            <p class="text-muted small mb-0">Bantuan akses permodalan dari CSR PIK2</p>
                        </div>
                    </div>
                </div>
                <a href="{{ url('/tentang') }}" class="btn btn-primary btn-lg px-5 rounded-pill mt-4">
                    Pelajari Lebih Lanjut <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="section-stats">
    <div class="container">
        <div class="stats-wrapper rounded-4">
            <div class="row text-center g-4">
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-box">
                        <h2 class="stat-number">50+</h2>
                        <p class="stat-label">UMKM Terdaftar</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-box">
                        <h2 class="stat-number">281</h2>
                        <p class="stat-label">Produk Tersedia</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-box">
                        <h2 class="stat-number">14</h2>
                        <p class="stat-label">Desa Mitra</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-box">
                        <h2 class="stat-number">1000+</h2>
                        <p class="stat-label">Pelanggan Puas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section-categories">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge" data-aos="fade-up">Kategori Produk</span>
            <h2 class="section-title-lg" data-aos="fade-up" data-aos-delay="100">
                Jelajahi <span class="text-primary">Kategori</span> Kami
            </h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <a href="#" class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-water"></i>
                    </div>
                    <h5>Hasil Laut</h5>
                    <p>25 Produk</p>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <a href="#" class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5>Makanan Olahan</h5>
                    <p>40 Produk</p>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <a href="#" class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-palette"></i>
                    </div>
                    <h5>Kerajinan</h5>
                    <p>15 Produk</p>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <a href="#" class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <h5>Kuliner</h5>
                    <p>20 Produk</p>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="section-partners bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge" data-aos="fade-up">Mitra Kami</span>
            <h2 class="section-title-lg" data-aos="fade-up" data-aos-delay="100">
                Didukung Oleh
            </h2>
        </div>

        <div class="row align-items-center justify-content-center g-4">
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="400">
                <div class="partner-logo">
                    <img src="{{ asset('images/Logo/pemkab.png') }}" alt="Pemkab Tangerang" class="img-fluid">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="100">
                <div class="partner-logo">
                    <img src="{{ asset('images/Logo/asg.png') }}" alt="ASG Indonesia" class="img-fluid">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="200">
                <div class="partner-logo">
                    <img src="{{ asset('images/Logo/csrpik2.png') }}" alt="CSR PIK2" class="img-fluid">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="300">
                <div class="partner-logo">
                    <img src="{{ asset('images/cbd.png') }}" alt="Lokalin" class="img-fluid">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="300">
                <div class="partner-logo">
                    <img src="{{ asset('images/unnamed.png') }}" alt="Lokalin" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section-testimonials">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge" data-aos="fade-up">Testimoni</span>
            <h2 class="section-title-lg" data-aos="fade-up" data-aos-delay="100">
                Apa Kata <span class="text-primary">Mereka</span>
            </h2>
        </div>

        <div class="row g-4">
            @forelse($testimonials as $index => $testimonial)
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="testimonial-card">
                    <div class="testimonial-rating mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }} text-warning"></i>
                            @endfor
                    </div>
                    <p class="testimonial-text">"{{ $testimonial->content }}"</p>
                    <div class="testimonial-author d-flex align-items-center mt-4">
                        @if($testimonial->avatar)
                        <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-primary me-3 d-flex align-items-center justify-content-center text-white fw-bold" style="width: 60px; height: 60px;">
                            {{ substr($testimonial->name, 0, 1) }}
                        </div>
                        @endif
                        <div>
                            <h6 class="mb-0">{{ $testimonial->name }}</h6>
                            <small class="text-muted">{{ $testimonial->role }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <div class="testimonial-rating mb-3">
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                        <i class="bi bi-star-fill text-warning"></i>
                    </div>
                    <p class="testimonial-text">"Sejak bergabung dengan platform ini, penjualan kerupuk saya meningkat 3x lipat. Terima kasih Karang Taruna Teluknaga!"</p>
                    <div class="testimonial-author d-flex align-items-center mt-4">
                        <img src="https://i.pravatar.cc/60?img=11" alt="Pak Jaya" class="rounded-circle me-3">
                        <div>
                            <h6 class="mb-0">Pak Jaya</h6>
                            <small class="text-muted">Pemilik UMKM Kerupuk Udang</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section-contact bg-light">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="section-badge">Hubungi Kami</span>
                <h2 class="section-title-lg mb-4">
                    Ada <span class="text-primary">Pertanyaan?</span>
                </h2>
                <p class="text-muted mb-5">Jangan ragu untuk menghubungi kami. Tim kami siap membantu Anda.</p>

                <div class="contact-info">
                    <div class="contact-item d-flex mb-4">
                        <div class="contact-icon me-4">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <h6>Alamat</h6>
                            <p class="text-muted mb-0">Desa Teluknaga, Kec. Teluknaga,<br>Kabupaten Tangerang, Banten</p>
                        </div>
                    </div>
                    <div class="contact-item d-flex mb-4">
                        <div class="contact-icon me-4">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <h6>Email</h6>
                            <p class="text-muted mb-0">{{ App\Models\Setting::get('company_email', 'info@lokalin.id') }}</p>
                        </div>
                    </div>
                    <div class="contact-item d-flex mb-4">
                        <div class="contact-icon me-4">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <h6>Telepon</h6>
                            <p class="text-muted mb-0">{{ App\Models\Setting::get('company_phone', '+62 812 3456 7890') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="contact-form-wrapper">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                        @csrf
                        {{-- Honeypot Field for Security --}}
                        <div style="display: none;">
                            <input type="text" name="_hp_name" tabindex="-1" autocomplete="off">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subjek</label>
                                <input type="text" name="subject" class="form-control" placeholder="Subjek pesan">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Pesan</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Tulis pesan Anda..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold">
                                    Kirim Pesan <i class="bi bi-send ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Floating Event Sidebar -->
@php
$promo = $activePromotion ?? (object)[
'title' => 'Floating Market UMKM',
'subtitle' => 'Sunset Pier',
'description' => 'Ayo datang dan nikmati berbagai produk lokal berkualitas dari UMKM Teluknaga!',
'button_text' => 'Jelajahi Sekarang',
'button_link' => '/katalog'
];
@endphp
@if(isset($activePromotion) || true) {{-- Always show for now, but use DB if exists --}}
<div class="d-none d-xl-block" style="position: fixed; right: 30px; top: 120px; width: 280px; z-index: 99;">
    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px; background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%); color: white;">
        <div class="card-body p-4 text-center">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">
                    <i class="bi bi-stars me-1"></i> Event Spesial
                </span>
                <i class="bi bi-rocket-takeoff text-white-50 fs-4"></i>
            </div>

            <h3 class="fw-bold mb-1 text-white" style="font-size: 1.5rem; letter-spacing: -0.5px;">{{ $promo->title }}</h3>
            <h5 class="fw-bold mb-3 text-white-50" style="font-size: 1.1rem;">{{ $promo->subtitle }}</h5>

            <p class="small text-white-50 mb-4 px-2" style="line-height: 1.6; font-size: 0.85rem;">
                {{ $promo->description }}
            </p>

            <a href="{{ $promo->button_link }}" class="btn btn-light w-100 rounded-pill fw-bold shadow-sm py-2" style="font-size: 0.9rem;">
                {{ $promo->button_text }}
            </a>
        </div>
    </div>
</div>
@endif

@endsection