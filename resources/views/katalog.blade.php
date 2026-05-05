@extends('layouts.app')

@section('title', 'Katalog Produk - Karang Taruna Teluknaga')

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="section-badge" data-aos="fade-up">Katalog Produk</span>
                <h1 class="page-title" data-aos="fade-up" data-aos-delay="100">
                    Jelajahi Produk <span class="text-primary">Unggulan</span>
                </h1>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Temukan berbagai produk UMKM berkualitas dari pesisir Teluknaga
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="section-filter">
    <div class="container">
        <!-- Search Bar -->
        <div class="search-wrapper mb-4" data-aos="fade-up">
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input id="search-query" type="search" class="search-input" placeholder="Cari produk, kategori, atau desa...">
                <button id="search-clear" class="search-clear" type="button"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-wrapper" data-aos="fade-up" data-aos-delay="100">
            <!-- Category Pills -->
            <div class="filter-section">
                <h6 class="filter-label"><i class="bi bi-grid me-2"></i>Kategori</h6>
                <div class="filter-pills">
                    <button class="filter-pill active" data-filter="kategori" data-value="">
                        <i class="bi bi-collection"></i> Semua
                    </button>
                    @foreach($categories ?? [] as $category)
                    <button class="filter-pill" data-filter="kategori" data-value="{{ $category }}">
                        {{ $category }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Desa Pills -->
            <div class="filter-section">
                <h6 class="filter-label"><i class="bi bi-geo-alt me-2"></i>Desa</h6>
                <div class="filter-pills">
                    <button class="filter-pill active" data-filter="desa" data-value="">
                        <i class="bi bi-pin-map"></i> Semua Desa
                    </button>
                    @foreach($desas ?? [] as $desa)
                    <button class="filter-pill" data-filter="desa" data-value="{{ $desa->nama_desa }}">
                        {{ $desa->nama_desa }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Active Filters & Results Count -->
            <div class="filter-info">
                <div class="active-filters" id="active-filters"></div>
                <div class="results-count">
                    <span id="results-count">{{ $products->total() ?? 0 }}</span> produk ditemukan
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="section-products-grid">
    <div class="container">
        <div class="row g-4">
            @if($products && $products->count() > 0)
                @foreach($products as $product)
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="product-card" data-category="{{ $product->kategori }}" data-desa="{{ $product->umkm->desa ?? '' }}" data-title="{{ strtolower($product->nama_produk) }}">
                        <div class="product-image position-relative">
                            @php
                                $foto = $product->image;
                                if (!$foto && $product->foto_produk) {
                                    $foto_array = json_decode($product->foto_produk);
                                    $foto = (is_array($foto_array) && count($foto_array) > 0) ? $foto_array[0] : $product->foto_produk;
                                }
                                $foto_url = $foto ? asset('storage/' . $foto) : 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=400';
                            @endphp
                            <img src="{{ $foto_url }}" alt="{{ $product->nama_produk }}" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover;" onerror="this.src='https://via.placeholder.com/400x300?text={{ urlencode($product->nama_produk) }}'">
                            
                            @if($product->is_best_seller)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 px-2 py-1 rounded-pill shadow-sm fw-bold" style="font-size: 0.7rem; z-index: 10;">
                                    <i class="bi bi-fire me-1"></i> Best Seller
                                </span>
                            @endif

                            @if($product->stok < 5 && $product->stok > 0)
                                <span class="product-badge bg-danger">Stok Terbatas</span>
                            @elseif($product->stok <= 0)
                                <span class="product-badge bg-secondary">Habis</span>
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
                                    <span class="text-muted ms-1" style="font-size: 0.7rem;">({{ $product->reviews->count() }})</span>
                                </div>
                            </div>
                            <h5 class="product-title">{{ Str::limit($product->nama_produk, 30) }}</h5>
                            <p class="product-seller"><i class="bi bi-shop me-1"></i> {{ $product->umkm->nama_toko ?? 'UMKM' }} &middot; <small class="text-muted">{{ $product->umkm->desa ?? '' }}</small></p>
                            <div class="product-footer d-flex justify-content-between align-items-center mt-3">
                                <span class="product-price fw-bold text-primary">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                <a href="{{ route('catalog.show', $product->id) }}" class="btn btn-sm btn-primary rounded-pill px-3">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Tidak ada produk yang ditemukan</p>
                </div>
            @endif
        </div>
        
        <!-- Pagination -->
        @if($products && $products->hasPages())
        <div class="d-flex justify-content-center mt-5" data-aos="fade-up">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    @if($products->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->previousPageUrl() }}"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    @endif
                    
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if ($page == $products->currentPage())
                        <li class="page-item active"><a class="page-link" href="#">{{ $page }}</a></li>
                        @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                    
                    @if($products->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $products->nextPageUrl() }}"><i class="bi bi-chevron-right"></i></a>
                    </li>
                    @else
                    <li class="page-item disabled">
                        <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
        @endif
    </div>
</section>

