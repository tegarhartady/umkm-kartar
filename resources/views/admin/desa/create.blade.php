@extends('layouts.dashboard')

@section('title', 'Tambah Desa')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.desa.index') }}">Kelola Desa</a></li>
            <li class="breadcrumb-item active">Tambah Desa</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Tambah Desa Baru</h1>
            <p class="page-subtitle">
                Tambahkan data desa baru untuk registrasi UMKM
            </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Desa</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.desa.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Desa <span class="text-danger">*</span></label>
                        <input type="text" name="nama_desa" class="form-control @error('nama_desa') is-invalid @enderror" 
                               value="{{ old('nama_desa') }}" required>
                        @error('nama_desa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <input type="text" name="kecamatan" class="form-control @error('kecamatan') is-invalid @enderror" 
                                   value="{{ old('kecamatan') }}" required>
                            @error('kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kabupaten <span class="text-danger">*</span></label>
                            <input type="text" name="kabupaten" class="form-control @error('kabupaten') is-invalid @enderror" 
                                   value="{{ old('kabupaten') }}" required>
                            @error('kabupaten')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" 
                                  placeholder="Deskripsi singkat tentang desa (opsional)">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check me-2"></i>Simpan Desa
                        </button>
                        <a href="{{ route('admin.desa.index') }}" class="btn btn-secondary">
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
                <h6 class="card-title mb-0">Informasi</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="bi bi-info-circle me-2"></i>Tips</h6>
                    <ul class="mb-0 small">
                        <li>Nama desa harus unik</li>
                        <li>Pastikan nama kecamatan dan kabupaten sudah benar</li>
                        <li>Deskripsi dapat membantu UMKM memilih lokasi</li>
                        <li>Data ini akan digunakan untuk registrasi UMKM</li>
                    </ul>
                </div>
                
                <hr>
                
                <div class="small text-muted">
                    <strong>Format yang disarankan:</strong><br>
                    • Nama Desa: Desa Teluknaga<br>
                    • Kecamatan: Teluknaga<br>
                    • Kabupaten: Tangerang
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
