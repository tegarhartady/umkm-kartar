@extends('layouts.dashboard')

@section('title', 'Detail UMKM')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.umkm.index') }}">UMKM</a></li>
            <li class="breadcrumb-item active">{{ $umkm->nama_toko }}</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Informasi UMKM</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                    <i class="bi bi-shop" style="font-size: 3rem; color: #667eea;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-1">{{ $umkm->nama_toko }}</h3>
                            <p class="text-muted mb-3">Pemilik: {{ $umkm->pemilik }}</p>
                            
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <th width="40%">Status</th>
                                    <td>
                                        @if($umkm->status == 'pending')
                                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                                        @elseif($umkm->status == 'disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($umkm->status == 'ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $umkm->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td>{{ $umkm->kategori }}</td>
                                </tr>
                                <tr>
                                    <th>Desa</th>
                                    <td>{{ $umkm->desa }}</td>
                                </tr>
                                <tr>
                                    <th>Produk</th>
                                    <td>{{ $umkm->products_count ?? 0 }} produk</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Kontak & Lokasi</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Email</p>
                            <p class="mb-3">{{ $umkm->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Telepon</p>
                            <p class="mb-3">{{ $umkm->phone }}</p>
                        </div>
                    </div>

                    <p class="text-muted small mb-1">Alamat</p>
                    <p class="mb-3">{{ $umkm->alamat }}</p>

                    @if($umkm->omzet_bulanan)
                        <p class="text-muted small mb-1">Omzet Bulanan</p>
                        <p class="mb-3">Rp {{ number_format($umkm->omzet_bulanan, 0, ',', '.') }}</p>
                    @endif

                    @if($umkm->deskripsi)
                        <hr class="my-4">
                        <h5 class="mb-3">Deskripsi</h5>
                        <p class="text-muted">{{ $umkm->deskripsi }}</p>
                    @endif

                    <hr class="my-4">

                    <p class="text-muted small">
                        <i class="bi bi-calendar me-1"></i>Terdaftar: {{ $umkm->created_at->format('d M Y H:i') }}<br>
                        <i class="bi bi-arrow-repeat me-1"></i>Diperbarui: {{ $umkm->updated_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.umkm.edit', $umkm->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.umkm.destroy', $umkm->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menghapus UMKM ini?')">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tombol Approve/Reject untuk UMKM Pending -->
            @if($umkm->status === 'pending')
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Verifikasi</h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">UMKM ini menunggu persetujuan Anda.</p>
                        <form action="{{ route('admin.umkm.approve', $umkm->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui UMKM {{ $umkm->nama_toko }}?')">
                                <i class="bi bi-check-circle me-2"></i>Setujui UMKM
                            </button>
                        </form>
                        <form action="{{ route('admin.umkm.reject', $umkm->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak UMKM {{ $umkm->nama_toko }}?')">
                                <i class="bi bi-x-circle me-2"></i>Tolak UMKM
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Produk</h5>
                </div>
                <div class="card-body">
                    @if($umkm->products && $umkm->products->count() > 0)
                        <p class="small text-muted mb-3">{{ $umkm->products->count() }} produk terdaftar</p>
                        <div class="list-group list-group-flush">
                            @foreach($umkm->products->take(5) as $product)
                                <a href="{{ route('admin.products.show', $product->id) }}" class="list-group-item list-group-item-action px-0 py-2">
                                    <div class="small">{{ $product->nama }}</div>
                                    <small class="text-muted">Rp {{ number_format($product->harga, 0, ',', '.') }}</small>
                                </a>
                            @endforeach
                        </div>
                        @if($umkm->products->count() > 5)
                            <a href="{{ route('admin.products.index', ['umkm' => $umkm->id]) }}" class="btn btn-sm btn-outline-primary w-100 mt-2">
                                Lihat Semua Produk
                            </a>
                        @endif
                    @else
                        <p class="small text-muted mb-0">Belum ada produk</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Foto KTP dan Foto Tempat Usaha -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📷 Foto KTP</h6>
                </div>
                <div class="card-body">
                    @if($umkm->foto_ktp)
                        <img src="{{ asset('storage/' . $umkm->foto_ktp) }}" alt="Foto KTP" class="img-fluid rounded" style="max-height: 400px;">
                    @else
                        <p class="text-muted">Belum ada foto KTP</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📷 Foto Tempat Usaha</h6>
                </div>
                <div class="card-body">
                    @if($umkm->foto_tempat)
                        <img src="{{ asset('storage/' . $umkm->foto_tempat) }}" alt="Foto Tempat Usaha" class="img-fluid rounded" style="max-height: 400px;">
                    @else
                        <p class="text-muted">Belum ada foto tempat usaha</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.umkm.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>
@endsection
