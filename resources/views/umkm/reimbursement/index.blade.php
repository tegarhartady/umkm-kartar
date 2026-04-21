@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 fw-bold">Reimbursement</h1>
                <a href="{{ route('reimbursement.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Ajukan Reimbursement
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($reimbursements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reimbursements as $item)
                                <tr>
                                    <td><strong>{{ $item->judul }}</strong></td>
                                    <td><span class="badge bg-info">{{ ucfirst($item->kategori) }}</span></td>
                                    <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->status === 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($item->status === 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('reimbursement.show', $item->id) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center py-3">
                    {{ $reimbursements->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Belum ada reimbursement</p>
                    <a href="{{ route('reimbursement.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> Ajukan Reimbursement Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection