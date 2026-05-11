@extends('layouts.app')

@section('title', $product->nama_produk . ' - Karang Taruna Teluknaga')

@section('content')

<!-- Product Detail -->
<section class="py-5">
    <div class="container">
        <div class="row g-5 mt-4">
            <!-- Product Image -->
            <div class="col-lg-5">
                <div style="height: 400px; border-radius: 20px; overflow: hidden; background: linear-gradient(45deg, #f8f9fa, #e9ecef);">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->nama_produk }}" class="w-100 h-100" style="object-fit: cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center">
                                <i class="bi bi-image" style="font-size: 4rem; color: #6c757d; opacity: 0.5;"></i>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-7">
                <!-- Breadcrumb -->
                {{-- <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-primary text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('katalog') }}" class="text-primary text-decoration-none">Katalog</a></li>
                        <li class="breadcrumb-item active">{{ $product->nama_produk }}</li>
                    </ol>
                </nav> --}}

                <!-- Category Badge -->
                <span class="badge bg-success mb-3">{{ $product->kategori }}</span>

                <!-- Title -->
                <h1 class="fw-bold mb-3">{{ $product->nama_produk }}</h1>

                <!-- UMKM Info -->
                <div class="d-flex align-items-center mb-4 pb-4 border-bottom justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-shop text-success me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="mb-0">{{ $product->umkm->nama_toko ?? 'UMKM' }}</h6>
                            <small class="text-muted">{{ $product->umkm->desa ?? '' }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="text-warning h5 mb-0">
                            @php $rating = $product->averageRating(); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                        <small class="text-muted">{{ $product->reviews->count() }} Penilaian</small>
                    </div>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <span class="h2 fw-bold text-success">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                    <span class="text-muted ms-3">/ {{ $product->satuan ?? 'pcs' }}</span>
                </div>

                <!-- Stock Info -->
                <div class="mb-4">
                    <p class="mb-2">
                        <i class="bi bi-box-seam me-2 text-success"></i>
                        <strong>Stok: {{ $product->stok }} {{ $product->satuan ?? 'pcs' }}</strong>
                    </p>
                    @if($product->stok < 5 && $product->stok > 0)
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <div>Stok terbatas! Segera pesan sebelum kehabisan.</div>
                        </div>
                    @elseif($product->stok <= 0)
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-x-circle me-2"></i>
                            <div>Stok habis. Hubungi penjual untuk info ketersediaan.</div>
                        </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <h6 class="fw-bold mb-3">Deskripsi Produk</h6>
                    <p class="text-muted lh-lg">{{ $product->deskripsi }}</p>
                </div>

                <!-- Quantity Selector -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Jumlah</h6>
                    <div class="input-group mb-3" style="width: 150px;">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(-1)">-</button>
                        <input type="number" id="display-qty" class="form-control text-center" value="1" min="1" max="{{ $product->stok }}" onchange="syncQty()">
                        <button class="btn btn-outline-secondary" type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="d-flex gap-3">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="quantity" id="cart-qty" value="1">
                        <button type="submit" class="btn btn-outline-success btn-lg grow px-4 rounded-pill shadow-sm {{ $product->stok <= 0 ? 'disabled' : '' }}">
                            <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                        </button>
                    </form>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="quantity" id="buy-qty" value="1">
                        <input type="hidden" name="redirect" value="cart">
                        <button type="submit" class="btn btn-success btn-lg grow px-5 rounded-pill shadow-sm {{ $product->stok <= 0 ? 'disabled' : '' }}">
                            <i class="bi bi-cart-check me-2"></i>Beli Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="row mt-5 pt-4">
            <div class="col-lg-8">
                <h4 class="fw-bold mb-4">Penilaian Produk</h4>
                @forelse($product->reviews()->with('user')->latest()->get() as $review)
                    <div class="card border-0 shadow-sm rounded-4 mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-light text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'Anonim' }}</h6>
                                        <div class="text-warning x-small">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                            </div>
                            <p class="text-muted mb-0 mt-3 fst-italic">"{{ $review->comment }}"</p>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light border text-center py-5 rounded-4">
                        <i class="bi bi-chat-dots fs-1 text-muted opacity-25 d-block mb-3"></i>
                        <p class="text-muted mb-0">Belum ada penilaian untuk produk ini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="row mt-5 pt-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4">Produk Terkait</h3>
                </div>
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col">
                            <a href="/beli/{{ $related->id }}" class="text-decoration-none">
                                <div class="card h-100 border-0 shadow-sm hover-shadow rounded-4 overflow-hidden">
                                    <div style="height: 200px; overflow: hidden; background: linear-gradient(45deg, #f8f9fa, #e9ecef);">
                                        @if($related->image)
                                            <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->nama_produk }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center h-100">
                                                <i class="bi bi-image text-muted opacity-50 fs-1"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="card-title fw-bold text-dark mb-0">{{ Str::limit($related->nama_produk, 25) }}</h6>
                                        </div>
                                        <p class="card-text text-muted x-small mb-2">{{ $related->umkm->nama_toko ?? 'UMKM' }}</p>
                                        <div class="text-warning x-small mb-2">
                                            @php $relRating = $related->averageRating(); @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($relRating) ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="card-text fw-bold text-success mb-0">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    function changeQty(amount) {
        const input = document.getElementById('display-qty');
        let val = parseInt(input.value) + amount;
        if (val < 1) val = 1;
        if (val > {{ $product->stok }}) val = {{ $product->stok }};
        input.value = val;
        syncQty();
    }

    function syncQty() {
        const val = document.getElementById('display-qty').value;
        document.getElementById('cart-qty').value = val;
        document.getElementById('buy-qty').value = val;
    }
</script>
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .badge {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
@endpush
