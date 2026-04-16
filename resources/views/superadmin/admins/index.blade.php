@extends('layouts.dashboard')

@section('title', 'Kelola Admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Kelola Admin</li>
        </ol>
    </nav>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="page-title">Kelola Admin</h1>
            <p class="page-subtitle">
                Kelola akun administrator sistem
            </p>
        </div>
        <div class="col-auto">
            <a href="{{ route('superadmin.admins.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-2"></i>Tambah Admin
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">Daftar Administrator</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if($admins->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Bergabung</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $index => $admin)
                        <tr>
                            <td>{{ $admins->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-white fw-bold">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <strong>{{ $admin->name }}</strong>
                                        <div class="small text-muted">Administrator</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('superadmin.admins.edit', $admin->id) }}" 
                                       class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($admin->id !== auth()->id())
                                    <button type="button" class="btn btn-outline-danger" 
                                            onclick="confirmDelete({{ $admin->id }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $admins->firstItem() }} sampai {{ $admins->lastItem() }} 
                    dari {{ $admins->total() }} admin
                </div>
                {{ $admins->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-person-gear fs-1 text-muted d-block mb-3"></i>
                <h5>Belum ada admin</h5>
                <p class="text-muted">Klik tombol "Tambah Admin" untuk menambah administrator baru</p>
                <a href="{{ route('superadmin.admins.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus me-2"></i>Tambah Admin
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus admin ini?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua akses admin tersebut.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function confirmDelete(adminId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/superadmin/admins/${adminId}`;
    modal.show();
}
</script>
@endsection
