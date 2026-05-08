@extends('layouts.dashboard')

@section('title', 'Manajemen Promo & Event')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Promo & Event</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPromoModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Promo Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Promo</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Judul (Main)</th>
                            <th>Subtitle (Lokasi)</th>
                            <th>Tombol</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promo)
                        <tr>
                            <td>
                                <form action="{{ route('admin.promotions.toggle', $promo) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $promo->is_active ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $promo->is_active ? 'Aktif' : 'Non-aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td><strong>{{ $promo->title }}</strong></td>
                            <td>{{ $promo->subtitle }}</td>
                            <td>
                                <span class="badge bg-info">{{ $promo->button_text }}</span><br>
                                <small class="text-muted">{{ $promo->button_link }}</small>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary edit-promo" 
                                        data-promo='@json($promo)'
                                        data-bs-toggle="modal" data-bs-target="#editPromoModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus promo ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data promo.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $promotions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createPromoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.promotions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Promo Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Promo (Contoh: Floating Market UMKM)</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle / Lokasi (Contoh: Sunset Pier)</label>
                        <input type="text" name="subtitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teks Tombol</label>
                                <input type="text" name="button_text" class="form-control" value="Jelajahi Sekarang" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Link Tombol</label>
                                <input type="text" name="button_link" class="form-control" value="/katalog" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActiveCheck" checked>
                            <label class="form-check-label" for="isActiveCheck">Aktifkan Sekarang (Otomatis menonaktifkan promo lain)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPromoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editPromoForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Promo</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subtitle / Lokasi</label>
                        <input type="text" name="subtitle" id="edit_subtitle" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teks Tombol</label>
                                <input type="text" name="button_text" id="edit_button_text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Link Tombol</label>
                                <input type="text" name="button_link" id="edit_button_link" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active">
                            <label class="form-check-label" for="edit_is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.edit-promo');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const promo = JSON.parse(this.getAttribute('data-promo'));
                const url = `{{ route('admin.promotions.update', ':id') }}`.replace(':id', promo.id);
                
                document.getElementById('editPromoForm').setAttribute('action', url);
                document.getElementById('edit_title').value = promo.title;
                document.getElementById('edit_subtitle').value = promo.subtitle;
                document.getElementById('edit_description').value = promo.description;
                document.getElementById('edit_button_text').value = promo.button_text;
                document.getElementById('edit_button_link').value = promo.button_link;
                document.getElementById('edit_is_active').checked = (promo.is_active == 1);
            });
        });
    });
</script>
@endpush
@endsection
