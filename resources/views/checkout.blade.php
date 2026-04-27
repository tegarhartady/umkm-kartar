@extends('layouts.app')

@section('title', 'Checkout - ' . $product->nama_produk)

@section('content')

<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 2rem 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                    <!-- Header -->
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; text-align: center;">
                        <h2 class="fw-bold mb-2">Checkout Produk</h2>
                        <p class="mb-0">Lengkapi data untuk melanjutkan pembelian</p>
                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">
                        <!-- Product Summary -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-box me-2"></i>Ringkasan Produk</h6>
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div style="width: 80px; height: 80px; background: #e9ecef; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="fw-bold mb-1">{{ $product->nama_produk }}</h6>
                                        <p class="text-muted small mb-2">{{ $product->umkm->nama_toko }}</p>
                                        <div class="text-success fw-bold">Rp {{ number_format($product->harga, 0, ',', '.') }} / {{ $product->satuan }}</div>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-top">
                                    <label class="fw-bold mb-2">Jumlah:</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-outline-primary" onclick="changeQuantity(-1)">-</button>
                                        <input type="number" id="quantity" value="1" min="1" max="{{ $product->stok }}" class="form-control" style="width: 80px; text-align: center;" onchange="updateTotal()">
                                        <button type="button" class="btn btn-outline-primary" onclick="changeQuantity(1)">+</button>
                                        <span class="text-muted small ms-2">Stok: {{ $product->stok }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <strong>Total:</strong>
                                        <strong id="total" class="text-success fs-5">Rp {{ number_format($product->harga, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Form -->
                        <form action="/order/{{ $product->id }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" id="quantityInput" value="1">

                            <h6 class="fw-bold mb-3"><i class="bi bi-person me-2"></i>Data Pembeli</h6>

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap *</label>
                                <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" placeholder="Nama Lengkap" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="customer_email" class="form-control @error('customer_email') is-invalid @enderror" placeholder="Email" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nomor WhatsApp *</label>
                                <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" placeholder="08..." required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap *</label>
                                <textarea name="customer_address" class="form-control @error('customer_address') is-invalid @enderror" rows="3" placeholder="Alamat Lengkap" required></textarea>
                                @error('customer_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-warning d-flex gap-2 mb-4">
                                <i class="bi bi-shield-check"></i>
                                <div class="small">Data Anda aman. Kami akan menghubungi melalui WhatsApp untuk konfirmasi pesanan.</div>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('katalog') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success ms-auto">
                                    <i class="bi bi-credit-card"></i> Lanjut ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const productPrice = {{ $product->harga }};
    const maxStock = {{ $product->stok }};

    function changeQuantity(change) {
        const input = document.getElementById('quantity');
        let qty = parseInt(input.value) + change;
        if (qty < 1) qty = 1;
        if (qty > maxStock) qty = maxStock;
        input.value = qty;
        updateTotal();
    }

    function updateTotal() {
        const qty = parseInt(document.getElementById('quantity').value);
        const total = productPrice * qty;
        document.getElementById('subtotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('quantityInput').value = qty;
    }
</script>

@endsection