@extends('layouts.dashboard')

@section('title', 'Edit UMKM')

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

                    <form action="{{ route('admin.umkm.update', $umkm->id) }}" method="POST">
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
                                    <option value="Hasil Laut" {{ old('kategori', $umkm->kategori) == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                    <option value="Makanan Olahan" {{ old('kategori', $umkm->kategori) == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                    <option value="Bumbu Dapur" {{ old('kategori', $umkm->kategori) == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                    <option value="Kerajinan" {{ old('kategori', $umkm->kategori) == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                    <option value="Kuliner" {{ old('kategori', $umkm->kategori) == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
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
                            <input type="number" name="omzet_bulanan" class="form-control @error('omzet_bulanan') is-invalid @enderror" 
                                   value="{{ old('omzet_bulanan', $umkm->omzet_bulanan) }}" min="0">
                            @error('omzet_bulanan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                                <i class="bi bi-check me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.umkm.show', $umkm->id) }}" class="btn btn-outline-secondary btn-lg flex-grow-1">
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
