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
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Edit Data UMKM</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.umkm.update', $umkm->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_toko" class="form-label">Nama Toko *</label>
                                    <input type="text" name="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror" 
                                           id="nama_toko" value="{{ old('nama_toko', $umkm->nama_toko) }}" required>
                                    @error('nama_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pemilik" class="form-label">Nama Pemilik *</label>
                                    <input type="text" name="pemilik" class="form-control @error('pemilik') is-invalid @enderror" 
                                           id="pemilik" value="{{ old('pemilik', $umkm->pemilik) }}" required>
                                    @error('pemilik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" value="{{ old('email', $umkm->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Telepon *</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" value="{{ old('phone', $umkm->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="desa" class="form-label">Desa *</label>
                            <input type="text" name="desa" class="form-control @error('desa') is-invalid @enderror" 
                                   id="desa" value="{{ old('desa', $umkm->desa) }}" required>
                            @error('desa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap *</label>
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" rows="3" required>{{ old('alamat', $umkm->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori Usaha *</label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" 
                                            id="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Hasil Laut" {{ old('kategori', $umkm->kategori) == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                        <option value="Makanan Olahan" {{ old('kategori', $umkm->kategori) == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                        <option value="Bumbu Dapur" {{ old('kategori', $umkm->kategori) == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                        <option value="Kerajinan" {{ old('kategori', $umkm->kategori) == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                        <option value="Kuliner" {{ old('kategori', $umkm->kategori) == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror" 
                                            id="status" required>
                                        <option value="pending" {{ old('status', $umkm->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="disetujui" {{ old('status', $umkm->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="ditolak" {{ old('status', $umkm->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Usaha</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" rows="4">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="omzet_bulanan" class="form-label">Omzet Bulanan (Rp)</label>
                            <input type="number" name="omzet_bulanan" class="form-control @error('omzet_bulanan') is-invalid @enderror" 
                                   id="omzet_bulanan" value="{{ old('omzet_bulanan', $umkm->omzet_bulanan) }}" min="0">
                            @error('omzet_bulanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.umkm.show', $umkm->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
