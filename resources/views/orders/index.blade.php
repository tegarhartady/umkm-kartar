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
                <a href="/orders?status=proses" class="btn {{ request('status') === 'proses' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Diproses</a>
                <a href="/orders?status=pending" class="btn {{ request('status') === 'pending' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Belum Bayar</a>
                <a href="/orders?status=packing" class="btn {{ request('status') === 'packing' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Packing</a>
                <a href="/orders?status=pengiriman" class="btn {{ request('status') === 'pengiriman' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Pengiriman</a>
                <a href="/orders?status=delivered" class="btn {{ request('status') === 'delivered' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Diterima</a>
                <a href="/orders?status=selesai" class="btn {{ request('status') === 'selesai' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Selesai</a>
                <a href="/orders?status=gagal" class="btn {{ request('status') === 'gagal' ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4 flex-shrink-0">Gagal/Batal</a>
            </div>

            @forelse ($transactions as $order)
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden order-card">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark border me-2"><i class="bi bi-bag me-1"></i> Belanja</span>
                            <span class="text-muted small me-2">{{ $order->created_at->format('d M Y') }}</span>
                            @php
                                $status_labels = [
                                    'pending' => ['label' => 'Belum Bayar', 'class' => 'bg-warning'],
                                    'paid' => ['label' => 'Sudah Bayar', 'class' => 'bg-info'],
                                    'proses' => ['label' => 'Diproses', 'class' => 'bg-primary'],
                                    'ready' => ['label' => 'Siap Dikirim', 'class' => 'bg-primary'],
                                    'shipping' => ['label' => 'Dikirim', 'class' => 'bg-primary'],
                                    'delivered' => ['label' => 'Tiba di Tujuan', 'class' => 'bg-success'],
                                    'completed' => ['label' => 'Selesai', 'class' => 'bg-success'],
                                    'failed' => ['label' => 'Gagal', 'class' => 'bg-danger'],
                                    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-secondary'],
                                ];
                                $curr_status = $status_labels[$order->status] ?? ['label' => strtoupper($order->status), 'class' => 'bg-secondary'];
                            @endphp
                            <span class="badge {{ $curr_status['class'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $curr_status['class']) }}">
                                {{ strtoupper($curr_status['label']) }}
                            </span>
                            <span class="ms-2 text-muted small d-none d-md-inline">/ {{ $order->transaction_code }}</span>
                        </div>
                        <div class="text-primary fw-bold">{{ $order->product->umkm->nama_toko ?? 'Toko UMKM' }}</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    @if($order->product)
                                        @php
                                            $foto = $order->product->image;
                                            if (!$foto && $order->product->foto_produk) {
                                                $foto_array = json_decode($order->product->foto_produk);
                                                $foto = (is_array($foto_array) && count($foto_array) > 0) ? $foto_array[0] : $order->product->foto_produk;
                                            }
                                            
                                            if (!$foto) {
                                                $foto_url = asset('storage/default.jpg');
                                            } else {
                                                $foto_url = (strpos($foto, 'products/') === 0) ? asset('storage/' . $foto) : asset('storage/products/' . $foto);
                                            }
                                        @endphp
                                        <img src="{{ $foto_url }}" class="rounded-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order->product->nama_produk) }}&background=random&color=fff&size=80'">
                                    @else
                                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                                            <i class="bi bi-box text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    @if($order->product)
                                        <h6 class="fw-bold mb-1">{{ $order->product->nama_produk }}</h6>
                                        <p class="text-muted small mb-1">{{ $order->quantity }} x Rp{{ number_format($order->price, 0, ',', '.') }}</p>
                                        @if($order->order_type == 'po')
                                        <span class="badge bg-warning bg-opacity-10 text-warning border-warning border-opacity-25 x-small">
                                            <i class="bi bi-clock-history me-1"></i> Pre-Order (Target Selesai: {{ $order->po_date ? \Carbon\Carbon::parse($order->po_date)->format('d M Y') : '-' }})
                                        </span>
                                        @endif
                                    @else
                                        <h6 class="fw-bold mb-1 text-muted">Produk tidak tersedia</h6>
                                        <p class="text-muted small mb-1">{{ $order->quantity }} item</p>
                                    @endif
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
                                @if($order->status === 'pending' && ($order->payment_method === 'midtrans' || $order->snap_token))
                                <button onclick="payOrder('{{ $order->snap_token }}')" class="btn btn-primary btn-sm rounded-pill py-2">
                                    <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                                </button>
                                @elseif($order->status === 'pending' && ($order->payment_method === 'transfer' || $order->payment_method === 'qris'))
                                <button type="button" class="btn btn-primary btn-sm rounded-pill py-2" data-bs-toggle="modal" data-bs-target="#uploadProofModal{{ $order->id }}">
                                    <i class="bi bi-cloud-upload me-2"></i>{{ $order->payment_proof ? 'Ganti Bukti' : 'Upload Bukti' }}
                                </button>

                                @endif
                                @if($order->status === 'ready' || $order->delivery_status === 'shipping')
                                <form action="{{ route('transaction.update_status_user', $order->id) }}" method="POST" class="d-grid">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <input type="hidden" name="delivery_status" value="delivered">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill py-2" onclick="return confirm('Apakah pesanan sudah Anda terima dengan baik?')">
                                        <i class="bi bi-check-circle me-2"></i>Selesaikan Pesanan
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-light btn-sm rounded-pill px-3 py-2 text-muted border">
                                    <i class="bi bi-info-circle me-1"></i> Lihat Detail
                                </a>
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

            <!-- All Modals (Moved Outside Main Loop) -->
            @foreach ($transactions as $order)
                @if($order->status === 'pending' && ($order->payment_method === 'transfer' || $order->payment_method === 'qris'))
                <div class="modal fade" id="uploadProofModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Upload Bukti Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('transaction.upload_proof', $order->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <p class="text-muted small mb-3">Silakan upload foto bukti transfer Anda untuk diverifikasi oleh admin.</p>

                                    @if($order->payment_proof)
                                    <div class="mb-3">
                                        <p class="small fw-bold mb-1">Bukti Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $order->payment_proof) }}" class="img-fluid rounded border" style="max-height: 200px;">
                                    </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Pilih Foto Bukti</label>
                                        <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">Upload Sekarang</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

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
            onSuccess: function(result) {
                window.location.reload();
            },
            onPending: function(result) {
                window.location.reload();
            },
            onError: function(result) {
                alert("Pembayaran gagal!");
            },
            onClose: function() {
                console.log('customer closed the popup without finishing the payment');
            }
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
