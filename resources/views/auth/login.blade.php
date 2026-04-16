@extends('layouts.app')

@section('title', 'Login - Karang Taruna Teluknaga')

@section('content')

<section class="section-login">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-5 col-md-7">
                <div class="login-wrapper" data-aos="fade-up">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <a href="{{ url('/') }}" class="d-inline-flex align-items-center text-decoration-none">
                            <div class="brand-icon me-2">
                                <span>TN</span>
                            </div>
                            <div class="brand-text">
                                <small class="text-muted d-block" style="font-size: 10px; line-height: 1;">KARANG TARUNA</small>
                                <span class="fw-bold text-dark" style="font-size: 16px; line-height: 1.2;">TELUKNAGA<span class="text-primary">.</span></span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="login-card">
                        <h3 class="login-title text-center">Selamat Datang</h3>
                        <p class="login-subtitle text-center text-muted mb-4">Masuk ke akun Anda untuk melanjutkan</p>
                        
                        <form class="login-form" method="POST" action="{{ route('login.post') }}">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label small" for="remember">Ingat saya</label>
                                </div>
                                <a href="#" class="small text-primary">Lupa password?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3">
                                Masuk
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">
                                Belum punya akun? <a href="{{ url('/daftar-umkm') }}" class="text-primary fw-semibold">Daftar Sekarang</a>
                            </p>
                        </div>
                    </div>
                    
                    <p class="text-center text-muted small mt-4">
                        <a href="{{ url('/') }}" class="text-muted"><i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda</a>
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

.input-group .btn {
    border-radius: 0 12px 12px 0;
    border-color: var(--border-color);
}

.form-label {
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 8px;
}
</style>
@endpush

@push('scripts')
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.querySelector('input[type="password"], input[type="text"]');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});
</script>
@endpush
