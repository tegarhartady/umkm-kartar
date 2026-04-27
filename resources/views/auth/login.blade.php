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
                        <a href="{{ url('/') }}" class="d-inline-flex">
                            <img src="{{ asset('images/smartumkm.svg') }}" alt="Smart UMKM Logo" style="height: 80px; width: auto;">
                        </a>
                    </div>
                    
                    <div class="login-card">
                        <h3 class="login-title text-center">Selamat Datang</h3>
                        <p class="login-subtitle text-center text-muted mb-4">Masuk ke akun Anda untuk melanjutkan</p>
                        
                        <form class="login-form" method="POST" action="{{ route('login.authenticate') }}" id="loginForm">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label class="form-label">Login Sebagai</label>
                                <div class="role-selector">
                                    <input type="radio" id="role-umkm" name="role" value="umkm" checked onchange="updateFormAction()">
                                    <label for="role-umkm" class="role-option">
                                        <i class="bi bi-shop"></i>
                                        <span>UMKM</span>
                                    </label>
                                    
                                    <input type="radio" id="role-admin" name="role" value="admin" onchange="updateFormAction()">
                                    <label for="role-admin" class="role-option">
                                        <i class="bi bi-person-badge"></i>
                                        <span>Admin</span>
                                    </label>
                                </div>
                            </div>
                            
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
                            <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 btn-login">
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
    border-color: #001f5c;
    box-shadow: 0 0 0 0.2rem rgba(0, 31, 92, 0.15);
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

/* Role Selector */
.role-selector {
    display: flex;
    gap: 1rem;
}

.role-selector input[type="radio"] {
    display: none;
}

.role-option {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
    font-weight: 500;
    color: #6c757d;
}

.role-selector input[type="radio"]:checked + .role-option {
    border-color: var(--primary-color);
    background: rgba(0, 166, 126, 0.1);
    color: var(--primary-color);
}

.role-option i {
    font-size: 1.5rem;
}

.login-container {
    background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
}

.btn-login {
    background: #001f5c;
    border-color: #001f5c;
    color: white;
}

.btn-login:hover {
    background: #000f3d;
    border-color: #000f3d;
    color: white;
    box-shadow: 0 4px 15px rgba(0, 31, 92, 0.3);
}

a {
    color: #001f5c;
}

a:hover {
    color: #000f3d;
}

.text-primary {
    color: #001f5c !important;
}
</style>
@endpush

@push('scripts')
<script>
function updateFormAction() {
    const form = document.getElementById('loginForm');
    const role = document.querySelector('input[name="role"]:checked').value;
    
    if (role === 'umkm') {
        form.action = "{{ route('umkm.authenticate') }}";
    } else {
        form.action = "{{ route('login.authenticate') }}";
    }
}

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

// Set initial form action
updateFormAction();
</script>
@endpush
