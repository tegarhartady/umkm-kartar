@extends('layouts.dashboard-umkm')

@section('title', 'Daftar Transaksi - ' . Auth::guard('umkm')->user()->nama_toko)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('umkm.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Transaksi</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Daftar Transaksi</h1>
                    <p class="text-muted mb-0">Kelola pesanan dari pelanggan Anda</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Pesanan Masuk</h5>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal</th>
                                        <th>Produk</th>
                                        <th>Pembeli</th>
                                        <th>Harga Total</th>
                                        <th>Status Bayar</th>
                                        <th>Status Pengiriman</th>
                                        <th>Rating</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $t)
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-dark fw-bold border">{{ $t->transaction_code }}</span>
                                                @if($t->checkout_code)
                                                    <div class="x-small text-muted mt-1" style="font-size: 0.65rem;">Grup: {{ $t->checkout_code }}</div>
                                                @endif
                                            </td>
                                            <td>{{ $t->created_at->format('d M Y, H:i') }}</td>
                                             <td>
                                                <div class="fw-bold" style="font-size: 0.8rem;">
                                                     @if($t->items->count() > 0)
                                                         @foreach($t->items as $item)
                                                             <div class="mb-1 text-dark fw-bold" style="font-size: 0.75rem;">
                                                                 <i class="bi bi-dot"></i> {{ $item->product_name }} ({{ $item->quantity }}x)
                                                             </div>
                                                         @endforeach
                                                     @else
                                                         <div class="text-dark fw-bold" style="font-size: 0.75rem;">{{ $t->product->nama_produk ?? 'Produk Dihapus' }}</div>
                                                     @endif
                                                 </div>
                                                 @if($t->order_type == 'po')
                                                     <span class="badge bg-warning text-dark x-small mt-1" style="font-size: 0.65rem;">Pre-Order ({{ \Carbon\Carbon::parse($t->po_date)->format('d/m/Y') }})</span>
                                                 @endif
                                             </td>
                                             <td>
                                                 <div>{{ $t->buyer_name ?? ($t->user->name ?? '-') }}</div>
                                                 <small class="text-muted">{{ $t->buyer_phone ?? '-' }}</small>
                                             </td>
                                             <td>
                                                 <div class="fw-bold text-success" style="font-size: 0.9rem;">Rp {{ number_format($t->total_price, 0, ',', '.') }}</div>
                                                 @if($t->checkout_code && isset($grandTotals[$t->checkout_code]))
                                                     <div class="text-muted x-small mt-1" style="font-size: 0.65rem;" title="Total Transfer Pelanggan">
                                                         Total Transfer: Rp {{ number_format($grandTotals[$t->checkout_code], 0, ',', '.') }}
                                                     </div>
                                                 @endif
                                             </td>
                                            <td>
                                                @if($t->status == 'completed')
                                                    <span class="badge bg-success bg-opacity-10 text-success border-success border-opacity-25 px-2 py-1">Selesai</span>
                                                @elseif($t->status == 'paid' || $t->status == 'proses' || $t->status == 'ready' || $t->status == 'shipping')
                                                    <span class="badge bg-success bg-opacity-10 text-success border-success border-opacity-25 px-2 py-1">Berhasil</span>
                                                @elseif($t->status == 'pending')
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border-warning border-opacity-25 px-2 py-1">Belum Bayar</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border-danger border-opacity-25 px-2 py-1">{{ ucfirst($t->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($t->delivery_status == 'delivered')
                                                    <span class="badge bg-success bg-opacity-10 text-success border-success border-opacity-25 px-2 py-1">Selesai</span>
                                                @elseif($t->delivery_status == 'shipping')
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border-primary border-opacity-25 px-2 py-1">Pengiriman</span>
                                                @elseif($t->delivery_status == 'ready')
                                                    <span class="badge bg-info bg-opacity-10 text-info border-info border-opacity-25 px-2 py-1">Packing</span>
                                                @elseif($t->status == 'proses')
                                                    <span class="badge bg-info bg-opacity-10 text-info border-info border-opacity-25 px-2 py-1">Diproses</span>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border-secondary border-opacity-25 px-2 py-1">Menunggu</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($t->review)
                                                    <div class="text-warning small mb-1">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="bi bi-star{{ $i <= $t->review->rating ? '-fill' : '' }}"></i>
                                                        @endfor
                                                    </div>
                                                    <small class="text-muted d-block" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                        "{{ $t->review->comment }}"
                                                    </small>
                                                @else
                                                    <span class="text-muted small">Belum ada</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    @if($t->status == 'paid')
                                                        <form action="{{ route('umkm.transactions.update_status', $t->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="proses">
                                                            <button type="submit" class="btn btn-sm btn-primary">Terima & Buat</button>
                                                        </form>
                                                    @elseif($t->status == 'proses')
                                                        <form action="{{ route('umkm.transactions.update_status', $t->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="ready">
                                                            <input type="hidden" name="delivery_status" value="ready">
                                                            <button type="submit" class="btn btn-sm btn-success">Selesai Buat</button>
                                                        </form>
                                                    @elseif($t->status == 'ready' && $t->delivery_status == 'ready' && $t->delivery_type == 'delivery')
                                                        <form action="{{ route('umkm.transactions.update_status', $t->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="ready">
                                                            <input type="hidden" name="delivery_status" value="shipping">
                                                            <button type="submit" class="btn btn-sm btn-warning">Pick Up</button>
                                                        </form>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $t->id }}">Detail</button>
                                                    @if($t->status != 'pending')
                                                        <button type="button" class="btn btn-sm {{ !$t->order_photo && in_array($t->status, ['ready', 'shipping']) ? 'btn-primary' : 'btn-outline-primary' }} position-relative" data-bs-toggle="modal" data-bs-target="#uploadPhotoModal{{ $t->id }}">
                                                            <i class="bi bi-camera me-1"></i> {{ $t->order_photo ? 'Update Foto' : 'Upload Foto' }}
                                                            @if(!$t->order_photo && in_array($t->status, ['ready', 'shipping']))
                                                                <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                                                    <span class="visually-hidden">Unggah foto</span>
                                                                </span>
                                                            @endif
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($transactions->hasPages())
                            <div class="d-flex justify-content-center py-3">
                                {{ $transactions->links() }}
                            </div>
                        @endif

                        <!-- Modals -->
                        @foreach($transactions as $t)
                            <div class="modal fade" id="detailModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header bg-light border-0">
                                            <h5 class="modal-title fw-bold">Detail Pesanan #{{ $t->transaction_code }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Nama:</small>
                                                        <span class="fw-bold">{{ $t->buyer_name }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Telepon:</small>
                                                        <span class="fw-bold">{{ $t->buyer_phone }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Alamat:</small>
                                                        <span>{{ $t->buyer_address }}, {{ $t->buyer_city }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <small class="text-muted d-block">Metode Pengiriman:</small>
                                                        <span class="badge bg-info bg-opacity-10 text-info">{{ $t->delivery_type == 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}</span>
                                                    </div>
                                                    @if($t->checkout_code)
                                                    <div class="mt-3 p-2 bg-light rounded border-start border-primary border-4">
                                                        <small class="text-muted d-block">Grup Checkout:</small>
                                                        <span class="fw-bold text-primary">{{ $t->checkout_code }}</span>
                                                        <small class="text-muted d-block mt-1">Total Pembayaran Pelanggan:</small>
                                                        <span class="fw-bold text-dark">Rp{{ number_format($grandTotals[$t->checkout_code] ?? $t->total_price, 0, ',', '.') }}</span>
                                                        <div class="x-small text-muted mt-1" style="font-size: 0.7rem;">(Termasuk produk dari toko lain dalam satu keranjang)</div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold mb-3">Bukti Pembayaran</h6>
                                                    @if($t->payment_proof)
                                                        <a href="{{ asset('storage/' . $t->payment_proof) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $t->payment_proof) }}" 
                                                                 class="img-fluid rounded border shadow-sm" 
                                                                 style="max-height: 200px;"
                                                                 onerror="this.src='https://ui-avatars.com/api/?name=Bukti+Bayar&background=f8f9fa&color=6c757d&size=200&text=Gagal+Muat'">
                                                        </a>
                                                        <div class="mt-2 text-center">
                                                            <small class="text-muted">Klik gambar untuk memperbesar</small>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-light border text-center py-4">
                                                            <i class="bi bi-image text-muted d-block fs-2 mb-2"></i>
                                                            <span class="text-muted small">Belum ada bukti pembayaran</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <h6 class="fw-bold mb-3">Foto Pesanan Siap (Oleh UMKM)</h6>
                                                    @if($t->order_photo)
                                                        <a href="{{ asset('storage/' . $t->order_photo) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $t->order_photo) }}" 
                                                                 class="img-fluid rounded border shadow-sm" 
                                                                 style="max-height: 200px;"
                                                                 onerror="this.src='https://ui-avatars.com/api/?name=Foto+Pesanan&background=f8f9fa&color=6c757d&size=200&text=Gagal+Muat'">
                                                        </a>
                                                    @else
                                                        <div class="alert alert-light border text-center py-4 mb-0">
                                                            <i class="bi bi-camera text-muted d-block fs-2 mb-2"></i>
                                                            <span class="text-muted small">UMKM belum mengunggah foto pesanan</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-12">
                                                    <hr class="my-2 opacity-50">
                                                    <h6 class="fw-bold mb-3">Rincian Produk (Toko Anda)</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-borderless">
                                                            <tbody>
                                                                @php $productSubtotal = 0; @endphp
                                                                @forelse($t->items as $item)
                                                                    @php $productSubtotal += $item->subtotal; @endphp
                                                                    <tr>
                                                                        <td>
                                                                            <div class="fw-bold" style="font-size: 0.85rem;">{{ $item->product_name }}</div>
                                                                            <small class="text-muted">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</small>
                                                                        </td>
                                                                        <td class="text-end fw-bold align-middle">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                                    </tr>
                                                                @empty
                                                                    @if($t->product)
                                                                        @php $itemTotal = $t->price * $t->quantity; $productSubtotal = $itemTotal; @endphp
                                                                        <tr>
                                                                            <td>
                                                                                <div class="fw-bold" style="font-size: 0.85rem;">{{ $t->product->nama_produk }}</div>
                                                                                <small class="text-muted">{{ $t->quantity }} x Rp{{ number_format($t->price, 0, ',', '.') }}</small>
                                                                            </td>
                                                                            <td class="text-end fw-bold align-middle">Rp{{ number_format($itemTotal, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                    @endif
                                                                @endforelse
                                                            </tbody>
                                                            <tfoot class="border-top">
                                                                <tr>
                                                                    <td class="text-muted small ps-0">Subtotal Produk:</td>
                                                                    <td class="text-end small pe-0">Rp{{ number_format($productSubtotal, 0, ',', '.') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="text-muted small ps-0">Biaya Pengiriman (Toko Anda):</td>
                                                                    <td class="text-end small pe-0">Rp{{ number_format($t->delivery_fee ?? 0, 0, ',', '.') }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="fw-bold ps-0">Total Pendapatan Anda:</td>
                                                                    <td class="text-end fw-bold text-success pe-0" style="font-size: 1rem;">Rp{{ number_format($t->total_price, 0, ',', '.') }}</td>
                                                                </tr>
                                                                @if($t->checkout_code && isset($grandTotals[$t->checkout_code]))
                                                                <tr class="bg-light">
                                                                    <td class="small ps-1 py-1">Total Bayar Pelanggan (Grup):</td>
                                                                    <td class="text-end small pe-1 py-1">Rp{{ number_format($grandTotals[$t->checkout_code], 0, ',', '.') }}</td>
                                                                </tr>
                                                                @endif
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    @if($t->notes)
                                                        <div class="bg-light p-2 rounded mt-2">
                                                            <small class="text-muted d-block fw-bold mb-1">Catatan dari Pembeli:</small>
                                                            <span class="small">"{{ $t->notes }}"</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Upload Foto Pesanan -->
                            <div class="modal fade" id="uploadPhotoModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Upload Foto Pesanan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('umkm.transactions.upload_order_photo', $t->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body py-4">
                                                @if($t->order_photo)
                                                    <div class="text-center mb-3">
                                                        <img src="{{ asset('storage/' . $t->order_photo) }}" class="img-fluid rounded border shadow-sm" style="max-height: 200px;">
                                                        <p class="text-muted small mt-2">Foto saat ini</p>
                                                    </div>
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small">Pilih Foto (Hasil Jadi/Siap Kirim)</label>
                                                    <input type="file" name="order_photo" class="form-control" accept="image/*" required>
                                                    <div class="form-text">Maksimal 2MB (JPG, PNG, JPEG)</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Upload Foto</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-receipt" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
