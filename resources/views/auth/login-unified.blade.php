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

                        <!-- Role Selection Pills -->
                        <ul class="nav nav-pills mb-4 gap-2" id="loginTabs" role="tablist" style="display: flex; justify-content: center; flex-wrap: wrap;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-2 px-3 px-md-4 rounded-pill fw-bold" id="customer-tab" data-bs-toggle="pill" data-bs-target="#customer-login" type="button" role="tab" style="border: 2px solid #ddd; color: #666; background-color: transparent; transition: all 0.3s; white-space: nowrap; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    <i class="bi bi-person me-2"></i> Pelanggan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2 px-3 px-md-4 rounded-pill fw-bold" id="umkm-tab" data-bs-toggle="pill" data-bs-target="#umkm-login" type="button" role="tab" style="border: 2px solid #ddd; color: #666; background-color: transparent; transition: all 0.3s; white-space: nowrap; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    <i class="bi bi-shop me-2"></i> UMKM
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2 px-3 px-md-4 rounded-pill fw-bold" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin-login" type="button" role="tab" style="border: 2px solid #ddd; color: #666; background-color: transparent; transition: all 0.3s; white-space: nowrap; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    <i class="bi bi-shield-lock me-2"></i> Admin
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="loginTabContent">
                            <!-- Customer Login -->
                            <div class="tab-pane fade show active" id="customer-login" role="tabpanel">
                                @if($errors->has('customer_error'))
                                    <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
                                        <i class="bi bi-exclamation-circle me-2"></i>
                                        {{ $errors->first('customer_error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <form action="{{ route('login.authenticate') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="role" value="customer">

                                    <div class="mb-3">
                                        <label for="customer_email" class="form-label fw-bold" style="font-size: clamp(0.875rem, 2vw, 1rem);">Email</label>
                                        <input type="email" class="form-control rounded-lg" id="customer_email" name="email" placeholder="email@contoh.com" required autofocus style="border: 2px solid #eee; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    </div>

                                    <div class="mb-4">
                                        <label for="customer_password" class="form-label fw-bold" style="font-size: clamp(0.875rem, 2vw, 1rem);">Password</label>
                                        <input type="password" class="form-control rounded-lg" id="customer_password" name="password" placeholder="••••••••" required style="border: 2px solid #eee; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn rounded-lg fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                            <i class="bi bi-box-arrow-in-right me-2"></i> Login Pelanggan
                                        </button>
                                    </div>
                                </form>

                                <div class="text-center mt-4">
                                    <p class="text-muted mb-0" style="font-size: clamp(0.75rem, 2vw, 0.875rem);">Belum punya akun? <a href="{{ route('register.customer') }}" class="text-decoration-none fw-bold" style="color: #667eea;">Daftar Pelanggan</a></p>
                                </div>
                            </div>

                            <!-- UMKM Login -->
                            <div class="tab-pane fade" id="umkm-login" role="tabpanel">
                                @if($errors->has('umkm_error'))
                                    <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
                                        <i class="bi bi-exclamation-circle me-2"></i>
                                        {{ $errors->first('umkm_error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <form action="{{ route('login.authenticate') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="role" value="umkm">

                                    <div class="mb-3">
                                        <label for="umkm_email" class="form-label fw-bold" style="font-size: clamp(0.875rem, 2vw, 1rem);">Email</label>
                                        <input type="email" class="form-control rounded-lg" id="umkm_email" name="email" placeholder="masukkan@email.com" value="{{ old('email') }}" required autofocus style="border: 2px solid #eee; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    </div>

                                    <div class="mb-4">
                                        <label for="umkm_password" class="form-label fw-bold" style="font-size: clamp(0.875rem, 2vw, 1rem);">Password</label>
                                        <input type="password" class="form-control rounded-lg" id="umkm_password" name="password" placeholder="••••••••" required style="border: 2px solid #eee; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn rounded-lg fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                            <i class="bi bi-box-arrow-in-right me-2"></i> Login UMKM
                                        </button>
                                    </div>
                                </form>

                                <div class="text-center mt-4">
                                    <p class="text-muted mb-0" style="font-size: clamp(0.75rem, 2vw, 0.875rem);">Belum punya akun? <a href="/daftar-umkm" class="text-decoration-none fw-bold" style="color: #667eea;">Daftar di sini</a></p>
                                </div>
                            </div>

                            <!-- Admin Login -->
                            <div class="tab-pane fade" id="admin-login" role="tabpanel">
                                @if($errors->has('admin_error'))
                                    <div class="alert alert-danger alert-dismissible fade show rounded-lg" role="alert">
                                        <i class="bi bi-exclamation-circle me-2"></i>
                                        {{ $errors->first('admin_error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <form action="{{ route('login.authenticate') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="role" value="admin">

                                    <div class="mb-3">
                                        <label for="admin_email" class="form-label fw-bold" style="font-size: clamp(0.875rem, 2vw, 1rem);">Email Admin</label>
                                        <input type="email" class="form-control rounded-lg" id="admin_email" name="email" placeholder="admin@localhost.com" required autofocus style="border: 2px solid #eee; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    </div>

                                    <div class="mb-4">
                                        <label for="admin_password" class="form-label fw-bold" style="font-size: clamp(0.875rem, 2vw, 1rem);">Password</label>
                                        <input type="password" class="form-control rounded-lg" id="admin_password" name="password" placeholder="••••••••" required style="border: 2px solid #eee; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn rounded-lg fw-bold" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; transition: all 0.3s; padding: clamp(0.5rem, 2vw, 0.75rem) 1rem; font-size: clamp(0.875rem, 2vw, 1rem);">
                                            <i class="bi bi-shield-lock me-2"></i> Login Admin
                                        </button>
                                    </div>
                                </form>
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