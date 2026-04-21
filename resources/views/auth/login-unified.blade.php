@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">
                <div class="card shadow-lg border-0 rounded-lg" style="backdrop-filter: blur(10px);">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <img src="/images/logo1.png" alt="Lokalin" style="height: 60px; width: auto;" class="mb-3">
                            <h2 class="fw-bold text-dark">Selamat Datang</h2>
                            <p class="text-muted">Akselerasi UMKM Binaan CSR PIK2</p>
                        </div>

                        <!-- Role Selection Pills -->
                        <ul class="nav nav-pills mb-4 gap-2" id="loginTabs" role="tablist" style="display: flex; justify-content: center;">
                            <li class="nav-item flex-grow-1" role="presentation">
                                <button class="nav-link active w-100 py-2 rounded-pill fw-bold" id="umkm-tab" data-bs-toggle="pill" data-bs-target="#umkm-login" type="button" role="tab" style="border: 2px solid #667eea; color: #667eea; background-color: transparent; transition: all 0.3s;">
                                    <i class="bi bi-shop me-2"></i> UMKM
                                </button>
                            </li>
                            <li class="nav-item flex-grow-1" role="presentation">
                                <button class="nav-link w-100 py-2 rounded-pill fw-bold" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin-login" type="button" role="tab" style="border: 2px solid #ddd; color: #666; background-color: transparent; transition: all 0.3s;">
                                    <i class="bi bi-shield-lock me-2"></i> Admin
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="loginTabContent">
                            <!-- UMKM Login -->
                            <div class="tab-pane fade show active" id="umkm-login" role="tabpanel">
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
                                        <label for="umkm_email" class="form-label fw-bold">Email</label>
                                        <input type="email" class="form-control form-control-lg rounded-lg" id="umkm_email" name="email" placeholder="masukkan@email.com" value="{{ old('email') }}" required autofocus style="border: 2px solid #eee; transition: all 0.3s;">
                                    </div>

                                    <div class="mb-4">
                                        <label for="umkm_password" class="form-label fw-bold">Password</label>
                                        <input type="password" class="form-control form-control-lg rounded-lg" id="umkm_password" name="password" placeholder="••••••••" required style="border: 2px solid #eee; transition: all 0.3s;">
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-lg rounded-lg fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; transition: all 0.3s;">
                                            <i class="bi bi-box-arrow-in-right me-2"></i> Login UMKM
                                        </button>
                                    </div>
                                </form>

                                <div class="text-center mt-4">
                                    <p class="text-muted mb-0">Belum punya akun? <a href="/daftar-umkm" class="text-decoration-none fw-bold" style="color: #667eea;">Daftar di sini</a></p>
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
                                        <label for="admin_email" class="form-label fw-bold">Email Admin</label>
                                        <input type="email" class="form-control form-control-lg rounded-lg" id="admin_email" name="email" placeholder="admin@localhost.com" required autofocus style="border: 2px solid #eee; transition: all 0.3s;">
                                    </div>

                                    <div class="mb-4">
                                        <label for="admin_password" class="form-label fw-bold">Password</label>
                                        <input type="password" class="form-control form-control-lg rounded-lg" id="admin_password" name="password" placeholder="••••••••" required style="border: 2px solid #eee; transition: all 0.3s;">
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-lg rounded-lg fw-bold" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; transition: all 0.3s;">
                                            <i class="bi bi-shield-lock me-2"></i> Login Admin
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <a href="/" class="text-muted text-decoration-none small">← Kembali ke Halaman Utama</a>
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
</style>
@endsection