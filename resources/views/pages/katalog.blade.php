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

<!-- Search Bar -->
<section class="section-search">
    <div class="container">
        <form action="{{ route('katalog') }}" method="GET" class="search-wrapper mb-4" data-aos="fade-up">
            <!-- Preserve filter params -->
            @if(request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            @if(request('desa'))
            <input type="hidden" name="desa" value="{{ request('desa') }}">
            @endif
            
            <div class="search-box">
                <i class="bi bi-search search-icon"></i>
                <input id="search-query" type="search" name="search" class="search-input" placeholder="Cari produk..." value="{{ request('search', '') }}">
                <button id="search-clear" class="search-clear" type="button"><i class="bi bi-x-lg"></i></button>
            </div>
        </form>
    </div>
</section>

<!-- Products Section with Sidebar -->
<section class="section-products-grid">
    <div class="container-fluid px-3 px-lg-4">
        <div class="row g-4">
            <!-- Sidebar Filter -->
            <div class="col-lg-2">
                <div class="filter-sidebar" data-aos="fade-up">
                    <!-- Mobile Toggle Button -->
                    <button class="filter-toggle d-lg-none" id="filter-toggle">
                        <i class="bi bi-funnel"></i> Filter
                    </button>

                    <div class="filter-sidebar-content" id="filter-sidebar-content">
                        <form id="filter-form" method="GET" action="{{ route('katalog') }}">
                            <!-- Category Dropdown -->
                            <div class="filter-section">
                                <div class="filter-header" data-toggle="dropdown-kategori">
                                    <h6 class="filter-label">
                                        <i class="bi bi-grid"></i> Kategori
                                    </h6>
                                    <i class="bi bi-chevron-down dropdown-icon"></i>
                                </div>
                                <div class="filter-options" id="dropdown-kategori">
                                    <div class="filter-option">
                                        <input type="radio" id="kat-all" name="kategori" value="" class="filter-input server-filter" @if(!request('kategori')) checked @endif>
                                        <label for="kat-all" class="filter-label-text">Semua Kategori</label>
                                    </div>
                                    @foreach($categories ?? [] as $category)
                                    <div class="filter-option">
                                        <input type="radio" id="kat-{{ str_replace(' ', '-', strtolower($category)) }}" name="kategori" value="{{ $category }}" class="filter-input server-filter" @if(request('kategori') == $category) checked @endif>
                                        <label for="kat-{{ str_replace(' ', '-', strtolower($category)) }}" class="filter-label-text">{{ $category }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Desa Dropdown -->
                            <div class="filter-section">
                                <div class="filter-header" data-toggle="dropdown-desa">
                                    <h6 class="filter-label">
                                        <i class="bi bi-geo-alt"></i> Desa
                                    </h6>
                                    <i class="bi bi-chevron-down dropdown-icon"></i>
                                </div>
                                <div class="filter-options" id="dropdown-desa">
                                    <div class="filter-option">
                                        <input type="radio" id="desa-all" name="desa" value="" class="filter-input server-filter" @if(!request('desa')) checked @endif>
                                        <label for="desa-all" class="filter-label-text">Semua Desa</label>
                                    </div>
                                    @foreach($desas ?? [] as $desa)
                                    <div class="filter-option">
                                        <input type="radio" id="desa-{{ str_replace(' ', '-', strtolower($desa->nama_desa)) }}" name="desa" value="{{ $desa->nama_desa }}" class="filter-input server-filter" @if(request('desa') == $desa->nama_desa) checked @endif>
                                        <label for="desa-{{ str_replace(' ', '-', strtolower($desa->nama_desa)) }}" class="filter-label-text">{{ $desa->nama_desa }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Clear Filters Button -->
                            <a href="{{ route('katalog') }}" class="btn btn-outline-secondary w-100 mt-3">
                                <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Filter
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-10">
                <div class="products-header d-flex justify-content-between align-items-center mb-4">
                    <div class="results-count">
                        <span id="results-count">{{ $products->total() ?? 0 }}</span> produk ditemukan
                    </div>
                    <div class="active-filters-inline" id="active-filters">
                        @if(request('kategori'))
                        <span class="active-filter-tag">
                            <i class="bi bi-grid"></i> {{ request('kategori') }}
                            <a href="{{ route('katalog', array_merge(request()->query(), ['kategori' => null])) }}" class="remove-filter"><i class="bi bi-x"></i></a>
                        </span>
                        @endif
                        
                        @if(request('desa'))
                        <span class="active-filter-tag">
                            <i class="bi bi-geo-alt"></i> {{ request('desa') }}
                            <a href="{{ route('katalog', array_merge(request()->query(), ['desa' => null])) }}" class="remove-filter"><i class="bi bi-x"></i></a>
                        </span>
                        @endif
                        
                        @if(request('search'))
                        <span class="active-filter-tag">
                            <i class="bi bi-search"></i> "{{ request('search') }}"
                            <a href="{{ route('katalog', array_merge(request()->query(), ['search' => null])) }}" class="remove-filter"><i class="bi bi-x"></i></a>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="row g-4">
                    @if($products && $products->count() > 0)
                        @foreach($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up">
                            <div class="product-card">
                                <div class="product-image">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" class="img-fluid">
                                    @else
                                        <img src="https://via.placeholder.com/400x300?text={{ urlencode($product->nama_produk) }}" alt="{{ $product->nama_produk }}" class="img-fluid">
                                    @endif
                                    @if($product->is_best_seller)
                                        <span class="product-badge bg-warning text-dark position-absolute top-0 start-0 m-2 px-2 py-1 rounded-pill shadow-sm fw-bold" style="font-size: 0.75rem;">
                                            <i class="bi bi-fire me-1"></i> Best Seller
                                        </span>
                                    @endif
                                    @if($product->stok < 5)
                                    <span class="product-badge bg-danger position-absolute top-0 end-0 m-2 px-2 py-1 rounded-pill" style="font-size: 0.75rem;">Stok Terbatas</span>
                                    @endif
                                </div>
                                <div class="product-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="product-category m-0">{{ $product->kategori }}</span>
                                        <div class="text-warning small" style="font-size: 0.8rem;">
                                            @php $rating = $product->reviews_avg_rating ?? 0; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}"></i>
                                            @endfor
                                            <span class="text-muted ms-1">({{ $product->reviews_count ?? 0 }})</span>
                                        </div>
                                    </div>
                                    <h5 class="product-title">{{ $product->nama_produk }}</h5>
                                    <p class="product-seller"><i class="bi bi-shop me-1"></i> {{ $product->umkm->nama_toko ?? 'UMKM' }} &middot; <small class="text-muted">{{ $product->umkm->desa ?? '' }}</small></p>
                                    <div class="product-footer d-flex justify-content-between align-items-center">
                                        <span class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                        <a href="/beli/{{ $product->id }}" class="btn btn-sm btn-primary rounded-pill">Detail</a>
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
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
        @endif
            </div>
        </div>
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
                                    <img src="https://via.placeholder.com/400x300?text={{ urlencode($desa->nama_desa) }}" alt="{{ $desa->nama_desa }}" class="img-fluid">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="desa-body">
                                    <h4 class="desa-name">{{ $desa->nama_desa }}</h4>
                                    <p class="desa-desc">{{ $desa->deskripsi ?? 'Desa mitra dengan berbagai produk unggulan UMKM.' }}</p>
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
                                    <a href="#" class="btn btn-sm btn-primary rounded-pill">Lihat Detail <i class="bi bi-arrow-right ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada desa mitra yang terdaftar</p>
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

/* ========== Search Section Styles ========== */
.section-search {
    padding: 40px 0 30px;
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
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

/* ========== Filter Sidebar Styles ========== */
.filter-sidebar {
    background: white;
    border-radius: 12px;
    padding: 0;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.filter-toggle {
    display: none;
    width: 100%;
    padding: 16px;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 12px;
    gap: 8px;
    align-items: center;
    justify-content: center;
}

.filter-toggle:hover {
    background: var(--primary-dark);
}

.filter-sidebar-content {
    padding: 20px;
}

.filter-section {
    margin-bottom: 24px;
    border-bottom: 1px solid #f1f3f5;
    padding-bottom: 24px;
}

.filter-section:last-child {
    margin-bottom: 0;
    border-bottom: none;
    padding-bottom: 0;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 8px 0;
    user-select: none;
    transition: all 0.3s ease;
}

.filter-header:hover {
    opacity: 0.7;
}

.filter-label {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-label i {
    color: var(--primary-color);
}

.dropdown-icon {
    font-size: 14px;
    color: var(--text-muted);
    transition: transform 0.3s ease;
}

.filter-header.active .dropdown-icon {
    transform: rotate(-180deg);
}

.filter-options {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transition: all 0.3s ease;
    margin-top: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-options.show {
    max-height: 500px;
    opacity: 1;
    margin-top: 12px;
}

.filter-option {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.filter-option:hover {
    background: #f8f9fa;
}

.filter-input {
    appearance: none;
    -webkit-appearance: none;
    width: 18px;
    height: 18px;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.filter-input:hover {
    border-color: var(--primary-color);
}

.filter-input:checked {
    background: var(--primary-color);
    border-color: var(--primary-color);
    position: relative;
}

.filter-input:checked::after {
    content: '✓';
    position: absolute;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    width: 100%;
    height: 100%;
}

.filter-label-text {
    margin: 0 0 0 10px;
    cursor: pointer;
    font-size: 14px;
    color: var(--text-dark);
    flex: 1;
}

/* ========== Products Header ========== */
.products-header {
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 12px;
}

.results-count {
    font-size: 14px;
    color: var(--text-muted);
}

.results-count span {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 16px;
}

.active-filters-inline {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    flex: 1;
    justify-content: flex-end;
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

/* ========== Products Grid ========== */
.section-products-grid {
    padding: 40px 0 100px;
    background: var(--bg-light);
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
    color: #001f5c !important;
    display: block;
}

.desa-stats .stat-label {
    font-size: 12px;
    color: var(--text-muted);
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
    .filter-sidebar {
        position: static;
        top: auto;
        margin-bottom: 30px;
    }

    .filter-toggle {
        display: flex;
    }

    .filter-sidebar-content {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: all 0.3s ease;
        padding: 0;
    }

    .filter-sidebar-content.show {
        max-height: 1000px;
        opacity: 1;
        padding: 20px;
    }

    .products-header {
        justify-content: space-between;
    }
}

@media (max-width: 767.98px) {
    .section-search {
        padding: 30px 0 20px;
    }

    .search-box {
        padding: 6px 16px;
    }

    .search-input {
        font-size: 15px;
        padding: 10px 0;
    }

    .filter-sidebar {
        border-radius: 8px;
    }

    .products-header {
        flex-direction: column;
    }

    .active-filters-inline {
        justify-content: flex-start;
    }

    .filter-label {
        font-size: 13px;
    }

    .filter-label-text {
        font-size: 13px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-query');
    const clearBtn = document.getElementById('search-clear');
    const filterToggle = document.getElementById('filter-toggle');
    const filterSidebarContent = document.getElementById('filter-sidebar-content');
    const filterForm = document.getElementById('filter-form');

    // Sidebar dropdown functionality
    document.querySelectorAll('.filter-header').forEach(header => {
        header.addEventListener('click', function() {
            const targetId = this.dataset.toggle;
            const targetEl = document.getElementById(targetId);
            
            // Close other dropdowns
            document.querySelectorAll('.filter-options').forEach(el => {
                if (el.id !== targetId) {
                    el.classList.remove('show');
                }
            });

            document.querySelectorAll('.filter-header').forEach(h => {
                if (h !== this) {
                    h.classList.remove('active');
                }
            });

            // Toggle current dropdown
            this.classList.toggle('active');
            targetEl?.classList.toggle('show');
        });
    });

    // Mobile filter toggle
    if (filterToggle) {
        filterToggle.addEventListener('click', function() {
            filterSidebarContent.classList.toggle('show');
        });
    }

    // Server-side filter input change handlers
    document.querySelectorAll('.server-filter').forEach(input => {
        input.addEventListener('change', function() {
            // Submit form to apply filter
            if (filterForm) {
                filterForm.submit();
            }
        });
    });

    // Search input handler - submit on debounce
    let searchTimeout;
    const searchForm = document.querySelector('form[action*="katalog.products"]');
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (searchForm) {
                searchForm.submit();
            }
        }, 500);
    });

    // Clear button handler
    clearBtn.addEventListener('click', function(e) {
        e.preventDefault();
        searchInput.value = '';
        if (searchForm) {
            searchForm.submit();
        }
    });
});
</script>
@endpush