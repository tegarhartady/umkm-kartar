@extends('layouts.dashboard')

@section('content')
<div class="laporan-container">
    <div class="mb-4">
        <h1 class="page-title">Edit Transaksi</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validasi Gagal:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info mb-4">
                    <i class="bi bi-info-circle"></i>
                    <strong>Kode Transaksi:</strong> {{ $transaction->transaction_code }}<br>
                    <strong>Produk:</strong> {{ $transaction->product->nama_produk ?? 'Produk Dihapus' }}<br>
                    <strong>Jumlah:</strong> {{ $transaction->quantity }} unit @ Rp{{ number_format($transaction->price, 0, ',', '.') }}<br>
                    <strong>Total:</strong> Rp{{ number_format($transaction->total_price, 0, ',', '.') }}
                </div>

                <h5 class="mb-3">Status Transaksi</h5>

                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>
                            Menunggu Pembayaran
                        </option>
                        <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>
                            Selesai
                        </option>
                        <option value="cancelled" {{ $transaction->status === 'cancelled' ? 'selected' : '' }}>
                            Dibatalkan
                        </option>
                        <option value="failed" {{ $transaction->status === 'failed' ? 'selected' : '' }}>
                            Gagal
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Metode Pembayaran</label>
                    <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                        <option value="">-- Pilih Metode --</option>
                        <option value="transfer" {{ $transaction->payment_method === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="ewallet" {{ $transaction->payment_method === 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                        <option value="cod" {{ $transaction->payment_method === 'cod' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                    </select>
                    @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Informasi Pembeli</h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="buyer_name" class="form-label">Nama Pembeli <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('buyer_name') is-invalid @enderror" 
                               id="buyer_name" name="buyer_name" value="{{ old('buyer_name', $transaction->buyer_name) }}" required>
                        @error('buyer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="buyer_phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('buyer_phone') is-invalid @enderror" 
                               id="buyer_phone" name="buyer_phone" value="{{ old('buyer_phone', $transaction->buyer_phone) }}" required>
                        @error('buyer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="buyer_address" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('buyer_address') is-invalid @enderror" 
                              id="buyer_address" name="buyer_address" rows="3" required>{{ old('buyer_address', $transaction->buyer_address) }}</textarea>
                    @error('buyer_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="buyer_city" class="form-label">Kota <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('buyer_city') is-invalid @enderror" 
                               id="buyer_city" name="buyer_city" value="{{ old('buyer_city', $transaction->buyer_city) }}" required>
                        @error('buyer_city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="buyer_postal_code" class="form-label">Kode Pos</label>
                        <input type="text" class="form-control @error('buyer_postal_code') is-invalid @enderror" 
                               id="buyer_postal_code" name="buyer_postal_code" value="{{ old('buyer_postal_code', $transaction->buyer_postal_code) }}">
                        @error('buyer_postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Catatan</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="3">{{ old('notes', $transaction->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Perbarui Transaksi
                    </button>
                    <a href="{{ route('admin.transactions.show', $transaction->id) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .laporan-container {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
    }

    .card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 500;
        color: #495057;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .alert-info {
        background-color: #e7f3ff;
        border-color: #b3d9ff;
        color: #004085;
    }

    @media (max-width: 768px) {
        .laporan-container {
            padding: 12px;
        }

        .page-title {
            font-size: 1.25rem;
        }
    }
</style>
@endsection
