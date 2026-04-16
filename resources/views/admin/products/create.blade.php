@extends('layouts.dashboard')

@section('title', 'Tambah Produk')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">Tambah Produk</li>
        </ol>
    </nav>
@endsection

@section('styles')
<style>
.product-form {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
    min-height: 100vh;
    margin: -2rem -1.5rem;
    padding: 2rem 1.5rem;
}
.product-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: none;
    border-radius: 25px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    max-width: 900px;
    margin: 0 auto;
}
.product-header {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    color: white;
    padding: 2.5rem;
    text-align: center;
    border-radius: 25px 25px 0 0;
}
.product-body {
    padding: 3rem;
}
.form-floating {
    margin-bottom: 1.5rem;
    position: relative;
}
.form-floating > .form-control,
.form-floating > .form-select {
    border-radius: 15px;
    border: 3px solid #f1f3f4;
    height: 65px;
    transition: all 0.3s ease;
    font-size: 1rem;
    background: #fafafa;
}
.form-floating > textarea.form-control {
    height: 130px;
    padding-top: 1.625rem;
}
.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: #ff9a9e;
    box-shadow: 0 0 0 0.2rem rgba(255, 154, 158, 0.25);
    background: white;
}
.form-floating > label {
    color: #666;
    font-weight: 600;
    font-size: 0.9rem;
}
.image-upload-zone {
    border: 3px dashed #ff9a9e;
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background: #fafafa;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}
.image-upload-zone:hover {
    border-color: #ff6b6b;
    background: #fff5f5;
    transform: translateY(-2px);
}
.image-upload-zone.dragover {
    border-color: #ff6b6b;
    background: #fff0f0;
    transform: scale(1.02);
}
.image-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 15px;
    margin-top: 1rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}
.upload-icon {
    font-size: 3rem;
    color: #ff9a9e;
    margin-bottom: 1rem;
}
.btn-product {
    border-radius: 15px;
    padding: 15px 35px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    border: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.btn-primary.btn-product {
    background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
    box-shadow: 0 8px 25px rgba(255, 154, 158, 0.3);
}
.btn-primary.btn-product:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(255, 154, 158, 0.4);
}
.btn-secondary.btn-product {
    background: #6c757d;
    color: white;
}
.product-section {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    border-left: 5px solid #ff9a9e;
}
.section-title {
    color: #333;
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 10px;
}
.price-input {
    position: relative;
}
.price-input::before {
    content: "Rp";
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #ff9a9e;
    font-weight: bold;
    z-index: 10;
}
.price-input .form-control {
    padding-left: 45px;
}
</style>
@endsection

@section('content')

