@extends('layouts.app')

@section('title', 'Edit Produk - ' . $product->nama_produk)

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-pencil me-2"></i>Edit Produk
            </h1>
            <p class="text-muted small">
                <a href="{{ route('umkm.dashboard') }}">Dashboard</a> /
                <a href="{{ route('umkm.products.index') }}">Produk</a> / Edit
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>Form Edit Produk
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('umkm.products.update', $product) }}" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <!-- Nama Produk -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror"
                                   value="{{ old('nama_produk', $product->nama_produk) }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                      rows="4" required>{{ old('deskripsi', $product->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga & Satuan Row -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                       min="0" step="1"
                                       value="{{ old('harga', $product->harga) }}" required>
                                @error('harga')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="pcs" {{ old('satuan', $product->satuan) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="kg" {{ old('satuan', $product->satuan) == 'kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="liter" {{ old('satuan', $product->satuan) == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="box" {{ old('satuan', $product->satuan) == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="pack" {{ old('satuan', $product->satuan) == 'pack' ? 'selected' : '' }}>Pack</option>
                                </select>
                                @error('satuan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Stok & Kategori Row -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                                <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
                                       min="0"
                                       value="{{ old('stok', $product->stok) }}" required>
                                @error('stok')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Kategori</label>
                                <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                                       value="{{ old('kategori', $product->kategori) }}">
                                @error('kategori')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Foto -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto Produk</label>

                            @if ($product->foto)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $product->foto) }}" alt="{{ $product->nama_produk }}"
                                         class="img-thumbnail" style="max-width: 200px;">
                                    <p class="small text-muted mt-2 mb-0">Foto saat ini</p>
                                </div>
                            @endif

                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                                   accept="image/*">
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG, GIF | Ukuran max: 2MB
                            </small>
                            @error('foto')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('umkm.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Info Produk
                    </h6>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <strong>Status</strong>
                        <p class="mb-0">
                            @if ($product->status === 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-warning">Pending Review</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <strong>Dibuat</strong>
                        <p class="text-muted mb-0">{{ $product->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <strong>Diubah</strong>
                        <p class="text-muted mb-0">{{ $product->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
