@extends('layouts.dashboard')

@section('title', 'Edit Desa')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.desa.index') }}">Desa</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-1 fw-bold">Edit Desa</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Form Edit Desa: {{ $desa->nama_desa }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.desa.update', $desa->id) }}" method="POST">
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

                        <!-- Nama Desa -->
                        <div class="mb-3">
                            <label class="form-label">Nama Desa *</label>
                            <input type="text" name="nama_desa" class="form-control @error('nama_desa') is-invalid @enderror" 
                                   value="{{ old('nama_desa', $desa->nama_desa) }}" placeholder="Nama desa" required>
                            @error('nama_desa') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Provinsi -->
                        <div class="mb-3">
                            <label class="form-label">Provinsi</label>
                            <input type="text" class="form-control" value="Jawa Barat" disabled>
                            <small class="text-muted">Provinsi tidak dapat diubah (Jawa Barat)</small>
                        </div>

                        <!-- Kabupaten -->
                        <div class="mb-3">
                            <label class="form-label">Kabupaten/Kota</label>
                            <input type="text" class="form-control" value="Tangerang" disabled>
                            <small class="text-muted">Kabupaten tidak dapat diubah (Tangerang)</small>
                        </div>

                        <!-- Kecamatan -->
                        <div class="mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" class="form-control" value="Teluk Naga" disabled>
                            <small class="text-muted">Kecamatan tidak dapat diubah (Teluk Naga)</small>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      placeholder="Deskripsi desa..." rows="4">{{ old('deskripsi', $desa->deskripsi) }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.desa.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