<!-- Desa Mitra Section -->
<section class="py-5 bg-light">
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
    <section class="section-desa-mitra">
        <div class="container">
            <div class="row g-4">
                @forelse($desas as $index => $desa)
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="desa-card">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <div class="desa-image">
                                    <img src="https://images.unsplash.com/photo-{{ 1520000000000 + $index * 100000 }}?w=400" alt="{{ $desa->nama_desa }}" class="img-fluid" onerror="this.src='https://via.placeholder.com/400x300?text={{ urlencode($desa->nama_desa) }}'">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="desa-body">
                                    <h4 class="desa-name">{{ $desa->nama_desa }}</h4>
                                    <p class="desa-desc">{{ $desa->deskripsi ?? 'Desa dengan berbagai produk UMKM unggulan' }}</p>
                                    <div class="desa-stats d-flex gap-4 mb-3">
                                        <div>
                                            <span class="stat-number">{{ $desa->umkms_count ?? 0 }}</span>
                                            <span class="stat-label">UMKM</span>
                                        </div>
                                        <div>
                                            <span class="stat-number">{{ $desa->products_count ?? 0 }}</span>
                                            <span class="stat-label">Produk</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('desa-mitra') }}?desa={{ $desa->nama_desa }}" class="btn btn-sm btn-primary rounded-pill">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Data desa tidak tersedia</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</section>

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

/* ========== Filter Section Styles ========== */
.section-filter {
    padding: 15px 0;
    background: white;
    position: sticky;
    top: 95px; /* Slightly overlap navbar bottom border for seamless look */
    z-index: 990;
    border-bottom: 1px solid #f1f3f5;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* Search Box */
.search-wrapper {
    max-width: 600px;
    margin: 0 auto;
}

.search-box {
    position: relative;
    display: flex;
    align-items: center;
    background: white;
    border-radius: 60px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 8px 20px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.search-box:focus-within {
    border-color: var(--primary-color);
    box-shadow: 0 4px 25px rgba(0, 166, 126, 0.15);
}

.search-icon {
    font-size: 20px;
    color: var(--text-muted);
    margin-right: 12px;
}

.search-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 16px;
    padding: 12px 0;
    background: transparent;
}

.search-input::placeholder {
    color: #adb5bd;
}

.search-clear {
    background: #f1f3f4;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    color: var(--text-muted);
}

.search-clear:hover {
    background: #e9ecef;
    color: var(--text-dark);
}

/* Filter Wrapper */
.filter-wrapper {
    background: white;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
}

.filter-section {
    margin-bottom: 24px;
}

.filter-section:last-of-type {
    margin-bottom: 20px;
}

.filter-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
}

.section-desa-mitra {
    padding: 60px 0 100px;
}

.filter-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
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
    color: #001f5c !important;
    display: block;
}

.desa-stats .stat-label {
    font-size: 12px;
    color: var(--text-muted);
}

.filter-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    border: 2px solid #e9ecef;
    border-radius: 50px;
    background: white;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-dark);
    cursor: pointer;
    transition: all 0.25s ease;
}

.filter-pill i {
    font-size: 16px;
}

.filter-pill:hover {
    border-color: #001f5c;
    color: #001f5c;
    background: #e8eef7;
}

.filter-pill.active {
    background: #001f5c;
    border-color: #001f5c;
    color: white;
}

.filter-pill.active:hover {
    background: #000f3d;
    border-color: #000f3d;
}

/* Filter Info */
.filter-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid #f1f3f5;
}

.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.active-filter-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--primary-light);
    color: var(--primary-color);
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
}

.active-filter-tag .remove-filter {
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.active-filter-tag .remove-filter:hover {
    opacity: 1;
}

.results-count {
    font-size: 14px;
    color: var(--text-muted);
}

.results-count span {
    font-weight: 700;
    color: #001f5c;
}

/* ========== Products Grid ========== */
.section-products-grid {
    padding: 60px 0 100px;
    background: var(--bg-light);
}

.pagination .page-link {
    border: none;
    padding: 10px 16px;
    margin: 0 4px;
    border-radius: 8px;
    color: var(--text-dark);
}

.pagination .page-item.active .page-link {
    background: var(--primary-color);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 60px 20px;
    display: none;
}

.no-results.show {
    display: block;
}

.no-results-icon {
    font-size: 64px;
    color: #dee2e6;
    margin-bottom: 20px;
}

.no-results h4 {
    color: var(--text-dark);
    margin-bottom: 8px;
}

.no-results p {
    color: var(--text-muted);
}

/* Desa Mitra Cards */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
}

.btn-success {
    background-color: #1abc9c;
    border-color: #1abc9c;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background-color: #16a085;
    border-color: #16a085;
}

.text-success {
    color: #1abc9c !important;
}

