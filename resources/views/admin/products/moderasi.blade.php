@extends('layouts.dashboard')

@section('title', 'Moderasi Produk')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}">Dashboard</a></li>
        <li class="breadcrumb-item">Produk</li>
        <li class="breadcrumb-item active">Moderasi</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Moderasi Produk</h1>
                    <p class="text-muted mb-0">Tinjau dan kelola status publikasi produk UMKM</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! nl2br(e(session('success'))) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Daftar Produk</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Produk</th>
                            <th>UMKM</th>
                            <th>Harga</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 50px; height: 50px; overflow: hidden; border-radius: 8px;">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-100 h-100 object-fit-cover" alt="">
                                        @else
                                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                                <i class="bi bi-image small"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $product->nama_produk }}</div>
                                        <small class="text-muted">{{ $product->kategori }}</small>
                                        @if($product->is_best_seller)
                                            <span class="badge bg-warning text-dark ms-1" style="font-size: 0.65rem;">Best Seller</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $product->umkm->nama_toko }}</div>
                                <small class="text-muted">{{ $product->umkm->desa }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                                <small class="text-muted">Stok: {{ $product->stok }}</small>
                            </td>
                            <td>
                                <div class="text-warning small">
                                    @php $rating = $product->reviews_avg_rating ?? 0; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}"></i>
                                    @endfor
                                    <span class="text-muted ms-1">({{ $product->reviews_count }})</span>
                                </div>
                            </td>
                            <td>
                                @if($product->status == 'aktif')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">Aktif</span>
                                @elseif($product->status == 'nonaktif')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">Nonaktif</span>
                                @else
                                    <span class="badge bg-secondary">{{ $product->status }}</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($product->status != 'aktif')
                                        <form action="{{ route('admin.products.updateStatus', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="aktif">
                                            <button type="submit" class="btn btn-sm btn-success" title="Aktifkan">
                                                <i class="bi bi-check-circle"></i> Aktifkan
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.products.updateStatus', $product->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="nonaktif">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Nonaktifkan">
                                                <i class="bi bi-x-circle"></i> Nonaktifkan
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                Belum ada produk untuk dimoderasi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($products->hasPages())
        <div class="card-footer bg-white border-top-0 py-4">
            <div class="d-flex justify-content-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo;</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">&laquo;</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                            @if ($page == $products->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">&raquo;</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">&raquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .object-fit-cover {
        object-fit: cover;
    }
    .pagination {
        gap: 0.25rem;
    }
    .page-link {
        color: #001f5c;
        border-radius: 8px !important;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .page-link:hover {
        background-color: #001f5c;
        color: white;
        border-color: #001f5c;
    }
    .page-item.active .page-link {
        background-color: #001f5c;
        border-color: #001f5c;
        color: white;
    }
    .page-item.disabled .page-link {
        color: #adb5bd;
        background-color: #f8f9fa;
    }
</style>
@endpush
@endsection
