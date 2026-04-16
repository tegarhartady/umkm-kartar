@extends('layouts.app')

@section('title', 'Tambah Produk - UMKM')

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">
                <i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru
            </h1>
            <p class="text-muted small">
                <a href="{{ route('umkm.dashboard') }}">Dashboard</a> /
                <a href="{{ route('umkm.products.index') }}">Produk</a> / Tambah
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-box-seam me-2"></i>Form Produk
                    </h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('umkm.products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Nama Produk -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror"
                                   placeholder="Contoh: Keripik Tempe Pedas"
                                   value="{{ old('nama_produk') }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror"
                                      rows="4" placeholder="Jelaskan detail produk Anda..."
                                      required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Harga & Satuan Row -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
                                       placeholder="0" min="0" step="1"
                                       value="{{ old('harga') }}" required>
                                @error('harga')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Satuan <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Satuan --</option>
                                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kg</option>
                                    <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="box" {{ old('satuan') == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="pack" {{ old('satuan') == 'pack' ? 'selected' : '' }}>Pack</option>
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
                                       placeholder="0" min="0"
                                       value="{{ old('stok') }}" required>
                                @error('stok')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-semibold">Kategori</label>
                                <input type="text" name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                                       placeholder="Contoh: Makanan, Kerajinan, dll"
                                       value="{{ old('kategori') }}">
                                @error('kategori')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Foto -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Foto Produk</label>
                            <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror"
                                   accept="image/*">
                            <small class="text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Format: JPG, PNG, GIF | Ukuran max: 2MB
                            </small>
                            @error('foto')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Tambah Produk
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
                        <i class="bi bi-info-circle me-2"></i>Tips
                    </h6>
                </div>
                <div class="card-body small">
                    <div class="mb-3">
                        <strong>📝 Nama Produk</strong>
                        <p class="text-muted mb-0">Buat nama yang menarik dan deskriptif agar mudah ditemukan</p>
                    </div>
                    <div class="mb-3">
                        <strong>💰 Harga</strong>
                        <p class="text-muted mb-0">Masukkan harga jual produk Anda</p>
                    </div>
                    <div class="mb-3">
                        <strong>📦 Stok</strong>
                        <p class="text-muted mb-0">Jumlah stok produk yang tersedia</p>
                    </div>
                    <div class="mb-3">
                        <strong>🖼️ Foto</strong>
                        <p class="text-muted mb-0">Gunakan foto berkualitas tinggi untuk hasil terbaik</p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="bi bi-question-circle me-2"></i>Bantuan
                    </h6>
                </div>
                <div class="card-body small">
                    <p class="text-muted mb-0">
                        Produk yang ditambahkan akan masuk dalam status "Pending" dan perlu disetujui oleh admin sebelum ditampilkan secara publik.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
