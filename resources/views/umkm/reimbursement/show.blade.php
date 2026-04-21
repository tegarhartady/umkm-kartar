@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 fw-bold">Detail Reimbursement</h1>
                <a href="{{ route('reimbursement.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">{{ $reimbursement->judul }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Jumlah</p>
                            <h4 class="text-primary fw-bold">Rp {{ number_format($reimbursement->jumlah, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Kategori</p>
                            <span class="badge bg-info">{{ ucfirst($reimbursement->kategori) }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted mb-2">Deskripsi</p>
                        <p>{{ $reimbursement->deskripsi }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted mb-2">Status</p>
                        @if($reimbursement->status === 'pending')
                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                        @elseif($reimbursement->status === 'disetujui')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </div>

                    @if($reimbursement->catatan_admin)
                        <div class="mb-4">
                            <p class="text-muted mb-2">Catatan Admin</p>
                            <div class="alert alert-info">{{ $reimbursement->catatan_admin }}</div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <p class="text-muted mb-2">Tanggal Pengajuan</p>
                        <p>{{ $reimbursement->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Bukti Gambar -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">📷 Bukti Gambar</h5>
                </div>
                <div class="card-body">
                    @if($reimbursement->bukti_gambar)
                        <img src="{{ asset('storage/' . $reimbursement->bukti_gambar) }}" alt="Bukti" class="img-fluid rounded" style="max-height: 500px;">
                    @else
                        <p class="text-muted">Belum ada bukti gambar</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection