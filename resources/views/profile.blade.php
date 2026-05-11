@extends('layouts.app')

@section('title', 'Profil Saya - Smart UMKM')

@section('content')
<div class="container py-5" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="page-header mb-4">
                <h2 class="fw-bold"><i class="bi bi-person-circle me-2 text-primary"></i>Profil Saya</h2>
                <p class="text-muted">Lengkapi data diri Anda untuk mempermudah proses pemesanan.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4 rounded-3">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                <!-- Sidebar -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-body p-4 text-center bg-light">
                            <div class="avatar-placeholder mb-3 mx-auto shadow-sm">
                                <span class="fs-1 text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                            <p class="text-muted small mb-0">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="list-group list-group-flush border-top">
                            <a href="#personal-info" class="list-group-item list-group-item-action py-3 px-4 border-0 active">
                                <i class="bi bi-person me-3"></i>Data Pribadi
                            </a>
                            <a href="#change-password" class="list-group-item list-group-item-action py-3 px-4 border-0">
                                <i class="bi bi-key me-3"></i>Ganti Password
                            </a>
                            <a href="/orders" class="list-group-item list-group-item-action py-3 px-4 border-0">
                                <i class="bi bi-bag-check me-3"></i>Riwayat Transaksi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-8">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="card border-0 shadow-sm rounded-4 mb-4" id="personal-info">
                            <div class="card-header bg-white py-3 px-4 border-bottom">
                                <h6 class="fw-bold mb-0">Data Pribadi</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" class="form-control rounded-3 bg-light" value="{{ $user->email }}" disabled>
                                        <small class="text-muted x-small">Email tidak dapat diubah</small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Nomor Telepon/WhatsApp</label>
                                        <input type="text" name="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                                        <textarea name="address" class="form-control rounded-3 @error('address') is-invalid @enderror" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan">{{ old('address', $user->address) }}</textarea>
                                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Kota</label>
                                        <input type="text" name="city" class="form-control rounded-3 @error('city') is-invalid @enderror" value="{{ old('city', $user->city) }}" placeholder="Contoh: Tangerang">
                                        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Kode Pos</label>
                                        <input type="text" name="postal_code" class="form-control rounded-3 @error('postal_code') is-invalid @enderror" value="{{ old('postal_code', $user->postal_code) }}" placeholder="Contoh: 15510">
                                        @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4" id="change-password">
                            <div class="card-header bg-white py-3 px-4 border-bottom">
                                <h6 class="fw-bold mb-0">Ganti Password (Opsional)</h6>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Password Baru</label>
                                        <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Isi jika ingin mengganti">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ulangi password baru">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm fw-bold">
                                <i class="bi bi-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-placeholder {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .list-group-item.active {
        background-color: rgba(102, 126, 234, 0.1);
        color: #667eea;
        font-weight: 600;
        border-left: 4px solid #667eea !important;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
    }
    .rounded-4 { border-radius: 16px; }
    .rounded-3 { border-radius: 12px; }
</style>
@endsection
