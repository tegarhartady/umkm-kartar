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
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" class="w-100 h-100" style="object-fit: cover;">
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
                <!-- Category Badge -->
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-success me-2">{{ $product->kategori }}</span>
                    @if($product->is_best_seller)
                        <span class="badge bg-warning text-dark fw-bold"><i class="bi bi-fire me-1"></i>Best Seller</span>
                    @endif
                </div>

                <!-- Title -->
                <h1 class="fw-bold mb-2">{{ $product->nama_produk }}</h1>

                <!-- Rating Summary -->
                <div class="d-flex align-items-center mb-4">
                    <div class="text-warning me-2">
                        @php $rating = $product->reviews_avg_rating ?? 0; @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="fw-bold me-1">{{ number_format($rating, 1) }}</span>
                    <span class="text-muted">({{ $product->reviews_count }} Ulasan)</span>
                    <span class="text-muted ms-2 px-2 border-start">&bull; {{ $product->transactions_sum_quantity ?? 0 }} Terjual</span>
                </div>

                <!-- UMKM Info -->
                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                    <i class="bi bi-shop text-success me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="mb-0">{{ $umkm->nama_toko }}</h6>
                        <small class="text-muted">{{ $umkm->desa }}</small>
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
                        <div class="alert alert-warning d-flex align-items-center py-2" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <div class="small">Stok terbatas! Segera pesan sebelum kehabisan.</div>
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
            <div class="d-flex flex-column flex-lg-row gap-3">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-block w-100" style="flex: 1;">
                    @csrf
                    <input type="hidden" name="quantity" id="cart-qty" value="1">
                    <button type="submit" class="btn btn-outline-success btn-lg grow px-4 w-100 {{ $product->stok <= 0 ? 'disabled' : '' }}">
                        <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                    </button>
                </form>
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-block w-100" style="flex: 1;">
                    @csrf
                    <input type="hidden" name="quantity" id="buy-qty" value="1">
                    <input type="hidden" name="redirect" value="checkout">
                    <button type="submit" class="btn btn-success btn-lg grow px-4 w-100 {{ $product->stok <= 0 ? 'disabled' : '' }}">
                        <i class="bi bi-cart-check me-2"></i>Beli Sekarang
                    </button>
                </form>
                <a href="/katalog" class="btn btn-outline-secondary btn-lg w-100 d-lg-inline-block" style="flex: 0 0 auto;">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
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
                    <div class="card h-100 border-0 shadow-sm hover-shadow">
                        <div class="position-relative" style="height: 200px; overflow: hidden; background: linear-gradient(45deg, #f8f9fa, #e9ecef);">
                            @if($related->image)
                            <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->nama_produk }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                            @endif
                            
                            @if($related->is_best_seller)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 fw-bold" style="font-size: 0.7rem;"><i class="bi bi-fire"></i> Best Seller</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">{{ $related->kategori }}</small>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <span class="text-dark fw-bold">{{ number_format($related->reviews_avg_rating ?? 0, 1) }}</span>
                                </div>
                            </div>
                            <h6 class="card-title fw-bold text-dark">{{ Str::limit($related->nama_produk, 30) }}</h6>
                            <p class="card-text text-muted small mb-2">{{ $related->umkm->nama_toko ?? 'UMKM' }}</p>
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
@endpush

@push('styles')
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
