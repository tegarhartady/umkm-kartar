@extends('layouts.dashboard')

@section('title', 'Edit Produk - ' . $product->nama_produk)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
        <li class="breadcrumb-item active">Edit Produk</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Edit Produk</h1>
                    <p class="text-muted mb-0">Kelola informasi produk UMKM</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Form Edit Produk (Admin)</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pemilik UMKM *</label>
                                <select name="umkm_id" class="form-select @error('umkm_id') is-invalid @enderror" required>
                                    <option value="">Pilih UMKM</option>
                                    @foreach($umkms as $umkm)
                                        <option value="{{ $umkm->id }}" {{ old('umkm_id', $product->umkm_id) == $umkm->id ? 'selected' : '' }}>
                                            {{ $umkm->nama_toko }} ({{ $umkm->pemilik }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('umkm_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Produk *</label>
                                <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror"
                                    value="{{ old('nama_produk', $product->nama_produk) }}" placeholder="Masukkan nama produk" required>
                                @error('nama_produk') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kategori *</label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->nama_kategori }}" {{ old('kategori', $product->kategori) == $category->nama_kategori ? 'selected' : '' }}>{{ $category->nama_kategori }}</option>
                                @endforeach
                            </select>
                                    @error('kategori') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Satuan *</label>
                                    <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                        <option value="">Pilih Satuan</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->nama_satuan }}" {{ old('satuan', $product->satuan) == $unit->nama_satuan ? 'selected' : '' }}>{{ $unit->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                    @error('satuan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Deskripsi Produk *</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                placeholder="Deskripsi produk" rows="4" required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Harga (Rp) *</label>
                                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                        value="{{ old('harga', $product->harga) }}" min="0" step="100" placeholder="Masukkan harga" required>
                                    @error('harga') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Stok *</label>
                                    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                                        value="{{ old('stok', $product->stok) }}" min="0" placeholder="Masukkan stok" required>
                                    @error('stok') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Metode Pemesanan *</label>
                            <select name="metode_pemesanan" class="form-select @error('metode_pemesanan') is-invalid @enderror" required>
                                <option value="">-- Pilih Metode Pemesanan --</option>
                                <option value="siap_jadi" {{ old('metode_pemesanan', $product->metode_pemesanan) == 'siap_jadi' ? 'selected' : '' }}>Siap Jadi</option>
                                <option value="po" {{ old('metode_pemesanan', $product->metode_pemesanan) == 'po' ? 'selected' : '' }}>Pre-Order (PO)</option>
                                <option value="keduanya" {{ old('metode_pemesanan', $product->metode_pemesanan) == 'keduanya' ? 'selected' : '' }}>Siap Jadi & PO</option>
                            </select>
                            @error('metode_pemesanan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <div class="bg-light p-3 rounded-3 mb-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="fw-bold mb-1">Rating Produk Saat Ini</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="text-warning me-2">
                                            @php $rating = $product->reviews_avg_rating ?? 0; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="fw-bold fs-5 me-2">{{ number_format($rating, 1) }}</span>
                                        <span class="text-muted">({{ $product->reviews_count }} Ulasan)</span>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <p class="small text-muted mb-0">Rekomendasi Admin:</p>
                                    @if($rating >= 4.5)
                                        <span class="badge bg-success">Sangat Bagus untuk Best Seller</span>
                                    @elseif($rating >= 4.0)
                                        <span class="badge bg-primary">Bagus untuk Best Seller</span>
                                    @else
                                        <span class="badge bg-secondary">Rating Masih Rendah</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_best_seller" id="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_best_seller">Jadikan Produk Best Seller</label>
                            </div>
                            <small class="text-muted">Produk akan muncul dengan lencana khusus di katalog.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Status Produk</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_aktif" value="aktif" {{ old('status', $product->status) == 'aktif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_aktif">Aktif (Muncul di Katalog)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status_nonaktif" value="nonaktif" {{ old('status', $product->status) == 'nonaktif' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status_nonaktif">Nonaktif (Sembunyikan)</label>
                                </div>
                            </div>
                            @error('status') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto Produk</label>
                            @php
                            $foto = $product->image;
                            if (!$foto && $product->foto_produk) {
                            $foto_array = json_decode($product->foto_produk);
                            $foto = (is_array($foto_array) && count($foto_array) > 0) ? $foto_array[0] : $product->foto_produk;
                            }

                            if (!$foto) {
                            $foto_url = asset('storage/default.jpg');
                            } else {
                            $foto_url = (strpos($foto, 'products/') === 0) ? asset('storage/' . $foto) : asset('storage/products/' . $foto);
                            }
                            @endphp
                            <div class="mb-3">
                                <p class="small fw-bold mb-1">Foto Saat Ini:</p>
                                <img src="{{ $foto_url }}" alt="Produk" style="max-width: 150px; max-height: 150px; border-radius: 8px;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($product->nama_produk) }}&background=random&color=fff&size=150'">
                            </div>

                            <div class="border border-2 border-dashed rounded p-4 text-center bg-light" style="cursor: pointer;" onclick="document.getElementById('image').click()">
                                <input type="file" name="image" id="image" accept="image/*" style="display: none;" onchange="previewImage()">
                                <div id="upload-content">
                                    <i class="bi bi-cloud-upload" style="font-size: 2rem; color: #001f5c;"></i>
                                    <h5 class="mt-2 text-primary">Klik atau drag foto di sini untuk mengubah</h5>
                                    <p class="text-muted mb-0">JPG, PNG, GIF hingga 2MB</p>
                                </div>
                                <div id="image-preview-container" style="display: none; text-align: center;">
                                    <img id="image-preview" src="" alt="Preview" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                    <p class="mt-2 mb-0 text-success"><i class="bi bi-check-circle"></i> Foto baru siap diupload</p>
                                </div>
                            </div>
                            @error('image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
        transition: all 0.3s ease;
    }
    .border-dashed:hover {
        background-color: #f0f7ff !important;
        border-color: #001f5c !important;
    }
</style>

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
            uploadZone.style.background = '#eef2ff';
            uploadZone.style.borderColor = '#001f5c';
        });

        uploadZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadZone.style.background = '#f8f9fa';
            uploadZone.style.borderColor = '#dee2e6';
        });

        uploadZone.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            uploadZone.style.background = '#f8f9fa';
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
