@extends('layouts.app')

@section('title', 'Daftar UMKM - Karang Taruna Teluknaga')

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <img src="{{ asset('images/smartumkm.svg') }}" alt="Smart UMKM Logo" style="height: 80px; width: auto;" class="mb-3">
                <span class="section-badge" data-aos="fade-up">Bergabung</span>
                <h1 class="page-title" data-aos="fade-up" data-aos-delay="100">
                    Daftar <span class="text-primary">UMKM</span>
                </h1>
                <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                    Daftarkan usaha Anda dan nikmati berbagai keuntungan menjadi mitra kami
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="section-benefits">
    <div class="container">
        <div class="row g-4 justify-content-center mb-5">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-megaphone"></i>
                    </div>
                    <h5>Promosi Gratis</h5>
                    <p>Produk Anda akan dipromosikan di platform kami</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <h5>Pelatihan</h5>
                    <p>Akses pelatihan digital marketing dan bisnis</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h5>Akses Modal</h5>
                    <p>Kesempatan mendapat bantuan modal dari CSR</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5>Komunitas</h5>
                    <p>Bergabung dengan jaringan UMKM pesisir</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Registration Form -->
<section class="section-registration bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                <div class="registration-form-wrapper">
                    <h3 class="form-title text-center mb-4">Formulir Pendaftaran UMKM</h3>
                    
                    <form class="registration-form" action="{{ route('umkm.register.store') }}" method="POST" enctype="multipart/form-data" id="umkmForm">
                        @csrf
                        
                        <!-- Owner Info -->
                        <div class="form-section mb-4">
                            <h5 class="form-section-title"><i class="bi bi-person me-2"></i> Data Pemilik</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_pemilik" class="form-control @error('nama_pemilik') is-invalid @enderror" 
                                           placeholder="Masukkan nama lengkap" value="{{ old('nama_pemilik') }}" required>
                                    @error('nama_pemilik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. KTP <span class="text-danger">*</span></label>
                                    <input type="text" name="no_ktp" class="form-control @error('no_ktp') is-invalid @enderror" 
                                           placeholder="16 digit nomor KTP" value="{{ old('no_ktp') }}" required>
                                    @error('no_ktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. WhatsApp <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           placeholder="email@example.com" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat Domisili <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" name="alamat" rows="2" 
                                              placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Foto KTP <span class="text-danger">*</span></label>
                                    <input type="file" name="foto_ktp" class="form-control @error('foto_ktp') is-invalid @enderror" 
                                           accept="image/jpeg,image/png,image/jpg" required>
                                    <small class="form-text text-muted">Format: JPG, PNG | Maksimal: 2MB</small>
                                    @error('foto_ktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Business Info -->
                        <div class="form-section mb-4">
                            <h5 class="form-section-title"><i class="bi bi-shop me-2"></i> Data Usaha</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Usaha/UMKM <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror" 
                                           placeholder="Nama usaha Anda" value="{{ old('nama_toko') }}" required>
                                    @error('nama_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Usaha <span class="text-danger">*</span></label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                        <option value="">Pilih jenis usaha</option>
                                        <option value="Makanan Olahan" {{ old('kategori') == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                        <option value="Hasil Laut" {{ old('kategori') == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                        <option value="Bumbu Dapur" {{ old('kategori') == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                        <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                        <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                        <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    
                                    <label class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                                     <select name="desa" class="form-select @error('desa') is-invalid @enderror" required>
                                        <option value="">-- Pilih Desa --</option>
                                        @forelse($desas as $desa)
                                            <option value="{{ $desa->nama_desa }}" {{ old('desa') == $desa->nama_desa ? 'selected' : '' }}>
                                                {{ $desa->nama_desa }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Tidak ada desa tersedia</option>
                                        @endforelse
                                    </select>
                                    {{-- <select name="desa" class="form-select @error('desa') is-invalid @enderror" required>
                                        <option value="">Pilih desa</option>
                                        <option value="Teluknaga" {{ old('desa') == 'Teluknaga' ? 'selected' : '' }}>Desa Teluknaga</option>
                                        <option value="Tanjung Pasir" {{ old('desa') == 'Tanjung Pasir' ? 'selected' : '' }}>Desa Tanjung Pasir</option>
                                        <option value="Muara" {{ old('desa') == 'Muara' ? 'selected' : '' }}>Desa Muara</option>
                                        <option value="Lemo" {{ old('desa') == 'Lemo' ? 'selected' : '' }}>Desa Lemo</option>
                                        <option value="Pangkalan" {{ old('desa') == 'Pangkalan' ? 'selected' : '' }}>Desa Pangkalan</option>
                                    </select> --}}
                                    @error('desa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Lama Usaha</label>
                                    <select name="lama_usaha" class="form-select">
                                        <option value="">Pilih lama usaha</option>
                                        <option value="<1" {{ old('lama_usaha') == '<1' ? 'selected' : '' }}>Kurang dari 1 tahun</option>
                                        <option value="1-3" {{ old('lama_usaha') == '1-3' ? 'selected' : '' }}>1-3 tahun</option>
                                        <option value="3-5" {{ old('lama_usaha') == '3-5' ? 'selected' : '' }}>3-5 tahun</option>
                                        <option value=">5" {{ old('lama_usaha') == '>5' ? 'selected' : '' }}>Lebih dari 5 tahun</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Deskripsi Usaha</label>
                                    <textarea class="form-control" name="deskripsi" rows="3" placeholder="Ceritakan tentang usaha Anda...">{{ old('deskripsi') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Produk Utama <span class="text-danger">*</span></label>
                                    <input type="text" name="produk_utama" class="form-control @error('produk_utama') is-invalid @enderror" 
                                           placeholder="Contoh: Kerupuk Udang, Terasi, dll" value="{{ old('produk_utama') }}" required>
                                    @error('produk_utama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Foto Tempat Usaha <span class="text-danger">*</span></label>
                                    <input type="file" name="foto_tempat" class="form-control @error('foto_tempat') is-invalid @enderror" 
                                           accept="image/jpeg,image/png,image/jpg" required>
                                    <small class="form-text text-muted">Format: JPG, PNG | Maksimal: 2MB</small>
                                    @error('foto_tempat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Omzet Bulanan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: #f8f9fa; border: 1px solid var(--border-color); border-radius: 10px 0 0 10px;">Rp</span>
                                        <input type="text" id="omzet_display" class="form-control @error('omzet_bulanan') is-invalid @enderror" 
                                               placeholder="Contoh: 5.000.000" value="{{ old('omzet_bulanan') ? number_format(old('omzet_bulanan'), 0, ',', '.') : '' }}" required
                                               style="border-radius: 0 10px 10px 0;">
                                        <input type="hidden" name="omzet_bulanan" id="omzet_actual">
                                    </div>
                                    @error('omzet_bulanan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Lokasi dengan Google Maps -->
                        <div class="form-section mb-4">
                            <h5 class="form-section-title"><i class="bi bi-geo-alt me-2"></i> Lokasi Usaha (Pilih di Peta)</h5>
                            
                            <!-- Google Maps Container -->
                            <div class="mb-3">
                                <div id="map" style="height: 400px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"></div>
                                <small class="text-muted d-block mt-2">💡 Klik pada peta untuk memilih lokasi usaha Anda</small>
                            </div>

                            <!-- Koordinat Display -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Latitude <span class="text-danger">*</span></label>
                                    <input type="number" id="latitude" name="latitude" step="0.000001" 
                                           class="form-control @error('latitude') is-invalid @enderror" 
                                           placeholder="-6.123456" value="{{ old('latitude', '-6.1753') }}" required readonly>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Longitude <span class="text-danger">*</span></label>
                                    <input type="number" id="longitude" name="longitude" step="0.000001" 
                                           class="form-control @error('longitude') is-invalid @enderror" 
                                           placeholder="106.123456" value="{{ old('longitude', '106.9749') }}" required readonly>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Rekening & E-Wallet Section -->
                        <div class="form-section mb-4">
                            <h5 class="form-section-title"><i class="bi bi-wallet2 me-2"></i> Rekening / E-Wallet (Opsional)</h5>
                            <p class="text-muted small mb-3">Informasi untuk kemudahan transaksi pembayaran</p>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tipe Rekening / E-Wallet</label>
                                    <select name="tipe_rekening" class="form-select @error('tipe_rekening') is-invalid @enderror">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="BCA" {{ old('tipe_rekening') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                        <option value="Mandiri" {{ old('tipe_rekening') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                        <option value="BNI" {{ old('tipe_rekening') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                        <option value="CIMB" {{ old('tipe_rekening') == 'CIMB' ? 'selected' : '' }}>CIMB Niaga</option>
                                        <option value="Danamon" {{ old('tipe_rekening') == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                        <option value="GCash" {{ old('tipe_rekening') == 'GCash' ? 'selected' : '' }}>GCash (PH)</option>
                                        <option value="Dana" {{ old('tipe_rekening') == 'Dana' ? 'selected' : '' }}>Dana</option>
                                        <option value="OVO" {{ old('tipe_rekening') == 'OVO' ? 'selected' : '' }}>OVO</option>
                                        <option value="GOPAY" {{ old('tipe_rekening') == 'GOPAY' ? 'selected' : '' }}>GoPay</option>
                                    </select>
                                    @error('tipe_rekening')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nomor Rekening / No E-Wallet</label>
                                    <input type="text" name="no_rekening" class="form-control @error('no_rekening') is-invalid @enderror" 
                                           placeholder="Contoh: 1234567890" value="{{ old('no_rekening') }}">
                                    @error('no_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Nama Pemilik Rekening</label>
                                    <input type="text" name="nama_pemilik_rekening" class="form-control @error('nama_pemilik_rekening') is-invalid @enderror" 
                                           placeholder="Nama sesuai rekening/e-wallet" value="{{ old('nama_pemilik_rekening') }}">
                                    @error('nama_pemilik_rekening')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Agreement -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="agreement" name="agreement" required>
                            <label class="form-check-label" for="agreement">
                                Saya menyetujui <a href="#" class="text-primary">syarat dan ketentuan</a> yang berlaku
                            </label>
                        </div>
                        
                        <!-- Submit -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill">
                                Daftar Sekarang <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.page-header {
    padding: 140px 0 60px;
    background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
}

.page-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 800;
    margin-bottom: 16px;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
}

.section-benefits {
    padding: 60px 0;
}

.benefit-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: var(--transition-base);
    height: 100%;
}

.benefit-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.benefit-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-light);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--primary-color);
    margin: 0 auto 16px;
}

.benefit-card h5 {
    font-size: 1rem;
    margin-bottom: 8px;
}

.benefit-card p {
    color: var(--text-muted);
    font-size: 14px;
    margin: 0;
}

.section-registration {
    padding: 60px 0 100px;
}

.registration-form-wrapper {
    background: white;
    border-radius: 24px;
    padding: 48px;
    box-shadow: var(--shadow-lg);
}

.form-title {
    font-size: 1.5rem;
    font-weight: 700;
}

.form-section-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--primary-color);
    padding-bottom: 12px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 20px;
}

.form-control, .form-select {
    border-radius: 10px;
    padding: 12px 16px;
    border: 1px solid var(--border-color);
}

.form-control:focus, .form-select:focus {
    border-color: #001f5c;
    box-shadow: 0 0 0 0.2rem rgba(0, 31, 92, 0.15);
}

.form-control:read-only {
    background-color: #f8f9fa;
    cursor: default;
}

.form-label {
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 6px;
}

.daftar-umkm-btn {
    background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
    border: none;
    color: white;
    box-shadow: 0 4px 15px rgba(0, 31, 92, 0.2);
}

.daftar-umkm-btn:hover {
    background: linear-gradient(135deg, #000f3d 0%, #001f5c 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 31, 92, 0.3);
}

.step-indicator .step-active {
    background: #001f5c;
    color: white;
}

.progress-bar {
    background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
}

a {
    color: #001f5c;
}

a:hover {
    color: #000f3d;
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
    .registration-form-wrapper {
        padding: 30px 20px;
    }
    
    #map {
        height: 300px !important;
    }
    
    .leaflet-control-search input {
        width: 180px;
    }
}
</style>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-control-geocoder/2.4.0/Control.Geocoder.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-control-geocoder/2.4.0/Control.Geocoder.min.js"></script>

@endpush

@push('scripts')
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

// Form submission - ensure coordinates are set
document.getElementById('umkmForm').addEventListener('submit', function(e) {
    const latitude = document.getElementById('latitude').value;
    const longitude = document.getElementById('longitude').value;
    
    if (!latitude || !longitude) {
        e.preventDefault();
        alert('⚠️ Silakan pilih lokasi di peta terlebih dahulu');
    }
});

// Format Rupiah untuk Omzet Bulanan
function formatRupiah(value) {
    // Hapus karakter non-digit
    value = value.replace(/\D/g, '');
    
    // Format dengan separator
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    return value;
}

const omzetDisplay = document.getElementById('omzet_display');
const omzetActual = document.getElementById('omzet_actual');

if (omzetDisplay) {
    omzetDisplay.addEventListener('input', function() {
        const formatted = formatRupiah(this.value);
        this.value = formatted;
        
        // Simpan nilai asli (tanpa format) ke input hidden
        omzetActual.value = this.value.replace(/\D/g, '');
    });

    // Set nilai awal jika ada
    if (omzetDisplay.value) {
        omzetActual.value = omzetDisplay.value.replace(/\D/g, '');
    }
}
</script>
@endpush
