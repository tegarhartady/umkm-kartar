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

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</h5>
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

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- UMKM -->
                        <div class="mb-3">
                            <label class="form-label fw-600">UMKM *</label>
                            <select name="umkm_id" class="form-select @error('umkm_id') is-invalid @enderror" required>
                                <option value="">-- Pilih UMKM --</option>
                                @foreach($umkms as $umkm)
                                    <option value="{{ $umkm->id }}" {{ old('umkm_id') == $umkm->id ? 'selected' : '' }}>
                                        {{ $umkm->nama_toko }}
                                    </option>
                                @endforeach
                            </select>
                            @error('umkm_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Nama Produk -->
                        <div class="mb-3">
                            <label class="form-label fw-600">Nama Produk *</label>
                            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" 
                                   value="{{ old('nama_produk') }}" placeholder="Nama produk" required>
                            @error('nama_produk') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Kategori & Satuan -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-600">Kategori *</label>
                                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->nama_kategori }}" {{ old('kategori') == $category->nama_kategori ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">Satuan *</label>
                                <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                    <option value="">Pilih Satuan</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->nama_satuan }}" {{ old('satuan') == $unit->nama_satuan ? 'selected' : '' }}>{{ $unit->nama_satuan }}</option>
                                    @endforeach
                                </select>
                                @error('satuan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Metode Pemesanan -->
                        <div class="mb-3">
                            <label class="form-label fw-600">Metode Pemesanan *</label>
                            <select name="metode_pemesanan" class="form-select @error('metode_pemesanan') is-invalid @enderror" required>
                                <option value="">-- Pilih Metode Pemesanan --</option>
                                <option value="siap_jadi" {{ old('metode_pemesanan') == 'siap_jadi' ? 'selected' : '' }}>Siap Jadi</option>
                                <option value="po" {{ old('metode_pemesanan') == 'po' ? 'selected' : '' }}>Pre-Order (PO)</option>
                                <option value="keduanya" {{ old('metode_pemesanan') == 'keduanya' ? 'selected' : '' }}>Siap Jadi & PO</option>
                            </select>
                            @error('metode_pemesanan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label class="form-label fw-600">Deskripsi Produk *</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      rows="4" placeholder="Deskripsi produk" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Foto Produk -->
                        <div class="mb-3">
                            <label class="form-label fw-600">Foto Produk *</label>
                            <div class="border-2 border-dashed rounded p-4 text-center" style="cursor: pointer; border: 2px dashed #dee2e6;" onclick="document.getElementById('image').click()">
                                <input type="file" name="image" id="image" accept="image/*" style="display: none;" onchange="previewImage()">
                                <div id="upload-content">
                                    <i class="bi bi-cloud-upload" style="font-size: 2rem; color: #001f5c;"></i>
                                    <h5 class="mt-2">Klik atau drag foto di sini</h5>
                                    <p class="text-muted mb-0">JPG, PNG, GIF hingga 2MB</p>
                                </div>
                                <div id="image-preview-container" style="display: none; text-align: center;">
                                    <img id="image-preview" src="" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                    <p class="mt-2 mb-0 text-success"><i class="bi bi-check-circle"></i> Foto siap diupload</p>
                                </div>
                            </div>
                            @error('image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <!-- Harga & Stok -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-600">Harga (Rp) *</label>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                                       value="{{ old('harga') }}" min="0" step="100" placeholder="Harga" required>
                                @error('harga') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-600">Stok *</label>
                                <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                       value="{{ old('stok') }}" min="0" placeholder="Stok" required>
                                @error('stok') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg grow">
                                <i class="bi bi-check me-2"></i>Simpan Produk
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-lg grow">
                                <i class="bi bi-x me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
</style>

@endsection

@push('scripts')
<script>
function previewImage() {
    const file = document.getElementById('image').files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('image-preview');
        const uploadContent = document.getElementById('upload-content');
        const previewContainer = document.getElementById('image-preview-container');
        
        preview.src = e.target.result;
        uploadContent.style.display = 'none';
        previewContainer.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

// Drag and drop
const uploadZone = document.querySelector('[style*="border-dashed"]');
if (uploadZone) {
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadZone.style.background = '#f0f1ff';
        uploadZone.style.borderColor = '#001f5c';
    });
    
    uploadZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadZone.style.background = 'transparent';
        uploadZone.style.borderColor = '#dee2e6';
    });
    
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        uploadZone.style.background = 'transparent';
        uploadZone.style.borderColor = '#dee2e6';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('image').files = files;
            previewImage();
        }
    });
}
</script>
@endpush
