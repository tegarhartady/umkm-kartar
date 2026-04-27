@extends('layouts.dashboard')

@section('title', 'Laporan UMKM')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Laporan</li>
        </ol>
    </nav>
@endsection

@push('styles')
<style>
/* ========== Modern Dashboard Styling ========== */
:root {
    --primary: #667eea;
    --primary-dark: #764ba2;
    --success: #48bb78;
    --warning: #ffa500;
    --danger: #dc3545;
    --info: #4299e1;
    --light: #f7fafc;
    --border: #e2e8f0;
}

.stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.stat-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    flex-shrink: 0;
}

.stat-icon.primary {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
}

.stat-icon.success {
    background: linear-gradient(135deg, var(--success), #38a169);
}

.stat-icon.warning {
    background: linear-gradient(135deg, var(--warning), #ff8c00);
}

.stat-icon.info {
    background: linear-gradient(135deg, var(--info), #3182ce);
}

.stat-content h4 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #1a202c;
}

.stat-content p {
    font-size: 0.85rem;
    color: #718096;
    margin-bottom: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Grid Layout */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

/* Charts & Tables */
.chart-card {
    background: white;
    border-radius: 12px;
    border: 1px solid var(--border);
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.chart-header h5 {
    font-weight: 600;
    color: #1a202c;
    margin-bottom: 0;
}

.table-card {
    background: white;
    border-radius: 12px;
    border: 1px solid var(--border);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.table-card .table {
    margin-bottom: 0;
}

.table-card thead th {
    background: var(--light);
    border: none;
    font-weight: 600;
    font-size: 0.85rem;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 1.5rem;
}

.table-card tbody td {
    padding: 1rem 1.5rem;
    border-color: var(--border);
    vertical-align: middle;
}

.table-card tbody tr:hover {
    background: var(--light);
}

/* Badge & Status */
.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* Responsive */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        gap: 1rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    
    .stat-content h4 {
        font-size: 1.25rem;
    }
}
</style>
@endpush

@section('content')

<div class="laporan-wrapper">
    <!-- Header Stats -->
    <div class="stats-grid">
        <div class="stat-card" data-aos="fade-up">
            <div class="stat-icon primary">
                <i class="bi bi-shop"></i>
            </div>
            <div class="stat-content">
                <h4>{{ $totalUmkm ?? 0 }}</h4>
                <p>Total UMKM</p>
            </div>
        </div>

        <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-icon success">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-content">
                <h4>{{ $totalProducts ?? 0 }}</h4>
                <p>Total Produk</p>
            </div>
        </div>

        <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-icon warning">
                <i class="bi bi-cart"></i>
            </div>
            <div class="stat-content">
                <h4>{{ $totalTransactions ?? 0 }}</h4>
                <p>Total Transaksi</p>
            </div>
        </div>

        <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-icon info">
                <i class="bi bi-cash"></i>
            </div>
            <div class="stat-content">
                <h4>Rp 0</h4>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="chart-card" data-aos="fade-up">
                <div class="chart-header">
                    <h5>UMKM per Desa</h5>
                    <i class="bi bi-three-dots-vertical text-muted"></i>
                </div>
                <canvas id="desaChart"></canvas>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="chart-card" data-aos="fade-up" data-aos-delay="100">
                <div class="chart-header">
                    <h5>Produk per Kategori</h5>
                    <i class="bi bi-three-dots-vertical text-muted"></i>
                </div>
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="table-card" data-aos="fade-up">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border);">
                    <h5 style="margin-bottom: 0;">UMKM Pendaftar (6 Bulan Terakhir)</h5>
                </div>
                @if($dataPerDesa && $dataPerDesa->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-end">Jumlah Pendaftar</th>
                                    <th class="text-end">Perubahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPerDesa as $data)
                                <tr>
                                    <td>{{ $data->desa ?? '-' }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ $data->total ?? 0 }}</span>
                                    </td>
                                    <td class="text-end text-success">+5%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox"></i> Belum ada data
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="table-card" data-aos="fade-up" data-aos-delay="100">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border);">
                    <h5 style="margin-bottom: 0;">Top UMKM</h5>
                </div>
                <div style="padding: 1.5rem;">
                    @if($topUmkm && $topUmkm->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach($topUmkm as $index => $umkm)
                            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: var(--light); border-radius: 8px;">
                                <div style="width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                    {{ $index + 1 }}
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <p style="font-weight: 600; margin-bottom: 0.25rem; color: #1a202c; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $umkm->nama_toko }}</p>
                                    <p style="font-size: 0.85rem; color: #718096; margin-bottom: 0;">{{ $umkm->pemilik }}</p>
                                </div>
                                <span class="badge bg-primary">{{ $umkm->products_count ?? 0 }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox"></i> Belum ada data
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk UMKM per Desa
    const desaData = {
        @if($dataPerDesa && $dataPerDesa->count() > 0)
            labels: [
                @foreach($dataPerDesa as $data)
                    '{{ $data->desa ?? "Desa" }}',
                @endforeach
            ],
            values: [
                @foreach($dataPerDesa as $data)
                    {{ $data->total ?? 0 }},
                @endforeach
            ]
        @else
            labels: ['Tidak ada data'],
            values: [0]
        @endif
    };

    // Chart UMKM per Desa
    const desaCtx = document.getElementById('desaChart').getContext('2d');
    new Chart(desaCtx, {
        type: 'bar',
        data: {
            labels: desaData.labels,
            datasets: [{
                label: 'Jumlah UMKM',
                data: desaData.values,
                backgroundColor: [
                    'rgba(102, 126, 234, 0.7)',
                    'rgba(72, 187, 120, 0.7)',
                    'rgba(255, 165, 0, 0.7)',
                    'rgba(66, 153, 225, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                ],
                borderColor: [
                    'rgb(102, 126, 234)',
                    'rgb(72, 187, 120)',
                    'rgb(255, 165, 0)',
                    'rgb(66, 153, 225)',
                    'rgb(220, 53, 69)',
                    'rgb(153, 102, 255)',
                ],
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: '#e2e8f0',
                    },
                    ticks: {
                        color: '#718096',
                    }
                },
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#718096',
                    }
                }
            }
        }
    });

    // Data untuk Produk per Kategori (menggunakan dataPerDesa sebagai placeholder)
    const kategoriData = {
        @if($dataPerDesa && $dataPerDesa->count() > 0)
            labels: [
                @foreach($dataPerDesa as $data)
                    '{{ $data->desa ?? "Kategori" }}',
                @endforeach
            ],
            values: [
                @foreach($dataPerDesa as $data)
                    {{ $data->total ?? 0 }},
                @endforeach
            ]
        @else
            labels: ['Tidak ada data'],
            values: [0]
        @endif
    };

    // Chart Produk per Kategori
    const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
    new Chart(kategoriCtx, {
        type: 'doughnut',
        data: {
            labels: kategoriData.labels,
            datasets: [{
                label: 'Jumlah Produk',
                data: kategoriData.values,
                backgroundColor: [
                    'rgba(102, 126, 234, 0.7)',
                    'rgba(72, 187, 120, 0.7)',
                    'rgba(255, 165, 0, 0.7)',
                    'rgba(66, 153, 225, 0.7)',
                    'rgba(220, 53, 69, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                ],
                borderColor: [
                    'rgb(102, 126, 234)',
                    'rgb(72, 187, 120)',
                    'rgb(255, 165, 0)',
                    'rgb(66, 153, 225)',
                    'rgb(220, 53, 69)',
                    'rgb(153, 102, 255)',
                ],
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        color: '#718096',
                    }
                }
            }
        }
    });
});
</script>

@endsection