/* Responsive */
@media (max-width: 991.98px) {
    .filter-wrapper {
        padding: 20px;
    }
    
    .filter-info {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
}

@media (max-width: 767.98px) {
    .section-filter {
        padding: 10px 0;
        top: 90px; /* Adjust for mobile navbar if needed, keeping it sticky */
    }
    
    .search-box {
        padding: 6px 16px;
    }
    
    .search-input {
        font-size: 15px;
        padding: 10px 0;
    }
    
    .filter-pill {
        padding: 8px 14px;
        font-size: 13px;
    }
    
    .filter-pills {
        gap: 8px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-query');
    const clearBtn = document.getElementById('search-clear');
    const activeFiltersContainer = document.getElementById('active-filters');
    const resultsCount = document.getElementById('results-count');
    const productsGrid = document.querySelector('.section-products-grid .row');

    // Get all product cards
    const allProductCards = Array.from(document.querySelectorAll('.product-card'));

    // Get current filter state from URL params
    const urlParams = new URLSearchParams(window.location.search);
    let filters = {
        search: urlParams.get('search') || '',
        kategori: urlParams.get('kategori') || '',
        desa: urlParams.get('desa') || '',
        sort: urlParams.get('sort') || 'terbaru'
    };

    // Restore input values from URL
    if (filters.search) {
        searchInput.value = filters.search;
    }

    // Update active filter pills based on URL
    updateActiveFilterPills();

    function updateActiveFilterPills() {
        // Update kategori pills
        document.querySelectorAll('.filter-pill[data-filter="kategori"]').forEach(pill => {
            if (filters.kategori === pill.dataset.value) {
                pill.classList.add('active');
            } else {
                pill.classList.remove('active');
            }
        });

        // Update desa pills
        document.querySelectorAll('.filter-pill[data-filter="desa"]').forEach(pill => {
            if (filters.desa === pill.dataset.value) {
                pill.classList.add('active');
            } else {
                pill.classList.remove('active');
            }
        });
    }

    function applyClientSideFilters() {
        let visibleCount = 0;

        allProductCards.forEach(card => {
            const cardKategori = card.getAttribute('data-category') || '';
            const cardDesa = card.getAttribute('data-desa') || '';
            const cardTitle = (card.getAttribute('data-title') || '').toLowerCase();

            let isVisible = true;

            // Filter by category
            if (filters.kategori && cardKategori !== filters.kategori) {
                isVisible = false;
            }

            // Filter by desa
            if (filters.desa && cardDesa !== filters.desa) {
                isVisible = false;
            }

            // Filter by search
            if (filters.search) {
                const searchQuery = filters.search.toLowerCase();
                const cardNameSearch = card.querySelector('.product-title')?.textContent.toLowerCase() || '';
                if (!cardNameSearch.includes(searchQuery)) {
                    isVisible = false;
                }
            }

            // Show or hide the card
            const cardCol = card.closest('[class*="col-"]');
            if (cardCol) {
                cardCol.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            }
        });

        // Update results count
        resultsCount.textContent = visibleCount;

        // Update active filters display
        updateActiveFiltersDisplay();

        // Show no results message if needed
        toggleNoResults(visibleCount === 0);
    }

    function updateActiveFiltersDisplay() {
        activeFiltersContainer.innerHTML = '';

        if (filters.kategori) {
            activeFiltersContainer.innerHTML += `
                <span class="active-filter-tag">
                    <i class="bi bi-grid"></i> ${filters.kategori}
                    <i class="bi bi-x remove-filter" data-filter="kategori"></i>
                </span>
            `;
        }

        if (filters.desa) {
            activeFiltersContainer.innerHTML += `
                <span class="active-filter-tag">
                    <i class="bi bi-geo-alt"></i> ${filters.desa}
                    <i class="bi bi-x remove-filter" data-filter="desa"></i>
                </span>
            `;
        }

        if (filters.search) {
            activeFiltersContainer.innerHTML += `
                <span class="active-filter-tag">
                    <i class="bi bi-search"></i> "${filters.search}"
                    <i class="bi bi-x remove-filter" data-filter="search"></i>
                </span>
            `;
        }

        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-filter').forEach(btn => {
            btn.addEventListener('click', function() {
                const filterType = this.dataset.filter;
                
                if (filterType === 'search') {
                    filters.search = '';
                    searchInput.value = '';
                } else {
                    filters[filterType] = '';
                }
                
                updateActiveFilterPills();
                applyClientSideFilters();
            });
        });
    }

    function toggleNoResults(show) {
        let noResultsEl = document.querySelector('.no-results');
        
        if (!noResultsEl) {
            noResultsEl = document.createElement('div');
            noResultsEl.className = 'col-12 text-center py-5';
            noResultsEl.innerHTML = `<p class="text-muted">Tidak ada produk yang ditemukan</p>`;
            productsGrid.appendChild(noResultsEl);
        }

        noResultsEl.style.display = show ? '' : 'none';
    }

    // Search input handler - debounced
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filters.search = this.value;
            applyClientSideFilters();
        }, 300);
    });

    // Clear button handler
    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        filters.search = '';
        applyClientSideFilters();
    });

    // Filter pill buttons handler
    document.querySelectorAll('.filter-pill').forEach(pill => {
        pill.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            const value = this.dataset.value;

            filters[filterType] = value;
            updateActiveFilterPills();
            applyClientSideFilters();
        });
    });

    // Initial apply filters
    applyClientSideFilters();
});
</script>
@endpush