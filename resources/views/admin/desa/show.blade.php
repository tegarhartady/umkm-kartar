@extends('layouts.dashboard')

@section('title', 'Detail Desa - ' . $desa->nama_desa)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.desa.index') }}">Kelola Desa</a></li>
        <li class="breadcrumb-item active">{{ $desa->nama_desa }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-lg-4 bg-primary p-5 text-white d-flex flex-column justify-content-center align-items-center text-center">
                            <div class="mb-3">
                                <i class="bi bi-geo-alt-fill" style="font-size: 4rem;"></i>
                            </div>
                            <h2 class="fw-bold mb-1">{{ $desa->nama_desa }}</h2>
                            <p class="opacity-75 mb-0">{{ $desa->kecamatan }}, {{ $desa->kabupaten }}</p>
                        </div>
                        <div class="col-lg-8 p-5 bg-white">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">Informasi Desa</h5>
                                    <p class="text-muted">Detail profil dan statistik desa binaan</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.desa.edit', $desa->id) }}" class="btn btn-outline-primary rounded-pill px-4">
                                        <i class="bi bi-pencil me-2"></i>Edit Desa
                                    </a>
                                    <a href="{{ route('admin.desa.index') }}" class="btn btn-light rounded-pill px-4 border">
                                        <i class="bi bi-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </div>
                            
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light">
                                        <small class="text-muted d-block mb-1">Kecamatan</small>
                                        <span class="fw-bold text-dark fs-5">{{ $desa->kecamatan }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-4 bg-light">
                                        <small class="text-muted d-block mb-1">Kabupaten</small>
                                        <span class="fw-bold text-dark fs-5">{{ $desa->kabupaten }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-0">
                                <h6 class="fw-bold text-dark mb-2">Deskripsi Desa</h6>
                                <p class="text-muted mb-0" style="line-height: 1.6;">
                                    {{ $desa->deskripsi ?? 'Belum ada deskripsi untuk desa ini.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- UMKM List in this Village -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0">Daftar UMKM di Desa {{ $desa->nama_desa }}</h5>
                        <small class="text-muted">Total {{ $umkms->total() }} UMKM terdaftar</small>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase fw-bold">
                                <tr>
                                    <th class="px-4 py-3 border-0">UMKM</th>
                                    <th class="py-3 border-0">Pemilik</th>
                                    <th class="py-3 border-0">Kategori</th>
                                    <th class="py-3 border-0">Status</th>
                                    <th class="py-3 border-0">Terdaftar Pada</th>
                                    <th class="px-4 py-3 border-0 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($umkms as $umkm)
                                <tr>
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-light rounded-circle me-3 d-flex align-items-center justify-content-center text-primary">
                                                <i class="bi bi-shop fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $umkm->nama_toko }}</div>
                                                <div class="small text-muted">{{ $umkm->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $umkm->pemilik }}</td>
                                    <td><span class="badge bg-light text-dark px-3 rounded-pill">{{ $umkm->kategori_umkm }}</span></td>
                                    <td>
                                        @if($umkm->status == 'disetujui')
                                            <span class="badge bg-success-light text-success px-3 rounded-pill">Aktif</span>
                                        @elseif($umkm->status == 'pending')
                                            <span class="badge bg-warning-light text-warning px-3 rounded-pill">Pending</span>
                                        @else
                                            <span class="badge bg-danger-light text-danger px-3 rounded-pill">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $umkm->created_at->format('d M Y') }}</td>
                                    <td class="px-4 text-end">
                                        <a href="{{ route('admin.umkm.show', $umkm->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                            Detail <i class="bi bi-chevron-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="bi bi-shop-window text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-3 mb-0">Belum ada UMKM yang terdaftar di desa ini.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($umkms->hasPages())
                <div class="card-footer bg-white border-0 py-4 px-4">
                    {{ $umkms->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-primary {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
    }
    .text-primary {
        color: #667eea !important;
    }
    .btn-outline-primary {
        color: #667eea;
        border-color: #667eea;
    }
    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
    }
    .bg-success-light { background: rgba(40, 167, 69, 0.1); }
    .bg-warning-light { background: rgba(255, 193, 7, 0.1); }
    .bg-danger-light { background: rgba(220, 53, 69, 0.1); }
    .bg-primary-light { background: rgba(102, 126, 234, 0.1); }
    .avatar-sm { width: 40px; height: 40px; }
    .rounded-4 { border-radius: 1rem !important; }
</style>
@endpush
@endsection
