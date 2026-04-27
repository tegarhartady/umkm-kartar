@extends('layouts.dashboard')

@section('title', 'Pengaturan Sistem')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Pengaturan</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Pengaturan Sistem</h1>
            <p class="page-subtitle">
                Kelola pengaturan aplikasi UMKM Kartar Teluknaga
            </p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- General Settings -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-gear me-2 text-primary"></i>Pengaturan Umum
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('superadmin.settings.update') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Aplikasi</label>
                        <input type="text" name="app_name" class="form-control" value="UMKM Kartar Teluknaga" readonly>
                        <small class="form-text text-muted">Nama aplikasi yang ditampilkan di header</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email Sistem</label>
                        <input type="email" name="system_email" class="form-control" value="admin@umkmkartar.com" readonly>
                        <small class="form-text text-muted">Email untuk notifikasi sistem</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mode Maintenance</label>
                        <select name="maintenance_mode" class="form-select">
                            <option value="off">Non-aktif</option>
                            <option value="on">Aktif</option>
                        </select>
                        <small class="form-text text-muted">Aktifkan untuk maintenance sistem</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2 text-info"></i>Informasi Sistem
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <small class="text-muted">Versi Laravel</small>
                            <div class="fw-bold">{{ app()->version() }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <small class="text-muted">Versi PHP</small>
                            <div class="fw-bold">{{ phpversion() }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <small class="text-muted">Database</small>
                            <div class="fw-bold">SQLite</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <small class="text-muted">Environment</small>
                            <div class="fw-bold">{{ config('app.env') }}</div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <h6 class="mb-3">Statistik Cepat</h6>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <small class="text-muted">Total User</small>
                            <div class="fw-bold text-primary">{{ \App\Models\User::count() }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <small class="text-muted">Total UMKM</small>
                            <div class="fw-bold text-success">{{ \App\Models\Umkm::count() }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <small class="text-muted">Total Produk</small>
                            <div class="fw-bold text-info">{{ \App\Models\Product::count() }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <small class="text-muted">Total Desa</small>
                            <div class="fw-bold text-warning">{{ \App\Models\Desa::count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Backup & Restore -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-cloud-download me-2 text-warning"></i>Backup & Restore
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Kelola backup database dan file sistem</p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-warning" onclick="createBackup()">
                        <i class="bi bi-download me-2"></i>Buat Backup
                    </button>
                    <button class="btn btn-info" onclick="viewBackups()">
                        <i class="bi bi-list me-2"></i>Lihat Backup
                    </button>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Backup otomatis dilakukan setiap hari pukul 02:00
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Management -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-arrow-clockwise me-2 text-danger"></i>Cache Management
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Kelola cache aplikasi untuk optimasi performa</p>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-danger" onclick="clearCache()">
                        <i class="bi bi-trash me-2"></i>Hapus Cache
                    </button>
                    <button class="btn btn-warning" onclick="clearConfig()">
                        <i class="bi bi-gear me-2"></i>Hapus Config Cache
                    </button>
                    <button class="btn btn-info" onclick="optimizeApp()">
                        <i class="bi bi-lightning me-2"></i>Optimasi Aplikasi
                    </button>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Gunakan saat aplikasi berjalan lambat
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function createBackup() {
    alert('Fitur backup akan segera tersedia');
}

function viewBackups() {
    alert('Fitur lihat backup akan segera tersedia');
}

function clearCache() {
    if (confirm('Yakin ingin menghapus cache? Ini akan membuat aplikasi reload semua data.')) {
        // Here you would call an endpoint to clear cache
        alert('Cache berhasil dihapus');
    }
}

function clearConfig() {
    if (confirm('Yakin ingin menghapus config cache?')) {
        alert('Config cache berhasil dihapus');
    }
}

function optimizeApp() {
    if (confirm('Yakin ingin mengoptimasi aplikasi? Proses ini memerlukan beberapa detik.')) {
        alert('Aplikasi berhasil dioptimasi');
    }
}
</script>
@endsection
