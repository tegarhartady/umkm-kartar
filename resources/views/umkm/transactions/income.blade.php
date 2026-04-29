@extends('layouts.dashboard-umkm')

@section('title', 'Laporan Pembukuan - ' . Auth::guard('umkm')->user()->nama_toko)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('umkm.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pembukuan</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h3 mb-1 fw-bold">Pembukuan / Pendapatan</h1>
                    <p class="text-muted mb-0">Pantau arus kas dan pendapatan dari produk Anda</p>
                </div>
                <div>
                    <form action="{{ route('umkm.transactions.income') }}" method="GET" class="d-flex gap-2">
                        <select name="month" class="form-select form-select-sm" style="width: auto;">
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                        <select name="year" class="form-select form-select-sm" style="width: auto;">
                            @for($i=date('Y')-2; $i<=date('Y'); $i++)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">Total Pendapatan (Bulan Ini)</p>
                            <h2 class="mb-0 fw-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
                        </div>
                        <i class="bi bi-wallet2" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Pesanan Selesai</p>
                            <h2 class="mb-0 fw-bold text-dark">{{ $totalTransactions }}</h2>
                        </div>
                        <i class="bi bi-bag-check text-success" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Grafik Pendapatan Harian</h5>
                </div>
                <div class="card-body">
                    <canvas id="incomeChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Row -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Rincian Transaksi Selesai</h5>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal Selesai</th>
                                        <th>Kode Transaksi</th>
                                        <th>Produk</th>
                                        <th>Harga Satuan</th>
                                        <th>Qty</th>
                                        <th>Total Pemasukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $t)
                                        <tr>
                                            <td>{{ $t->created_at->format('d M Y, H:i') }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $t->transaction_code }}</span></td>
                                            <td>{{ $t->product->nama_produk ?? 'Produk Dihapus' }}</td>
                                            <td>Rp {{ number_format($t->price, 0, ',', '.') }}</td>
                                            <td>{{ $t->quantity }}</td>
                                            <td class="fw-bold text-success">+ Rp {{ number_format($t->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-wallet2" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Tidak ada pendapatan di bulan ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const labels = {!! json_encode(array_keys($dailyIncome)) !!};
        const data = {!! json_encode(array_values($dailyIncome)) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
