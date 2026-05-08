@extends('layouts.dashboard')

@section('title', 'Master Bank')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Master Bank</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Bank
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Bank / E-Wallet</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Bank / E-Wallet</th>
                            <th>Kode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banks as $index => $bank)
                        <tr>
                            <td>{{ $banks->firstItem() + $index }}</td>
                            <td><strong>{{ $bank->nama_bank }}</strong></td>
                            <td>{{ $bank->kode_bank ?? '-' }}</td>
                            <td>
                                <form action="{{ route('admin.master.banks.toggle', $bank) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $bank->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $bank->is_active ? 'Aktif' : 'Non-aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editBankModal{{ $bank->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.master.banks.destroy', $bank) }}" method="POST" onsubmit="return confirm('Hapus bank ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editBankModal{{ $bank->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.master.banks.update', $bank) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Bank</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Bank / E-Wallet</label>
                                                        <input type="text" name="nama_bank" class="form-control" value="{{ $bank->nama_bank }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kode Bank (Opsional)</label>
                                                        <input type="text" name="kode_bank" class="form-control" value="{{ $bank->kode_bank }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_active{{ $bank->id }}" {{ $bank->is_active ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_active{{ $bank->id }}">Aktif</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data bank.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $banks->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.master.banks.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Bank Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Bank / E-Wallet</label>
                        <input type="text" name="nama_bank" class="form-control" placeholder="Contoh: BCA, OVO, Dana" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Bank (Opsional)</label>
                        <input type="text" name="kode_bank" class="form-control" placeholder="Contoh: 014">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Bank</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
