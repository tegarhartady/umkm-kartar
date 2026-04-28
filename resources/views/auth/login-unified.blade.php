@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5 col-sm-10">
                <div class="card shadow-lg border-0 rounded-lg" style="backdrop-filter: blur(10px);">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4 mb-md-5">
                            <img src="{{ asset('images/smartumkm.svg') }}" alt="Smart UMKM Logo" style="height: clamp(80px, 15vw, 120px); width: auto; margin-bottom: 20px;">
                            <h2 class="fw-bold text-dark" style="font-size: clamp(1.5rem, 4vw, 1.75rem);">Selamat Datang</h2>
                            <p class="text-muted" style="font-size: clamp(0.875rem, 2vw, 1rem);">Akselerasi UMKM Binaan CSR PIK2</p>
                        </div>

                        @if($errors->has('email'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
                                <i class="bi bi-exclamation-circle me-2"></i>
                                {{ $errors->first('email') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('login.authenticate') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold" style="font-size: clamp(0.875rem, 2vw, 1rem);">Email</label>
                                <input type="email" class="form-control rounded-lg" id="email" name="email" placeholder="masukkan@email.com" value="{{ old('email') }}" required autofocus style="border: 2px solid #eee; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold" style="font-size: clamp(0.875rem, 2vw, 1rem);">Password</label>
                                <input type="password" class="form-control rounded-lg" id="password" name="password" placeholder="••••••••" required style="border: 2px solid #eee; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn rounded-lg fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sistem
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0" style="font-size: clamp(0.75rem, 2vw, 0.875rem);">Belum punya akun?</p>
                            <div class="d-flex justify-content-center gap-3 mt-2">
                                <a href="{{ route('register.customer') }}" class="text-decoration-none fw-bold" style="color: #667eea;"><i class="bi bi-person me-1"></i>Daftar Pelanggan</a>
                                <span class="text-muted">|</span>
                                <a href="/daftar-umkm" class="text-decoration-none fw-bold" style="color: #667eea;"><i class="bi bi-shop me-1"></i>Daftar UMKM</a>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <a href="/" class="text-muted text-decoration-none small" style="font-size: clamp(0.75rem, 2vw, 0.875rem);">← Kembali ke Halaman Utama</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
        border: none !important;
    }

    .form-control:focus {
        border: 2px solid #667eea !important;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .min-vh-100 {
            min-height: auto !important;
            padding: 20px 0 !important;
        }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
    }
</style>
@endsection