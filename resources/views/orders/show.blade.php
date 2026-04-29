@extends('layouts.app')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
                <div>
                    <a href="{{ route('orders.index') }}" class="btn btn-link text-decoration-none p-0 mb-2 text-muted">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Pesanan
                    </a>
                    <h3 class="fw-bold mb-0">Detail Pesanan <span class="text-primary">#{{ $transaction->transaction_code }}</span></h3>
                </div>
                <div class="text-end">
                    <span class="badge {{ $transaction->status === 'completed' ? 'bg-success' : 'bg-warning' }} bg-opacity-10 {{ $transaction->status === 'completed' ? 'text-success' : 'text-warning' }} px-3 py-2 rounded-pill">
                        {{ strtoupper($transaction->status == 'completed' ? 'Selesai' : ($transaction->status == 'pending' ? 'Belum Bayar' : 'Berhasil')) }}
                    </span>
                </div>
            </div>

            <div class="row g-4">
                <!-- Tracking Timeline -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Status Pengiriman</h5>
                            
                            <div class="tracking-timeline">
                                <!-- Status 1: Pesanan Dibuat -->
                                <div class="timeline-item {{ in_array($transaction->status, ['pending', 'paid', 'proses', 'ready', 'shipping', 'completed']) ? 'active' : '' }}">
                                    <div class="timeline-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-0">Pesanan Dibuat</h6>
                                        <p class="text-muted small mb-0">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>

                                <!-- Status 2: Pembayaran -->
                                <div class="timeline-item {{ in_array($transaction->status, ['paid', 'proses', 'ready', 'shipping', 'completed']) ? 'active' : '' }}">
                                    <div class="timeline-icon">
                                        <i class="bi bi-credit-card"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-0">Pembayaran Berhasil</h6>
                                        <p class="text-muted small mb-0">
                                            @if($transaction->paid_at)
                                                {{ $transaction->paid_at->format('d M Y, H:i') }}
                                            @elseif($transaction->status == 'pending')
                                                Menunggu pembayaran diverifikasi
                                            @else
                                                Terverifikasi
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Status 3: Proses -->
                                <div class="timeline-item {{ in_array($transaction->status, ['proses', 'ready', 'shipping', 'completed']) ? 'active' : '' }}">
                                    <div class="timeline-icon">
                                        <i class="bi bi-gear"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-0">Sedang Diproses</h6>
                                        <p class="text-muted small mb-0">UMKM sedang menyiapkan pesanan Anda</p>
                                    </div>
                                </div>

                                <!-- Status 4: Siap -->
                                <div class="timeline-item {{ in_array($transaction->status, ['ready', 'shipping', 'completed']) ? 'active' : '' }}">
                                    <div class="timeline-icon">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-0">Siap Dikirim/Diambil</h6>
                                        <p class="text-muted small mb-0">Pesanan telah dikemas dan siap</p>
                                    </div>
                                </div>

                                <!-- Status 5: Pengiriman -->
                                <div class="timeline-item {{ in_array($transaction->status, ['shipping', 'completed']) || $transaction->delivery_status == 'shipping' ? 'active' : '' }}">
                                    <div class="timeline-icon">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-0">Dalam Pengiriman</h6>
                                        <p class="text-muted small mb-0">Pesanan sedang dalam perjalanan ke lokasi Anda</p>
                                    </div>
                                </div>

                                <!-- Status 6: Selesai -->
                                <div class="timeline-item {{ $transaction->status == 'completed' ? 'active' : '' }}">
                                    <div class="timeline-icon">
                                        <i class="bi bi-check2-all"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold mb-0">Pesanan Selesai</h6>
                                        <p class="text-muted small mb-0">
                                            @if($transaction->completed_at)
                                                {{ $transaction->completed_at->format('d M Y, H:i') }}
                                            @else
                                                Pesanan telah diterima
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if(($transaction->status === 'ready' || $transaction->delivery_status === 'shipping') && $transaction->status !== 'completed')
                                <div class="mt-4 pt-3 border-top">
                                    <form action="{{ route('transaction.update_status_user', $transaction->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="completed">
                                        <input type="hidden" name="delivery_status" value="delivered">
                                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2" onclick="return confirm('Apakah pesanan sudah Anda terima dengan baik?')">
                                            Konfirmasi Pesanan Diterima
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Detail Produk</h5>
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0 me-3">
                                    @php
                                        // Cek field 'image' dulu (biasanya path lengkap: products/xxx.jpg)
                                        // Jika kosong, cek 'foto_produk' (biasanya JSON array)
                                        $foto = $transaction->product->image;
                                        
                                        if (!$foto && $transaction->product->foto_produk) {
                                            $foto_array = json_decode($transaction->product->foto_produk);
                                            $foto = (is_array($foto_array) && count($foto_array) > 0) ? $foto_array[0] : $transaction->product->foto_produk;
                                        }
                                        
                                        if (!$foto) {
                                            $foto_url = asset('storage/default.jpg');
                                        } else {
                                            // Jika path sudah ada prefix 'products/', gunakan asset('storage/' . $foto)
                                            // Jika tidak, tambahkan prefix 'products/'
                                            $foto_url = (strpos($foto, 'products/') === 0) ? asset('storage/' . $foto) : asset('storage/products/' . $foto);
                                        }
                                    @endphp
                                    <img src="{{ $foto_url }}" class="rounded-3 shadow-sm" style="width: 100px; height: 100px; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($transaction->product->nama_produk) }}&background=random&color=fff&size=100'">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">{{ $transaction->product->nama_produk }}</h6>
                                    <p class="text-primary fw-bold mb-0">Rp{{ number_format($transaction->price, 0, ',', '.') }}</p>
                                    <small class="text-muted">Jumlah: {{ $transaction->quantity }} {{ $transaction->product->satuan ?? 'pcs' }}</small>
                                </div>
                            </div>

                            <hr class="my-4 opacity-50">

                            <h5 class="fw-bold mb-3">Informasi Pengiriman</h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <p class="text-muted small mb-1">Nama Penerima</p>
                                    <p class="fw-bold mb-0">{{ $transaction->buyer_name }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-1">Nomor Telepon</p>
                                    <p class="fw-bold mb-0">{{ $transaction->buyer_phone }}</p>
                                </div>
                                <div class="col-12">
                                    <p class="text-muted small mb-1">Alamat Lengkap</p>
                                    <p class="mb-0">{{ $transaction->buyer_address }}, {{ $transaction->buyer_city }} {{ $transaction->buyer_postal_code }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-1">Metode Pengiriman</p>
                                    <p class="fw-bold mb-0 text-capitalize">{{ $transaction->delivery_type == 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted small mb-1">Metode Pembayaran</p>
                                    <p class="fw-bold mb-0 text-uppercase">{{ $transaction->payment_method }}</p>
                                </div>
                            </div>

                            <hr class="my-4 opacity-50">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Total Harga ({{ $transaction->quantity }} Produk)</span>
                                <span>Rp{{ number_format($transaction->price * $transaction->quantity, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Biaya Pengiriman</span>
                                <span>Rp{{ number_format($transaction->delivery_fee ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <h5 class="fw-bold mb-0">Total Belanja</h5>
                                <h4 class="fw-bold text-primary mb-0">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>

                    @if($transaction->notes)
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-2">Catatan Pesanan:</h6>
                                <p class="text-muted mb-0 italic">"{{ $transaction->notes }}"</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tracking-timeline {
        position: relative;
        padding-left: 45px;
    }

    .tracking-timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #edf2f7;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 30px;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: -45px;
        width: 42px;
        height: 42px;
        background: #fff;
        border: 2px solid #edf2f7;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a0aec0;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .timeline-item.active .timeline-icon {
        background: #4e73df;
        border-color: #4e73df;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
    }

    .timeline-item.active .timeline-content h6 {
        color: #2d3748;
    }

    .timeline-content h6 {
        color: #a0aec0;
        transition: all 0.3s ease;
    }

    /* Line logic */
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 42px;
        bottom: 0;
        width: 2px;
        background: #edf2f7;
        z-index: 0;
    }

    .timeline-item.active::before {
        background: #4e73df;
    }

    .timeline-item:last-child::before {
        display: none;
    }
</style>
@endsection
