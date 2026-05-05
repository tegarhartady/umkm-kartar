@extends('layouts.dashboard')

@section('title', 'Detail Produk')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
        <li class="breadcrumb-item active">{{ $product->nama_produk }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Informasi Produk</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->nama_produk }}" class="img-fluid rounded shadow-sm">
                            @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                <div class="text-center">
                                    <i class="bi bi-image" style="font-size: 4rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Tidak ada gambar</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3 class="mb-2">{{ $product->nama_produk }}</h3>
                            <p class="text-muted mb-3">{{ $product->kategori }}</p>

                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">SKU</th>
                                    <td>{{ $product->sku ?? 'Produk #' . $product->id }}</td>
                                </tr>
                                <tr>
                                    <th>UMKM</th>
                                    <td><a href="{{ route('admin.umkm.show', $product->umkm_id) }}" class="text-decoration-none">{{ $product->umkm->nama_toko }}</a></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($product->status == 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                        @elseif($product->status == 'published')
                                        <span class="badge bg-success">Terbit</span>
                                        @elseif($product->status == 'pending')
                                        <span class="badge bg-info">Menunggu Review</span>
                                        @else
                                        <span class="badge bg-secondary">{{ $product->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Harga</th>
                                    <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Stok</th>
                                    <td>{{ $product->stok }} {{ $product->satuan ?? 'pcs' }}</td>
                                </tr>
                                <tr>
                                    <th>Dibuat</th>
                                    <td>{{ $product->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Diperbarui</th>
                                    <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-4">
                        <h5 class="mb-3">Deskripsi</h5>
                        <p class="text-muted">{{ $product->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menghapus?')">
                            <i class="bi bi-trash me-2"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Informasi UMKM</h5>
                </div>
                <div class="card-body">
                    <h6 class="mb-2">{{ $product->umkm->nama_toko }}</h6>
                    <p class="text-muted small mb-2">{{ $product->umkm->alamat }}</p>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-telephone me-1"></i>{{ $product->umkm->phone }}
                    </p>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-envelope me-1"></i>{{ $product->umkm->email }}
                    </p>
                    <a href="{{ route('admin.umkm.show', $product->umkm->id) }}" class="btn btn-sm btn-outline-primary w-100">
                        Lihat Detail UMKM
                    </a>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary mt-3">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>
@endsection