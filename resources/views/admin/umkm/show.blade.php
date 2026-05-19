@extends('layouts.dashboard')

@section('title', 'Detail UMKM')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.umkm.index') }}">UMKM</a></li>
            <li class="breadcrumb-item active">{{ $umkm->nama_toko }}</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Informasi UMKM</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                    <i class="bi bi-shop" style="font-size: 3rem; color: #667eea;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-1">{{ $umkm->nama_toko }}</h3>
                            <p class="text-muted mb-3">Pemilik: {{ $umkm->pemilik }}</p>
                            
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th width="40%">Status</th>
                                    <td>
                                        @if($umkm->status == 'pending')
                                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                                        @elseif($umkm->status == 'disetujui' || $umkm->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($umkm->status == 'ditolak' || $umkm->status == 'rejected')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($umkm->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $umkm->kategori }}</td>
                                </tr>
                                <tr>
                                    <th>Desa</th>
                                    <td>{{ $umkm->desa }}</td>
                                </tr>
                                <tr>
                                    <th>Produk</th>
                                    <td>{{ $umkm->products->count() ?? 0 }} produk</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Kontak & Lokasi</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Email</p>
                            <p class="mb-3">{{ $umkm->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Telepon</p>
                            <p class="mb-3">{{ $umkm->phone }}</p>
                        </div>
                    </div>

                    <p class="text-muted small mb-1">Alamat</p>
                    <p class="mb-3">{{ $umkm->alamat }}</p>

                    @if($umkm->omzet_bulanan)
                        <p class="text-muted small mb-1">Omzet Bulanan</p>
                        <p class="mb-3">Rp {{ number_format($umkm->omzet_bulanan, 0, ',', '.') }}</p>
                    @endif

                    <!-- Rekening & E-Wallet Section -->
                    @if($umkm->tipe_rekening || $umkm->no_rekening || $umkm->nama_pemilik_rekening)
                        <hr class="my-4">
                        <h5 class="mb-3"><i class="bi bi-wallet2 me-2"></i>Rekening / E-Wallet</h5>
                        <div class="row">
                            @if($umkm->tipe_rekening)
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Tipe Rekening</p>
                                    <p class="mb-3"><strong>{{ $umkm->tipe_rekening }}</strong></p>
                                </div>
                            @endif
                            @if($umkm->no_rekening)
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1">Nomor Rekening / E-Wallet</p>
                                    <p class="mb-3"><code class="bg-light p-2 rounded">{{ $umkm->no_rekening }}</code></p>
                                </div>
                            @endif
                        </div>
                        @if($umkm->nama_pemilik_rekening)
                            <p class="text-muted small mb-1">Nama Pemilik Rekening</p>
                            <p class="mb-3">{{ $umkm->nama_pemilik_rekening }}</p>
                        @endif
                    @endif

                    <!-- Lokasi Maps Section -->
                    @if($umkm->latitude && $umkm->longitude)
                        <hr class="my-4">
                        <h5 class="mb-3"><i class="bi bi-geo-alt me-2"></i>Lokasi di Peta</h5>
                        <div id="map" style="height: 400px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px;"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Latitude</p>
                                <p class="mb-3"><code class="bg-light p-2 rounded">{{ $umkm->latitude }}</code></p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Longitude</p>
                                <p class="mb-3"><code class="bg-light p-2 rounded">{{ $umkm->longitude }}</code></p>
                            </div>
                        </div>
                    @endif

                    @if($umkm->deskripsi)
                        <hr class="my-4">
                        <h5 class="mb-3">Deskripsi</h5>
                        <p class="text-muted">{{ $umkm->deskripsi }}</p>
                    @endif

                    <hr class="my-4">

                    <p class="text-muted small">
                        <i class="bi bi-calendar me-1"></i>Terdaftar: {{ $umkm->created_at->format('d M Y H:i') }}<br>
                        <i class="bi bi-arrow-repeat me-1"></i>Diperbarui: {{ $umkm->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.umkm.edit', $umkm->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.umkm.destroy', $umkm->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menghapus UMKM ini?')">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tombol Approve/Reject untuk UMKM Pending -->
            @if($umkm->status === 'pending')
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Verifikasi</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">UMKM ini menunggu persetujuan Anda.</p>
                        <form action="{{ route('admin.umkm.approve', $umkm->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i>Setujui
                            </button>
                        </form>
                        <form action="{{ route('admin.umkm.reject', $umkm->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak UMKM {{ $umkm->nama_toko }}?')">
                                <i class="bi bi-x-circle me-2"></i>Tolak UMKM
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Tombol Reset Password -->
            @if($umkm->status === 'disetujui')
                <div class="alert alert-info" role="alert">
                    <strong>Password UMKM</strong>
                    <p class="mb-2">Jika UMKM lupa password, klik tombol di bawah untuk generate password baru: password123</p>
                    <form action="{{ route('admin.umkm.resetPassword', $umkm->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Generate password baru untuk UMKM ini?')">
                            <i class="bi bi-key"></i> Reset Password
                        </button>
                    </form>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Produk</h5>
                </div>
                <div class="card-body">
                    @if($umkm->products && $umkm->products->count() > 0)
                        <p class="small text-muted mb-3">{{ $umkm->products->count() }} produk terdaftar</p>
                        <div class="list-group list-group-flush">
                            @foreach($umkm->products->take(5) as $product)
                                <a href="{{ route('admin.products.show', $product->id) }}" class="list-group-item list-group-item-action px-0 py-2">
                                    <div class="small">{{ $product->nama }}</div>
                                    <small class="text-muted">Rp {{ number_format($product->harga, 0, ',', '.') }}</small>
                                </a>
                            @endforeach
                        </div>
                        @if($umkm->products->count() > 5)
                            <a href="{{ route('admin.products.index', ['umkm' => $umkm->id]) }}" class="btn btn-sm btn-outline-primary w-100 mt-2">
                                Lihat Semua Produk
                            </a>
                        @endif
                    @else
                        <p class="small text-muted mb-0">Belum ada produk</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Foto KTP dan Foto Tempat Usaha -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📷 Foto KTP</h6>
                </div>
                <div class="card-body">
                    @if($umkm->foto_ktp)
                        <img src="{{ asset('storage/' . $umkm->foto_ktp) }}" alt="Foto KTP" class="img-fluid rounded" style="max-height: 400px;">
                    @else
                        <p class="text-muted">Belum ada foto KTP</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📷 Foto Tempat Usaha</h6>
                </div>
                <div class="card-body">
                    @if($umkm->foto_tempat)
                        <img src="{{ asset('storage/' . $umkm->foto_tempat) }}" alt="Foto Tempat Usaha" class="img-fluid rounded" style="max-height: 400px;">
                    @else
                        <p class="text-muted">Belum ada foto tempat usaha</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.umkm.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<!-- Leaflet CSS & JS untuk Maps -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cek apakah ada map container
    const mapElement = document.getElementById('map');
    if (mapElement) {
        const latitude = parseFloat(mapElement.dataset.latitude || {{ $umkm->latitude ?? 'null' }});
        const longitude = parseFloat(mapElement.dataset.longitude || {{ $umkm->longitude ?? 'null' }});
        
        if (latitude && longitude && !isNaN(latitude) && !isNaN(longitude)) {
            // Initialize Leaflet map
            const map = L.map('map').setView([latitude, longitude], 15);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map);

            // Add marker at the location
            L.marker([latitude, longitude], {
                title: 'Lokasi UMKM',
            }).addTo(map).bindPopup(`<strong>{{ $umkm->nama_toko }}</strong><br>Lat: ${latitude.toFixed(6)}<br>Long: ${longitude.toFixed(6)}`);

            // Ensure map resizes properly
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }
    }
});
</script>

<style>
    #map {
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    code {
        color: #d63384;
        background-color: #f8f9fa !important;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-family: 'Courier New', monospace;
    }
</style>

@endsection
