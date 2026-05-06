@extends('layouts.dashboard')

@section('title', 'Pesan Masuk')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
        <li class="breadcrumb-item active">Pesan Masuk</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pesan Masuk (Hubungi Kami)</h1>
        <p class="text-muted">Kelola pesan dan pertanyaan dari pengunjung website</p>
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
                            <th class="px-4 py-3 border-0">Pengirim</th>
                            <th class="py-3 border-0">Subjek</th>
                            <th class="py-3 border-0">Tanggal</th>
                            <th class="py-3 border-0 text-center">Status</th>
                            <th class="px-4 py-3 border-0 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr class="{{ !$message->is_read ? 'fw-bold bg-light-blue' : '' }}">
                            <td class="px-4">
                                <div class="text-dark">{{ $message->name }}</div>
                                <div class="small text-muted">{{ $message->email }}</div>
                            </td>
                            <td>
                                <div class="text-dark">{{ $message->subject ?? 'Tanpa Subjek' }}</div>
                                <div class="small text-muted text-truncate" style="max-width: 250px;">
                                    {{ Str::limit($message->message, 80) }}
                                </div>
                            </td>
                            <td>
                                <div class="small">{{ $message->created_at->format('d M Y') }}</div>
                                <div class="small text-muted">{{ $message->created_at->format('H:i') }}</div>
                            </td>
                            <td class="text-center">
                                @if($message->is_read)
                                    <span class="badge bg-light text-muted rounded-pill px-3">Dibaca</span>
                                @else
                                    <span class="badge bg-primary rounded-pill px-3">Baru</span>
                                @endif
                            </td>
                            <td class="px-4 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.contact_messages.show', $message->id) }}" class="btn btn-sm btn-light border rounded-pill me-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.contact_messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
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
                                <i class="bi bi-envelope-open display-4 text-muted mb-3"></i>
                                <p class="text-muted">Belum ada pesan masuk</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($messages->hasPages())
        <div class="card-footer bg-white border-0 py-4 px-4">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .bg-light-blue { background-color: rgba(0, 123, 255, 0.05) !important; }
</style>
@endsection
