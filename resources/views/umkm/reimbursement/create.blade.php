@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 fw-bold">Ajukan Reimbursement</h1>
                <a href="{{ route('reimbursement.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('reimbursement.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Judul Reimbursement <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" 
                                placeholder="Contoh: Biaya Promosi di Facebook" value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" 
                                placeholder="Jelaskan detail reimbursement..." required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah" step="0.01" class="form-control @error('jumlah') is-invalid @enderror" 
                                        placeholder="0" value="{{ old('jumlah') }}" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="marketing" {{ old('kategori') == 'marketing' ? 'selected' : '' }}>Marketing/Promosi</option>
                                        <option value="operasional" {{ old('kategori') == 'operasional' ? 'selected' : '' }}>Biaya Operasional</option>
                                        <option value="peralatan" {{ old('kategori') == 'peralatan' ? 'selected' : '' }}>Peralatan</option>
                                        <option value="bahan_baku" {{ old('kategori') == 'bahan_baku' ? 'selected' : '' }}>Bahan Baku</option>
                                        <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Bukti Gambar <span class="text-danger">*</span></label>
                            <input type="file" name="bukti_gambar" class="form-control @error('bukti_gambar') is-invalid @enderror" 
                                accept="image/jpeg,image/jpg,image/png" required>
                            <small class="form-text text-muted">Format: JPG, PNG | Maksimal: 2MB</small>
                            @error('bukti_gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Ajukan Reimbursement
                            </button>
                            <a href="{{ route('reimbursement.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection