@extends('layouts.dashboard')

@section('title', 'Detail Pesan')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.contact_messages.index') }}">Pesan Masuk</a></li>
        <li class="breadcrumb-item active">Detail</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 fw-bold">Detail Pesan dari: {{ $message->name }}</h5>
                    <a href="{{ route('admin.contact_messages.index') }}" class="btn btn-sm btn-light border rounded-pill">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted d-block uppercase fw-bold">Nama Pengirim</label>
                            <div class="fs-5 fw-bold text-dark">{{ $message->name }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted d-block uppercase fw-bold">Email</label>
                            <div class="fs-5 text-dark">{{ $message->email }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted d-block uppercase fw-bold">Subjek</label>
                            <div class="text-dark">{{ $message->subject ?? 'Tanpa Subjek' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted d-block uppercase fw-bold">Waktu Pengiriman</label>
                            <div class="text-dark">{{ $message->created_at->format('d F Y, H:i') }}</div>
                        </div>
                    </div>

                    <div class="p-4 bg-light rounded-4 border">
                        <label class="small text-muted d-block uppercase fw-bold mb-2">Isi Pesan:</label>
                        <div class="text-dark" style="line-height: 1.8; white-space: pre-line;">
                            {{ $message->message }}
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="mailto:{{ $message->email }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-reply-fill me-2"></i>Balas via Email
                        </a>
                        <form action="{{ route('admin.contact_messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                <i class="bi bi-trash me-2"></i>Hapus Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .uppercase { text-transform: uppercase; letter-spacing: 1px; }
</style>
@endsection
