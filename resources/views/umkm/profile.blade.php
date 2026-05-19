@extends('layouts.dashboard-umkm')

@section('title', 'Profil UMKM')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Profil & Pengaturan Toko</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Toko</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('umkm.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Toko</label>
                            <input type="text" name="nama_toko" class="form-control" value="{{ old('nama_toko', $umkm->nama_toko) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Pemilik</label>
                            <input type="text" name="pemilik" class="form-control" value="{{ old('pemilik', $umkm->pemilik) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon/WhatsApp</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $umkm->phone) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $umkm->alamat) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Toko</label>
                            <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $umkm->deskripsi) }}</textarea>
                        </div>

                        <hr class="my-4">
                        <h5 class="mb-3 font-weight-bold text-primary">Pengaturan Pembayaran Event</h5>
                        <p class="text-muted small">QRIS ini akan digunakan sebagai opsi pembayaran khusus saat UMKM Anda mengikuti Event Spesial di platform.</p>

                        <div class="mb-3">
                            <label class="form-label">Foto / Gambar QRIS</label>
                            <input type="file" name="foto_qris" class="form-control" accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                            @error('foto_qris')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            
                            @if($umkm->foto_qris)
                                <div class="mt-3">
                                    <p class="mb-1 text-success"><i class="bi bi-check-circle-fill"></i> QRIS saat ini:</p>
                                    <img src="{{ asset('storage/' . $umkm->foto_qris) }}" alt="QRIS {{ $umkm->nama_toko }}" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            @endif
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
