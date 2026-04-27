@extends('layouts.app')

@section('title', 'Pesanan Saya - Karang Taruna Teluknaga')

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <!-- Sidebar Profil (Ringkasan) -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-circle me-3" style="width: 50px; height: 50px; background: #001f5c; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; font-size: 1.2rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-truncate" style="max-width: 150px;">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">Pelanggan</small>
                        </div>
                    </div>
                    <hr class="text-muted opacity-25">
                    <ul class="nav flex-column gap-2">
                        <li class="nav-item">
                            <a href="/profile" class="nav-link text-dark p-0 d-flex align-items-center">
                                <i class="bi bi-person me-2 fs-5"></i> Profil Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/orders" class="nav-link text-primary fw-bold p-0 d-flex align-items-center">
                                <i class="bi bi-bag-check me-2 fs-5"></i> Pesanan Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Daftar Pesanan -->
        <div class="col-lg-9">
            <h4 class="fw-bold mb-4">Daftar Pesanan</h4>

            <!-- Filter Status -->
            <div class="d-flex overflow-auto pb-3 mb-4 gap-2 scrollbar-hide">
                <a href="/orders" class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Semua</a>
                <a href="/orders?status=pending" class="btn {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Belum Bayar</a>
                <a href="/orders?status=paid" class="btn {{ request('status') === 'paid' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Berhasil</a>
                <a href="/orders?status=failed" class="btn {{ request('status') === 'failed' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Gagal/Batal</a>
            </div>

            @forelse ($transactions as $order)
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden order-card">
                    <div class="card-header bg-white py-3 border-bottom border-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-light text-dark border me-2"><i class="bi bi-bag me-1"></i> Belanja</span>
                                <span class="text-muted small me-2">{{ $order->created_at->format('d M Y') }}</span>
                                <span class="badge {{ $order->status === 'paid' || $order->status === 'completed' ? 'bg-success bg-opacity-10 text-success' : ($order->status === 'pending' ? 'bg-warning bg-opacity-10 text-warning' : 'bg-danger bg-opacity-10 text-danger') }}">
                                    @if($order->status === 'paid' || $order->status === 'completed')
                                        BERHASIL
                                    @elseif($order->status === 'pending')
                                        BELUM BAYAR
                                    @else
                                        GAGAL
                                    @endif
                                </span>
                                <span class="ms-2 text-muted small d-none d-md-inline">/ {{ $order->transaction_code }}</span>
                            </div>
                            <div class="text-primary fw-bold">{{ $order->product->umkm->nama_umkm ?? 'Toko UMKM' }}</div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        @php
                                            $foto = $order->product->foto_produk ? json_decode($order->product->foto_produk)[0] : 'default.jpg';
                                        @endphp
                                        <img src="{{ asset('storage/' . $foto) }}" class="rounded-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">{{ $order->product->nama_produk }}</h6>
                                        <p class="text-muted small mb-0">{{ $order->quantity }} x Rp{{ number_format($order->price, 0, ',', '.') }}</p>
                                        @if($order->quantity > 1)
                                            <small class="text-muted">+ {{ $order->quantity - 1 }} produk lainnya</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-3 mt-md-0 border-start ps-md-4">
                                <p class="text-muted small mb-1">Total Belanja</p>
                                <h5 class="fw-bold text-dark mb-3">Rp{{ number_format($order->total_price, 0, ',', '.') }}</h5>
                                
                                <div class="d-grid gap-2">
                                    @if($order->status === 'pending' && $order->payment_method === 'midtrans' && $order->snap_token)
                                        <button onclick="payOrder('{{ $order->snap_token }}')" class="btn btn-primary btn-sm rounded-pill py-2">
                                            <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                                        </button>
                                    @elseif($order->status === 'pending' && ($order->payment_method === 'transfer' || $order->payment_method === 'qris'))
                                        <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-primary btn-sm rounded-pill py-2">
                                            <i class="bi bi-info-circle me-2"></i>Cara Bayar
                                        </a>
                                    @endif
                                    <a href="#" class="btn btn-light btn-sm rounded-pill py-2 text-muted border">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <img src="https://illustrations.popsy.co/blue/shopping-cart.svg" alt="Empty" style="width: 200px;" class="mb-4">
                    <h5 class="fw-bold">Belum ada pesanan</h5>
                    <p class="text-muted">Ayo mulai belanja produk UMKM terbaik dari Teluknaga!</p>
                    <a href="/katalog" class="btn btn-primary px-5 rounded-pill mt-2">Belanja Sekarang</a>
                </div>
            @endforelse

            <div class="mt-4 d-flex justify-content-center">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

@if($transactions->where('payment_method', 'midtrans')->where('status', 'pending')->count() > 0)
@php
    $isProduction = ($settings['midtrans_is_production']->value ?? '0') == '1';
    $snapSrc = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    $clientKey = $settings['midtrans_client_key']->value ?? '';
@endphp
<script src="{{ $snapSrc }}" data-client-key="{{ $clientKey }}"></script>
<script>
    function payOrder(snapToken) {
        window.snap.pay(snapToken, {
            onSuccess: function(result) { window.location.reload(); },
            onPending: function(result) { window.location.reload(); },
            onError: function(result) { alert("Pembayaran gagal!"); },
            onClose: function() { console.log('customer closed the popup without finishing the payment'); }
        });
    }
</script>
@endif

<style>
    .order-card {
        transition: transform 0.2s;
    }
    .order-card:hover {
        transform: translateY(-5px);
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endsection
