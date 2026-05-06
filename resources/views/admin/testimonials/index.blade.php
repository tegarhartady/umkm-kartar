@extends('layouts.dashboard')

@section('title', 'Kelola Testimoni')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
        <li class="breadcrumb-item active">Testimoni</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Testimoni Pelanggan</h1>
            <p class="text-muted">Kelola testimoni yang ditampilkan di halaman utama</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i>Tambah Testimoni
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="px-4 py-3 border-0">Pengguna</th>
                            <th class="py-3 border-0">Konten</th>
                            <th class="py-3 border-0">Rating</th>
                            <th class="py-3 border-0 text-center">Status</th>
                            <th class="px-4 py-3 border-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($testimonial->avatar)
                                            <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="rounded-circle" width="45" height="45" style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" width="45" height="45" style="width: 45px; height: 45px;">
                                                {{ substr($testimonial->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $testimonial->name }}</div>
                                        <div class="small text-muted">{{ $testimonial->role }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-muted small" style="max-width: 300px;">
                                    "{{ Str::limit($testimonial->content, 100) }}"
                                </div>
                            </td>
                            <td>
                                <div class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="text-center">
                                @if($testimonial->is_active)
                                    <span class="badge bg-success-light text-success rounded-pill px-3">Aktif</span>
                                @else
                                    <span class="badge bg-secondary-light text-secondary rounded-pill px-3">Draft</span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-light border rounded-pill me-2">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" onsubmit="return confirm('Hapus testimoni ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border rounded-pill text-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-chat-quote display-4 text-muted mb-3"></i>
                                <p class="text-muted">Belum ada testimoni yang ditambahkan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($testimonials->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4">
            {{ $testimonials->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .bg-success-light { background: rgba(40, 167, 69, 0.1); }
    .bg-secondary-light { background: rgba(108, 117, 125, 0.1); }
</style>
@endsection
