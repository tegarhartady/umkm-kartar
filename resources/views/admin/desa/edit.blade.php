@extends('layouts.dashboard')

@section('title', 'Edit Desa')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.desa.index') }}">Kelola Desa</a></li>
            <li class="breadcrumb-item active">{{ $desa->nama_desa }}</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Edit Data Desa</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.desa.update', $desa->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_desa" class="form-label">Nama Desa *</label>
                            <input type="text" name="nama_desa" class="form-control @error('nama_desa') is-invalid @enderror" 
                                   id="nama_desa" value="{{ old('nama_desa', $desa->nama_desa) }}" required>
                            @error('nama_desa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kecamatan" class="form-label">Kecamatan *</label>
                                    <input type="text" name="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" 
                                           id="kecamatan" value="{{ old('kecamatan', $desa->kecamatan) }}" required>
                                    @error('kecamatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="kabupaten" class="form-label">Kabupaten *</label>
                                    <input type="text" name="kabupaten" class="form-control @error('kabupaten') is-invalid @enderror" 
                                           id="kabupaten" value="{{ old('kabupaten', $desa->kabupaten) }}" required>
                                    @error('kabupaten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" rows="4">{{ old('deskripsi', $desa->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="jumlah_umkm" class="form-label">Jumlah UMKM *</label>
                            <input type="number" name="jumlah_umkm" class="form-control @error('jumlah_umkm') is-invalid @enderror" 
                                   id="jumlah_umkm" value="{{ old('jumlah_umkm', $desa->jumlah_umkm ?? 0) }}" min="0" required>
                            @error('jumlah_umkm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-2"></i>Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.desa.index') }}" class="btn btn-outline-secondary">
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
