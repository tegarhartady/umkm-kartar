@extends('layouts.app')

@section('title', 'Keranjang Belanja - Karang Taruna Teluknaga')

@section('content')
<section class="py-5" style="margin-top: 100px !important;">
    <div class="container">
        <h2 class="fw-bold mb-4"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(count($groupedCart) > 0)
            <div class="row g-4">
                <div class="col-lg-8">
                    @foreach($groupedCart as $umkmId => $data)
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-light py-3 border-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-shop text-success me-2"></i>
                                    <h6 class="mb-0 fw-bold">{{ $data['name'] }}</h6>
                                    <span class="badge bg-success-subtle text-success ms-2 small">Penjual</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <tbody class="border-top-0">
                                            @foreach($data['items'] as $id => $details)
                                                <tr>
                                                    <td style="width: 100px; padding: 1.5rem;">
                                                        <div style="width: 80px; height: 80px; background: #f8f9fa; border-radius: 12px; overflow: hidden;">
                                                            @if($details['image'])
                                                                <img src="{{ asset('storage/' . $details['image']) }}" class="w-100 h-100" style="object-fit: cover;">
                                                            @else
                                                                <div class="d-flex align-items-center justify-content-center h-100">
                                                                    <i class="bi bi-image text-muted opacity-50 fs-4"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="fw-bold mb-1">{{ $details['name'] }}</h6>
                                                        <p class="text-success fw-bold mb-0">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                                    </td>
                                                    <td style="width: 150px;">
                                                        <div class="input-group input-group-sm">
                                                            <button class="btn btn-outline-secondary update-cart" data-id="{{ $id }}" data-qty="-1">-</button>
                                                            <input type="number" class="form-control text-center cart-qty-input" value="{{ $details['quantity'] }}" readonly>
                                                            <button class="btn btn-outline-secondary update-cart" data-id="{{ $id }}" data-qty="1">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="text-end fw-bold" style="width: 150px; padding-right: 1.5rem;">
                                                        Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                                                    </td>
                                                    <td style="width: 50px; padding-right: 1.5rem;">
                                                        <button class="btn btn-link text-danger p-0 remove-from-cart" data-id="{{ $id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 130px; z-index: 900;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Produk ({{ count(session('cart')) }})</span>
                                <span class="fw-bold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Jumlah Toko</span>
                                <span class="fw-bold">{{ count($groupedCart) }}</span>
                            </div>

                            <hr class="my-4">

                            <div class="alert alert-info small border-0 shadow-sm mb-4">
                                <i class="bi bi-info-circle me-2"></i>
                                Biaya pengiriman akan dihitung per toko (UMKM) pada saat checkout.
                            </div>

                            <div class="d-grid">
                                <a href="{{ route('cart.checkout') }}" class="btn btn-success btn-lg grow rounded-pill shadow-sm py-3 fw-bold">
                                    Lanjut ke Checkout
                                </a>
                                <a href="{{ route('katalog') }}" class="btn btn-link text-muted mt-2">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-cart-x" style="font-size: 5rem; color: #dee2e6;"></i>
                </div>
                <h3>Keranjang belanja Anda kosong</h3>
                <p class="text-muted mb-4">Yuk, cari produk menarik dari UMKM pilihan kami!</p>
                <a href="{{ route('katalog') }}" class="btn btn-success px-5 py-3 rounded-pill grow">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Update Cart
    document.querySelectorAll('.update-cart').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const qtyChange = parseInt(this.getAttribute('data-qty'));
            const input = this.parentElement.querySelector('.cart-qty-input');
            let newQty = parseInt(input.value) + qtyChange;
            
            if (newQty < 1) {
                if(!confirm('Hapus produk ini dari keranjang?')) return;
                newQty = 0;
            }
            
            fetch("{{ route('cart.update') }}", {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, quantity: newQty })
            }).then(() => window.location.reload());
        });
    });

    // Remove from Cart
    document.querySelectorAll('.remove-from-cart').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Hapus produk ini dari keranjang?')) {
                const id = this.getAttribute('data-id');
                fetch("{{ route('cart.remove') }}", {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id })
                }).then(() => window.location.reload());
            }
        });
    });
</script>
@endpush
