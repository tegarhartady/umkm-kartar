@extends('layouts.dashboard')

@section('title', 'Edit Testimoni')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}">Testimoni</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-bottom py-3 px-4" style="border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 fw-bold">Edit Testimoni: {{ $testimonial->name }}</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Pengguna</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $testimonial->name) }}" placeholder="Contoh: Pak Budi" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jabatan / Role</label>
                                <input type="text" name="role" class="form-control @error('role') is-invalid @enderror" value="{{ old('role', $testimonial->role) }}" placeholder="Contoh: Pembeli Setia / Pemilik UMKM" required>
                                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Profil (Avatar)</label>
                            @if($testimonial->avatar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, max 2MB. Kosongkan jika tidak ingin mengubah foto.</small>
                            @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Rating</label>
                            <select name="rating" class="form-select @error('rating') is-invalid @enderror" required>
                                <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Bintang)</option>
                                <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Bintang)</option>
                                <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 Bintang)</option>
                                <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>⭐⭐ (2 Bintang)</option>
                                <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>⭐ (1 Bintang)</option>
                            </select>
                            @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Konten Testimoni</label>
                            <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="5" placeholder="Tuliskan testimoni di sini..." required>{{ old('content', $testimonial->content) }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">Aktifkan Testimoni (Tampilkan di Beranda)</label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-check-lg me-2"></i>Perbarui Testimoni
                            </button>
                            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-light border rounded-pill px-4">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
