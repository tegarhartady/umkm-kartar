@extends('layouts.dashboard-umkm')

@section('title', 'Edit Produk - ' . Auth::guard('umkm')->user()->nama_toko)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('umkm.umkm.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('umkm.products.index') }}">Produk</a></li>
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
                    <p class="text-muted mb-0">Ubah informasi produk Anda</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Form Edit Produk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('umkm.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
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

                        <div class="mb-3">
                            <label class="form-label">Nama Produk *</label>
                            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" 
                                   value="{{ old('nama_produk', $product->nama_produk) }}" placeholder="Masukkan nama produk" required>
                            @error('nama_produk') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kategori *</label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Hasil Laut" {{ old('kategori', $product->kategori) == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                        <option value="Makanan Olahan" {{ old('kategori', $product->kategori) == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                        <option value="Bumbu Dapur" {{ old('kategori', $product->kategori) == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                        <option value="Kerajinan" {{ old('kategori', $product->kategori) == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                        <option value="Kuliner" {{ old('kategori', $product->kategori) == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                    </select>
                                    @error('kategori') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan *</label>
                                    <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="kg" {{ old('satuan', $product->satuan) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                        <option value="gram" {{ old('satuan', $product->satuan) == 'gram' ? 'selected' : '' }}>Gram (gr)</option>
                                        <option value="pcs" {{ old('satuan', $product->satuan) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                        <option value="pack" {{ old('satuan', $product->satuan) == 'pack' ? 'selected' : '' }}>Pack</option>
                                        <option value="porsi" {{ old('satuan', $product->satuan) == 'porsi' ? 'selected' : '' }}>Porsi</option>
                                    </select>
                                    @error('satuan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Produk *</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      placeholder="Deskripsi produk Anda" rows="4" required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Harga (Rp) *</label>
                                    <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                                           value="{{ old('harga', $product->harga) }}" min="0" step="100" placeholder="Masukkan harga" required>
                                    @error('harga') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Stok *</label>
                                    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                           value="{{ old('stok', $product->stok) }}" min="0" placeholder="Masukkan stok" required>
                                    @error('stok') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Produk</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($product->image) }}" alt="Produk" style="max-width: 150px; max-height: 150px; border-radius: 8px;">
                                    <p class="text-muted small mt-1">Foto saat ini</p>
                                </div>
                            @endif
                            <div class="border border-2 border-dashed rounded p-4 text-center" style="cursor: pointer;" onclick="document.getElementById('image').click()">
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
                                <i class="bi bi-check"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div
