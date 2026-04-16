@extends('layouts.dashboard')

@section('title', 'Edit Produk')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">Edit {{ $product->nama_produk }}</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Edit Produk</h1>
            <p class="page-subtitle">
                Edit informasi produk {{ $product->nama_produk }}
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Produk</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">UMKM <span class="text-danger">*</span></label>
                        <select name="umkm_id" class="form-select @error('umkm_id') is-invalid @enderror" required>
                            <option value="">Pilih UMKM</option>
                            @foreach($umkms as $umkm)
                                <option value="{{ $umkm->id }}" {{ (old('umkm_id', $product->umkm_id) == $umkm->id) ? 'selected' : '' }}>
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
                                   value="{{ old('nama_produk', $product->nama_produk) }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Hasil Laut" {{ old('kategori', $product->kategori) == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                <option value="Makanan Olahan" {{ old('kategori', $product->kategori) == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                <option value="Bumbu Dapur" {{ old('kategori', $product->kategori) == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                <option value="Kerajinan" {{ old('kategori', $product->kategori) == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                <option value="Kuliner" {{ old('kategori', $product->kategori) == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" 
                                  required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" 
                                   value="{{ old('harga', $product->harga) }}" min="0" step="100" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" 
                                   value="{{ old('stok', $product->stok) }}" min="0" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                <option value="">Pilih Satuan</option>
                                <option value="kg" {{ old('satuan', $product->satuan) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                <option value="gram" {{ old('satuan', $product->satuan) == 'gram' ? 'selected' : '' }}>Gram (gr)</option>
                                <option value="pcs" {{ old('satuan', $product->satuan) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                <option value="pack" {{ old('satuan', $product->satuan) == 'pack' ? 'selected' : '' }}>Pack</option>
                                <option value="porsi" {{ old('satuan', $product->satuan) == 'porsi' ? 'selected' : '' }}>Porsi</option>
                                <option value="liter" {{ old('satuan', $product->satuan) == 'liter' ? 'selected' : '' }}>Liter</option>
                                <option value="meter" {{ old('satuan', $product->satuan) == 'meter' ? 'selected' : '' }}>Meter</option>
                            </select>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check me-2"></i>Update Produk
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
                <h6 class="card-title mb-0">Info Produk</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Ditambahkan</small>
                    <div>{{ $product->created_at->format('d M Y, H:i') }}</div>
                </div>
                
                @if($product->updated_at != $product->created_at)
                <div class="mb-3">
                    <small class="text-muted">Terakhir diupdate</small>
                    <div>{{ $product->updated_at->format('d M Y, H:i') }}</div>
                </div>
                @endif

                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Perhatian</h6>
                    <p class="mb-0 small">Perubahan pada produk akan langsung terlihat oleh customer.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
