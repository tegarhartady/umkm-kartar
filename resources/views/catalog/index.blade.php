<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk UMKM | Kartar Teluknaga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --text-dark: #2d3748;
            --text-muted: #718096;
            --bg-light: #f7fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
        }

        /* Background Pattern Animation */
        .catalog-hero {
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5rem 0;
            overflow: hidden;
        }

        /* Animated background shapes */
        .catalog-hero::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: float 8s ease-in-out infinite;
        }

        .catalog-hero::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(30px);
            }
        }

        .catalog-hero > * {
            position: relative;
            z-index: 1;
        }

        .catalog-hero h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
            animation: slideDown 0.8s ease;
        }

        .catalog-hero .lead {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            animation: slideUp 0.8s ease 0.1s backwards;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .input-group-lg {
            animation: slideUp 0.8s ease 0.2s backwards;
        }

        .input-group-lg .form-control {
            border: none;
            background: rgba(255, 255, 255, 0.95);
            font-size: 1rem;
            padding: 15px 20px;
        }

        .input-group-lg .form-control:focus {
            background: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-color: transparent;
        }

        .input-group-lg .btn-light {
            background: white;
            border: none;
            color: var(--primary-color);
            font-weight: 600;
        }

        .input-group-lg .btn-light:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Filter Section with Pattern */
        .filter-section {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 2rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .filter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        .filter-section > * {
            position: relative;
            z-index: 1;
        }

        .filter-section h5 {
            font-weight: 600;
            color: var(--text-dark);
        }

        .filter-section .form-select {
            border: 1px solid #e2e8f0;
            background: white;
            color: var(--text-dark);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .filter-section .form-select:hover,
        .filter-section .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Product Cards */
        .product-card {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            background: white;
        }

        .product-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            height: 250px;
            overflow: hidden;
            position: relative;
            background: var(--bg-light);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .product-card:hover .product-image img {
            transform: scale(1.15) rotate(2deg);
        }

        .no-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            color: var(--text-muted);
        }

        .no-image i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            opacity: 0.4;
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.85) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.4s ease;
            backdrop-filter: blur(4px);
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .btn-view-detail {
            background: white;
            color: var(--primary-color);
            border: none;
            padding: 12px 28px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-view-detail:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.08);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .price-tag {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            font-family: 'Courier New', monospace;
        }

        .category-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
            animation: slideInRight 0.6s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .umkm-info {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .umkm-info i {
            color: var(--primary-color);
            margin-right: 5px;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            color: var(--text-dark);
        }

        .card-text {
            color: var(--text-muted);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Pagination Styling */
        .pagination {
            gap: 0.5rem;
        }

        .page-link {
            border: 1px solid #e2e8f0;
            color: var(--primary-color);
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-color: transparent;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        footer > * {
            position: relative;
            z-index: 1;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .social-links a:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transform: translateY(-4px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .catalog-hero {
                padding: 3rem 0;
            }

            .catalog-hero h1 {
                font-size: 2rem;
            }

            .product-card {
                margin-bottom: 1rem;
            }

            footer .col-md-6:last-child {
                margin-top: 1.5rem;
                text-align: center !important;
            }
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* AOS Animation Customization */
        [data-aos] {
            opacity: 0;
        }

        [data-aos].aos-animate {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: #333;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-shop me-2"></i>Kartar UMKM
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('katalog') }}">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tentang') }}">Tentang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="catalog-hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">Katalog Produk UMKM</h1>
            <p class="lead mb-4">Temukan berbagai produk berkualitas dari UMKM lokal Kartar Teluknaga</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" placeholder="Cari produk...">
                        <button class="btn btn-light" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">{{ $products->total() }} produk ditemukan</h5>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                        <select class="form-select" style="width: auto;">
                            <option>Semua Kategori</option>
                            <option>Hasil Laut</option>
                            <option>Makanan Olahan</option>
                            <option>Bumbu Dapur</option>
                            <option>Kerajinan</option>
                            <option>Kuliner</option>
                        </select>
                        <select class="form-select" style="width: auto;">
                            <option>Urutkan</option>
                            <option>Harga Terendah</option>
                            <option>Harga Tertinggi</option>
                            <option>Terbaru</option>
                            <option>Populer</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="py-5">
        <div class="container">
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card product-card h-100">
                            <div class="product-image">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->nama_produk }}">
                                @else
                                    <div class="no-image">
                                        <i class="bi bi-image"></i>
                                        <span>No Image</span>
                                    </div>
                                @endif
                                <div class="category-badge">{{ $product->kategori }}</div>
                                <div class="product-overlay">
                                    <a href="{{ route('catalog.show', $product) }}" class="btn btn-view-detail">
                                        <i class="bi bi-eye me-2"></i>Lihat Detail
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title fw-bold">{{ $product->nama_produk }}</h6>
                                <p class="card-text text-muted small">{{ Str::limit($product->deskripsi, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="price-tag">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                                    <small class="text-muted">/ {{ $product->satuan }}</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="umkm-info">
                                        <i class="bi bi-shop me-1"></i>{{ $product->umkm->nama_toko }}
                                    </div>
                                    @if($product->stok > 0)
                                        <span class="badge bg-success">Stok: {{ $product->stok }}</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                    <h3 class="mt-3">Tidak ada produk tersedia</h3>
                    <p class="text-muted">Silakan coba lagi nanti atau hubungi admin.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>UMKM Kartar Teluknaga</h5>
                    <p class="mb-0">Mendukung ekonomi lokal melalui produk berkualitas</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Add AOS animations to product cards dynamically
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.col-lg-3');
            cards.forEach((card, index) => {
                card.setAttribute('data-aos', 'fade-up');
                card.setAttribute('data-aos-delay', index * 100);
            });
            
            // Reinitialize AOS for dynamically added elements
            AOS.refresh();
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</html>