<div class="product-form">
    <div class="container-fluid">
        <div class="product-card">
            <div class="product-header">
                <h2 class="mb-2"><i class="bi bi-plus-circle me-3"></i>Tambah Produk Baru</h2>
                <p class="mb-0 opacity-75">Buat produk menarik untuk UMKM Anda</p>
            </div>
            
            <div class="product-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Basic Info Section -->
                    <div class="product-section">
                        <h3 class="section-title">
                            <i class="bi bi-info-circle text-primary"></i>
                            Informasi Dasar
                        </h3>
                        
                        <div class="form-floating">
                            <select name="umkm_id" class="form-select @error('umkm_id') is-invalid @enderror" id="umkm_id" required>
                                <option value="">Pilih UMKM</option>
                                @foreach($umkms as $umkm)
                                    <option value="{{ $umkm->id }}" {{ old('umkm_id') == $umkm->id ? 'selected' : '' }}>
                                        {{ $umkm->nama_toko }} ({{ $umkm->pemilik }})
                                    </option>
                                @endforeach
                            </select>
                            <label for="umkm_id">UMKM *</label>
                            @error('umkm_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-floating">
                                    <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" 
                                           id="nama_produk" value="{{ old('nama_produk') }}" placeholder="Nama Produk" required>
                                    <label for="nama_produk">Nama Produk *</label>
                                    @error('nama_produk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" id="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Hasil Laut" {{ old('kategori') == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                        <option value="Makanan Olahan" {{ old('kategori') == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                        <option value="Bumbu Dapur" {{ old('kategori') == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                        <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                        <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                    </select>
                                    <label for="kategori">Kategori *</label>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" placeholder="Deskripsi Produk" required>{{ old('deskripsi') }}</textarea>
                            <label for="deskripsi">Deskripsi Produk *</label>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="product-section">
                        <h3 class="section-title">
                            <i class="bi bi-image text-primary"></i>
                            Foto Produk
                        </h3>
                        
                        <div class="image-upload-zone" onclick="document.getElementById('image').click()">
                            <input type="file" name="image" id="image" accept="image/*" style="display: none;" onchange="previewImage()">
                            <div id="upload-content">
                                <i class="bi bi-cloud-upload upload-icon"></i>
                                <h5>Klik atau drag foto produk di sini</h5>
                                <p class="text-muted mb-0">JPG, PNG, GIF hingga 2MB</p>
                            </div>
                            <div id="image-preview-container" style="display: none;">
                                <img id="image-preview" class="image-preview" src="" alt="Preview">
                                <p class="mt-2 mb-0 text-success"><i class="bi bi-check-circle"></i> Foto siap diupload</p>
                            </div>
                            @error('image')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Pricing & Stock Section -->
                    <div class="product-section">
                        <h3 class="section-title">
                            <i class="bi bi-currency-dollar text-primary"></i>
                            Harga & Stok
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-floating price-input">
                                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                                           id="harga" value="{{ old('harga') }}" min="0" step="100" placeholder="Harga" required>
                                    <label for="harga">Harga *</label>
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                           id="stok" value="{{ old('stok') }}" min="0" placeholder="Stok" required>
                                    <label for="stok">Stok *</label>
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" id="satuan" required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram (gr)</option>
                                        <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                        <option value="pack" {{ old('satuan') == 'pack' ? 'selected' : '' }}>Pack</option>
                                        <option value="porsi" {{ old('satuan') == 'porsi' ? 'selected' : '' }}>Porsi</option>
                                        <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>Liter</option>
                                        <option value="meter" {{ old('satuan') == 'meter' ? 'selected' : '' }}>Meter</option>
                                    </select>
                                    <label for="satuan">Satuan *</label>
                                    @error('satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-product me-3">
                            <i class="bi bi-check me-2"></i>Simpan Produk
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-product">
                            <i class="bi bi-x me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function previewImage() {
    const file = document.getElementById('image').files[0];
    const preview = document.getElementById('image-preview');
    const uploadContent = document.getElementById('upload-content');
    const previewContainer = document.getElementById('image-preview-container');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            uploadContent.style.display = 'none';
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

// Drag and drop functionality
const uploadZone = document.querySelector('.image-upload-zone');

uploadZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadZone.classList.add('dragover');
});

uploadZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
});

uploadZone.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadZone.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('image').files = files;
        previewImage();
    }
});
</script>
@endsection
                    
                    <div class="mb-3">
                        <label class="form-label">UMKM <span class="text-danger">*</span></label>
                        <select name="umkm_id" class="form-select @error('umkm_id') is-invalid @enderror" required>
                            <option value="">Pilih UMKM</option>
                            @foreach($umkms as $umkm)
                                <option value="{{ $umkm->id }}" {{ old('umkm_id') == $umkm->id ? 'selected' : '' }}>
                                    {{ $umkm->nama_toko }} ({{ $umkm->pemilik }})
                                </option>
                            @endforeach
                        </select>
                        @error('umkm_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" 
                                   value="{{ old('nama_produk') }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Hasil Laut" {{ old('kategori') == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                <option value="Makanan Olahan" {{ old('kategori') == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                <option value="Bumbu Dapur" {{ old('kategori') == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" 
                                  required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                                   value="{{ old('harga') }}" min="0" step="100" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                   value="{{ old('stok') }}" min="0" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                <option value="">Pilih Satuan</option>
                                <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram (gr)</option>
                                <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                <option value="pack" {{ old('satuan') == 'pack' ? 'selected' : '' }}>Pack</option>
                                <option value="porsi" {{ old('satuan') == 'porsi' ? 'selected' : '' }}>Porsi</option>
                                <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>Liter</option>
                                <option value="meter" {{ old('satuan') == 'meter' ? 'selected' : '' }}>Meter</option>
                            </select>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check me-2"></i>Simpan Produk
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Preview</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-2"></i>Tips</h6>
                    <ul class="mb-0 small">
                        <li>Pilih UMKM yang sudah disetujui</li>
                        <li>Gunakan deskripsi yang menarik</li>
                        <li>Pastikan harga dan stok akurat</li>
                        <li>Pilih satuan yang sesuai</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
