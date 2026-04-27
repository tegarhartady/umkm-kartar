@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5 col-sm-10">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold text-dark">Daftar Pelanggan</h2>
                            <p class="text-muted">Buat akun untuk mulai berbelanja di Smart UMKM</p>
                        </div>

                        <form action="{{ route('register.customer.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control rounded-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control rounded-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control rounded-lg @error('password') is-invalid @enderror" id="password" name="password" required placeholder="minimal 8 karakter">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Password</label>
                                <input type="password" class="form-control rounded-lg" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password">
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary rounded-lg fw-bold py-2 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                    <i class="bi bi-person-plus me-2"></i> Daftar Sekarang
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <p class="text-muted small">Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: #667eea;">Login di sini</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .rounded-lg { border-radius: 12px; }
</style>
@endsection
