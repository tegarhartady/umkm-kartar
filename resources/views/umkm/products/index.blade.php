@extends('layouts.dashboard-umkm')

@section('title', 'Daftar Produk - ' . Auth::guard('umkm')->user()->nama_toko)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('umkm.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Daftar Produk</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Daftar Produk</h1>
                    <p class="text-muted mb-0">Kelola semua produk Anda</p>
                </div>
                @if(Auth::guard('umkm')->user()->status === 'disetujui')
                    <a href="{{ route('umkm.products.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-box me-2"></i>Produk Anda</h5>
                </div>
                <div class="card-body p-0">
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td><span class="badge bg-light text-dark">{{ $loop->iteration }}</span></td>
                                            <td>
                                                <div class="fw-bold">{{ $product->nama_produk }}</div>
                                                <small class="text-muted">{{ Str::limit($product->deskripsi, 50) }}</small>
                                            </td>
                                            <td>{{ $product->kategori }}</td>
                                            <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $product->stok > 0 ? 'success' : 'danger' }}">
                                                    {{ $product->stok }} {{ $product->satuan }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($product->status === 'aktif')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('umkm.products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('umkm.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="d-flex justify-content-center py-3">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Belum ada produk</p>
                            @if(Auth::guard('umkm')->user()->status === 'disetujui')
                                <a href="{{ route('umkm.products.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                                </a>
                            @else
                                <p class="text-danger mt-2">Akun Anda belum disetujui. Hubungi admin untuk persetujuan.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
