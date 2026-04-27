@extends('layouts.app')

@section('title', 'Login UMKM - Karang Taruna Teluknaga')

@section('content')

<section class="section-login">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-5 col-md-7">
                <div class="login-wrapper" data-aos="fade-up">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <a href="{{ url('/') }}" class="d-inline-flex">
                            <img src="{{ asset('images/smartumkm.svg') }}" alt="Smart UMKM Logo" style="height: 80px; width: auto;">
                        </a>
                    </div>

                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">Smart UMKM</h2>
                        <p class="text-muted">Akselerasi UMKM Binaan CSR PIK2</p>
                    </div>

                    <div class="login-card">
                        <div class="text-center mb-4">
                            <i class="bi bi-shop text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="login-title text-center">Login UMKM</h3>
                        <p class="login-subtitle text-center text-muted mb-4">
                            Masuk untuk mengelola produk Anda
                        </p>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                <strong>Gagal Login!</strong><br>
                                {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('umkm.authenticate') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">📧 Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       placeholder="Email yang terdaftar"
                                       value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">🔐 Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="passwordInput"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Password dari admin" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Password diberikan oleh admin setelah pendaftaran Anda disetujui
                                </small>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                            </button>
                        </form>

                        <hr class="my-3">

                        <div class="text-center">
                            <p class="text-muted small mb-3">Belum terdaftar sebagai UMKM?</p>
                            <a href="{{ url('/daftar-umkm') }}" class="btn btn-outline-primary rounded-pill w-100">
                                <i class="bi bi-shop me-2"></i>Daftar UMKM Sekarang
                            </a>
                        </div>
                    </div>

                    <p class="text-center text-muted small mt-4">
                        <a href="{{ url('/') }}" class="text-muted text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.section-login {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 40px 0;
}

.login-wrapper {
    max-width: 420px;
    margin: 0 auto;
}

.brand-icon {
    width: 45px;
    height: 45px;
    background: var(--secondary-color);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 15px;
}

.login-card {
    background: white;
    border-radius: 24px;
    padding: 40px;
    box-shadow: var(--shadow-xl);
}

.login-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.login-subtitle {
    font-size: 14px;
}

.form-control {
    border-radius: 12px;
    padding: 14px 18px;
    border: 1px solid var(--border-color);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(0, 166, 126, 0.1);
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.input-group .btn {
    border-radius: 0 12px 12px 0;
    border-color: var(--border-color);
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.invalid-feedback {
    font-size: 13px;
    color: #dc3545;
    margin-top: 4px;
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('passwordInput');
    const icon = this.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});
</script>
@endpush