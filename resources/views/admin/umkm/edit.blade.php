@extends('layouts.dashboard')

@section('title', 'Edit UMKM')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.umkm.index') }}">UMKM</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.umkm.show', $umkm->id) }}">{{ $umkm->nama_toko }}</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <h1 class="h3 mb-0 fw-bold">Edit Data UMKM</h1>
            <p class="text-muted mb-0">Ubah informasi UMKM dengan benar dan lengkap</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Edit Data UMKM</h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Terjadi Kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.umkm.update', $umkm->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nama Toko & Pemilik -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">Nama Toko *</label>
                                <input type="text" name="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror" 
                                       value="{{ old('nama_toko', $umkm->nama_toko) }}" required>
                                @error('nama_toko') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">Nama Pemilik *</label>
                                <input type="text" name="pemilik" class="form-control @error('pemilik') is-invalid @enderror" 
                                       value="{{ old('pemilik', $umkm->pemilik) }}" required>
                                @error('pemilik') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Email & Telepon -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $umkm->email) }}" required>
                                @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">Telepon *</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $umkm->phone) }}" required>
                                @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Desa -->
                        <div class="mb-3">
                            <label class="form-label fw-600">Desa *</label>
                            <select name="desa" class="form-select @error('desa') is-invalid @enderror" required>
                                <option value="">-- Pilih Desa --</option>
                                @forelse($desas as $desaItem)
                                    <option value="{{ $desaItem->nama_desa }}" @selected($desaItem->nama_desa == $umkm->desa)>
                                        {{ $desaItem->nama_desa }}
                                    </option>
                                @empty
                                    <option value="" disabled>Tidak ada desa tersedia</option>
                                @endforelse
                            </select>
                            @error('desa') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="mb-3">
                            <label class="form-label fw-600">Alamat Lengkap *</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="3" required>{{ old('alamat', $umkm->alamat) }}</textarea>
                            @error('alamat') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Kategori & Status -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">Kategori Usaha *</label>
                                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->nama_kategori }}" {{ old('kategori', $umkm->kategori) == $category->nama_kategori ? 'selected' : '' }}>
                                            {{ $category->nama_kategori }}
                                        </option>
                                    @endforeach
                                    <option value="Lainnya" {{ old('kategori', $umkm->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('kategori') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">Status *</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="pending" {{ old('status', $umkm->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="disetujui" {{ old('status', $umkm->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="ditolak" {{ old('status', $umkm->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label class="form-label fw-600">Deskripsi Usaha</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      rows="4">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Omzet Bulanan -->
                        <div class="mb-4">
                            <label class="form-label fw-600">Omzet Bulanan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8f9fa; border: 1px solid #ddd; border-radius: 8px 0 0 8px;">Rp</span>
                                <input type="text" id="omzet_display_admin" class="form-control @error('omzet_bulanan') is-invalid @enderror" 
                                       placeholder="Contoh: 5.000.000" 
                                       value="{{ old('omzet_bulanan', $umkm->omzet_bulanan ? number_format($umkm->omzet_bulanan, 0, ',', '.') : '') }}" 
                                       style="border-radius: 0 8px 8px 0;">
                                <input type="hidden" name="omzet_bulanan" id="omzet_actual_admin" value="{{ $umkm->omzet_bulanan ?? '' }}">
                            </div>
                            @error('omzet_bulanan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Upload Foto Section -->
                        <div class="border-top pt-4 mt-4 mb-4">
                            <h6 class="fw-600 mb-3"><i class="bi bi-image me-2"></i>Unggah Foto</h6>
                            
                            <!-- Foto KTP -->
                            <div class="mb-4">
                                <label class="form-label fw-600">Foto KTP Pemilik</label>
                                <div class="input-group mb-2">
                                    <input type="file" name="foto_ktp" class="form-control @error('foto_ktp') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('foto_ktp') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <small class="text-muted d-block">Format: JPG, PNG | Max: 5MB</small>
                                
                                @if($umkm->foto_ktp)
                                    <div class="mt-3">
                                        <p class="small text-muted mb-2">Foto KTP Saat Ini:</p>
                                        @if(Storage::disk('public')->exists($umkm->foto_ktp))
                                            <div style="max-width: 250px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                <img src="{{ asset('storage/' . $umkm->foto_ktp) }}" 
                                                     alt="Foto KTP" 
                                                     class="img-fluid" 
                                                     style="width: 100%; height: auto; display: block;">
                                            </div>
                                        @else
                                            <div class="alert alert-warning" role="alert">
                                                <i class="bi bi-exclamation-triangle me-2"></i>File tidak ditemukan di storage
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Foto Tempat -->
                            <div class="mb-4">
                                <label class="form-label fw-600">Foto Tempat Usaha</label>
                                <div class="input-group mb-2">
                                    <input type="file" name="foto_tempat" class="form-control @error('foto_tempat') is-invalid @enderror" 
                                           accept="image/*">
                                    @error('foto_tempat') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <small class="text-muted d-block">Format: JPG, PNG | Max: 5MB | Rekomendasi ukuran: 800x600px</small>
                                
                                @if($umkm->foto_tempat)
                                    <div class="mt-3">
                                        <p class="small text-muted mb-2">Foto Tempat Usaha Saat Ini:</p>
                                        @if(Storage::disk('public')->exists($umkm->foto_tempat))
                                            <div style="max-width: 300px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                <img src="{{ asset('storage/' . $umkm->foto_tempat) }}" 
                                                     alt="Foto Tempat" 
                                                     class="img-fluid" 
                                                     style="width: 100%; height: auto; display: block;">
                                            </div>
                                        @else
                                            <div class="alert alert-warning" role="alert">
                                                <i class="bi bi-exclamation-triangle me-2"></i>File tidak ditemukan di storage
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Lokasi dengan OpenStreetMap -->
                        <div class="form-section mb-4 border-top pt-4 mt-4">
                            <h6 class="fw-600 mb-3"><i class="bi bi-geo-alt me-2"></i>Lokasi Usaha (Pilih di Peta)</h6>
                            
                            <!-- Map Container -->
                            <div class="mb-3">
                                <div id="map" style="height: 400px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
                                <small class="text-muted d-block mt-2">💡 Klik pada peta untuk memilih lokasi usaha</small>
                            </div>

                            <!-- Koordinat Display -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Latitude</label>
                                    <input type="number" id="latitude" name="latitude" step="0.000001" 
                                           class="form-control @error('latitude') is-invalid @enderror" 
                                           placeholder="-6.123456" value="{{ old('latitude', $umkm->latitude ?? '-6.1753') }}" readonly>
                                    @error('latitude') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Longitude</label>
                                    <input type="number" id="longitude" name="longitude" step="0.000001" 
                                           class="form-control @error('longitude') is-invalid @enderror" 
                                           placeholder="106.123456" value="{{ old('longitude', $umkm->longitude ?? '106.9749') }}" readonly>
                                    @error('longitude') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="border-top pt-4 mt-4 mb-4">
                            <h6 class="fw-600 mb-3"><i class="bi bi-key me-2"></i>Ubah Password (Opsional)</h6>
                            <p class="text-muted small mb-3">Kosongkan jika tidak ingin mengubah password</p>

                            <!-- Password Baru -->
                            <div class="mb-3">
                                <label class="form-label fw-600">Password Baru</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                                       placeholder="Masukkan password baru (minimal 8 karakter)">
                                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                <small class="text-muted d-block mt-1">Gunakan kombinasi huruf besar, kecil, angka, dan simbol untuk keamanan maksimal</small>
                            </div>

                            <!-- Password Konfirmasi -->
                            <div class="mb-4">
                                <label class="form-label fw-600">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       placeholder="Ulangi password baru">
                                @error('password_confirmation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Rekening & E-Wallet Section -->
                        <div class="border-top pt-4 mt-4 mb-4">
                            <h6 class="fw-600 mb-3"><i class="bi bi-wallet2 me-2"></i>Rekening / E-Wallet (Opsional)</h6>
                            <p class="text-muted small mb-3">Informasi rekening untuk transaksi pembayaran</p>

                            <!-- Tipe Rekening -->
                            <div class="mb-3">
                                <label class="form-label fw-600">Tipe Rekening / E-Wallet</label>
                                <select name="tipe_rekening" class="form-select @error('tipe_rekening') is-invalid @enderror">
                                    <option value="">-- Pilih Tipe --</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->nama_bank }}" {{ old('tipe_rekening', $umkm->tipe_rekening) == $bank->nama_bank ? 'selected' : '' }}>
                                            {{ $bank->nama_bank }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tipe_rekening') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <!-- Nomor Rekening -->
                            <div class="mb-3">
                                <label class="form-label fw-600">Nomor Rekening / No E-Wallet</label>
                                <input type="text" name="no_rekening" class="form-control @error('no_rekening') is-invalid @enderror" 
                                       value="{{ old('no_rekening', $umkm->no_rekening) }}" placeholder="Contoh: 1234567890">
                                @error('no_rekening') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                <small class="text-muted d-block mt-1">Nomor rekening atau nomor e-wallet yang aktif</small>
                            </div>

                            <!-- Nama Pemilik Rekening -->
                            <div class="mb-4">
                                <label class="form-label fw-600">Nama Pemilik Rekening</label>
                                <input type="text" name="nama_pemilik_rekening" class="form-control @error('nama_pemilik_rekening') is-invalid @enderror" 
                                       value="{{ old('nama_pemilik_rekening', $umkm->nama_pemilik_rekening) }}" placeholder="Nama sesuai rekening/e-wallet">
                                @error('nama_pemilik_rekening') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                <small class="text-muted d-block mt-1">Nama pemilik rekening untuk verifikasi pembayaran</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg grow">
                                <i class="bi bi-check me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.umkm.show', $umkm->id) }}" class="btn btn-outline-secondary btn-lg grow">
                                <i class="bi bi-x me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-label.fw-600 {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #001f5c;
    box-shadow: 0 0 0 0.2rem rgba(0, 31, 92, 0.15);
}

.form-control.is-invalid, .form-select.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.btn-primary {
    background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(0, 31, 92, 0.2);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 31, 92, 0.3);
    color: white;
}

.btn-lg.grow {
    flex: 1;
}

/* Leaflet CSS Override */
#map {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1;
}

.leaflet-control-container {
    font-family: inherit;
}

.leaflet-bar {
    border-radius: 8px;
}

.leaflet-control-search {
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.leaflet-control-search input {
    padding: 10px 12px;
    font-size: 14px;
    border-radius: 4px;
    border: 1px solid #ddd;
    width: 280px;
}

.leaflet-control-search button {
    background: #001f5c;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 4px;
}

.leaflet-control-search button:hover {
    background: #000f3d;
}

@media (max-width: 767px) {
    #map {
        height: 300px !important;
    }
    
    .leaflet-control-search input {
        width: 180px;
    }
}
</style>
@endpush

@push('scripts')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-control-geocoder/2.4.0/Control.Geocoder.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-control-geocoder/2.4.0/Control.Geocoder.min.js"></script>

<script>
// Default location: Teluknaga, Tangerang
const DEFAULT_LAT = -6.1753;
const DEFAULT_LNG = 106.9749;

let map;
let marker;

function initMap() {
    // Initialize Leaflet map
    map = L.map('map').setView([DEFAULT_LAT, DEFAULT_LNG], 15);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    // Get initial values from inputs
    const initialLat = parseFloat(document.getElementById('latitude').value) || DEFAULT_LAT;
    const initialLng = parseFloat(document.getElementById('longitude').value) || DEFAULT_LNG;

    // Create initial marker
    marker = L.marker([initialLat, initialLng], {
        draggable: true,
        title: 'Lokasi Usaha Anda - Drag untuk menggeser',
    }).addTo(map);

    // Update coordinates when marker is dragged
    marker.on('dragend', function() {
        const position = marker.getLatLng();
        document.getElementById('latitude').value = position.lat.toFixed(6);
        document.getElementById('longitude').value = position.lng.toFixed(6);
    });

    // Click on map to place marker
    map.on('click', function(e) {
        const position = e.latlng;
        marker.setLatLng(position);
        document.getElementById('latitude').value = position.lat.toFixed(6);
        document.getElementById('longitude').value = position.lng.toFixed(6);
    });

    // Add search/geocoding control
    const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
        position: 'topleft',
    })
    .on('markgeocode', function(e) {
        const bbox = e.geocode.bbox;
        const center = [
            (bbox.getSouthWest().lat + bbox.getNorthEast().lat) / 2,
            (bbox.getSouthWest().lng + bbox.getNorthEast().lng) / 2
        ];
        
        marker.setLatLng(center);
        map.fitBounds(bbox);
        
        document.getElementById('latitude').value = center[0].toFixed(6);
        document.getElementById('longitude').value = center[1].toFixed(6);
    })
    .addTo(map);

    // Add attribution
    L.control.attribution({
        prefix: '<a href="https://leafletjs.com">Leaflet</a>'
    }).addTo(map);

    // Ensure map resizes properly
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    initMap();
});

// Format Rupiah untuk Omzet Bulanan di Admin Edit
function formatRupiah(value) {
    // Hapus karakter non-digit
    value = value.replace(/\D/g, '');
    
    // Format dengan separator
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    return value;
}

const omzetDisplayAdmin = document.getElementById('omzet_display_admin');
const omzetActualAdmin = document.getElementById('omzet_actual_admin');

if (omzetDisplayAdmin && omzetActualAdmin) {
    omzetDisplayAdmin.addEventListener('input', function() {
        const formatted = formatRupiah(this.value);
        this.value = formatted;
        
        // Simpan nilai asli (tanpa format) ke input hidden
        omzetActualAdmin.value = this.value.replace(/\D/g, '');
    });

    // Set nilai awal jika ada
    if (omzetDisplayAdmin.value) {
        omzetActualAdmin.value = omzetDisplayAdmin.value.replace(/\D/g, '');
    }
    
    // Before form submit, ensure hidden input is filled
    const form = omzetDisplayAdmin.closest('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Update hidden field from display field
            const displayValue = omzetDisplayAdmin.value;
            if (displayValue) {
                omzetActualAdmin.value = displayValue.replace(/\D/g, '');
            }
        });
    }
}
</script>
@endpush
