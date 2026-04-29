@extends('layouts.dashboard-umkm')

@section('title', 'Tambah Produk - ' . Auth::guard('umkm')->user()->nama_toko)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('umkm.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('umkm.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">Tambah Produk</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Tambah Produk</h1>
                    <p class="text-muted mb-0">Tambahkan produk baru untuk dijual</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Form Tambah Produk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('umkm.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Nama Produk *</label>
                            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" 
                                   value="{{ old('nama_produk') }}" placeholder="Masukkan nama produk" required>
                            @error('nama_produk') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kategori *</label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Hasil Laut" {{ old('kategori') == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                        <option value="Makanan Olahan" {{ old('kategori') == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                        <option value="Bumbu Dapur" {{ old('kategori') == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                        <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                        <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                    </select>
                                    @error('kategori') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan *</label>
                                    <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram (gr)</option>
                                        <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                        <option value="pack" {{ old('satuan') == 'pack' ? 'selected' : '' }}>Pack</option>
                                        <option value="porsi" {{ old('satuan') == 'porsi' ? 'selected' : '' }}>Porsi</option>
                                    </select>
                                    @error('satuan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Produk *</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      placeholder="Deskripsi produk Anda" rows="4" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Harga (Rp) *</label>
                                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                                           value="{{ old('harga') }}" min="0" step="100" placeholder="Masukkan harga" required>
                                    @error('harga') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Stok *</label>
                                    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                           value="{{ old('stok') }}" min="0" placeholder="Masukkan stok" required>
                                    @error('stok') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

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

                        <div class="mb-4">
                            <label class="form-label">Foto Produk *</label>
                            <div class="border-2 border-dashed rounded p-4 text-center" style="cursor: pointer; border: 2px dashed #dee2e6;" onclick="document.getElementById('image').click()">
                                <input type="file" name="image" id="image" accept="image/*" style="display: none;" onchange="previewImage()">
                                <div id="upload-content">
                                    <i class="bi bi-cloud-upload" style="font-size: 2rem; color: #667eea;"></i>
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

                        <div class="d-flex gap-2">
                            <a href="{{ route('umkm.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check"></i> Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
    const uploadZone = document.querySelector('.border-dashed');
    if (uploadZone) {
        uploadZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadZone.style.background = '#f0f1ff';
            uploadZone.style.borderColor = '#667eea';
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
                const dt = new DataTransfer();
                dt.items.add(files[0]);
                document.getElementById('image').files = dt.files;
                previewImage();
            }
        });
    }
</script>
@endsection
