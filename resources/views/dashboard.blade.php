@extends('layouts.admin')

@section('title', 'Dashboard - Vinaty Inventory System')
@section('header-title', 'Ringkasan Sistem')
@section('header-subtitle', 'Lihat semua ringkasan sistem & monitoring stok aktif di sini')

@section('content')
<div class="row g-4 animate-fade-in mb-4">
    <!-- Stat Cards -->
    <!-- Card 1: Total Produk (Neon Lime Highlight) -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-custom stat-card-highlight h-100 p-4 border-0 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 38px; height: 38px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <i class="fa-solid fa-cube" style="font-size: 14px;"></i>
                    </div>
                    <div class="card-arrow-btn">
                        <i class="fa-solid fa-arrow-trend-up" style="font-size: 11px;"></i>
                    </div>
                </div>
                <h2 class="fw-bold m-0 mb-1" style="font-family: var(--font-outfit); font-size: 32px; letter-spacing: -1px;">{{ $totalProducts }}</h2>
            </div>
            <span class="small fw-semibold opacity-75">Total Master Produk</span>
        </div>
    </div>

    <!-- Card 2: Stok Tersedia -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-custom stat-card-white h-100 p-4 border-0 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 38px; height: 38px; border: 1px solid rgba(0,0,0,0.05);">
                        <i class="fa-solid fa-boxes-stacked" style="font-size: 14px;"></i>
                    </div>
                    <div class="card-arrow-btn">
                        <i class="fa-solid fa-arrow-trend-up" style="font-size: 11px;"></i>
                    </div>
                </div>
                <h2 class="fw-bold m-0 mb-1" style="font-family: var(--font-outfit); font-size: 32px; letter-spacing: -1px;">{{ $totalAvailableStock }}</h2>
            </div>
            <span class="small text-muted fw-medium">Unit Stok Tersedia</span>
        </div>
    </div>

    <!-- Card 3: Batch Mendekati Kadaluarsa -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-custom stat-card-white h-100 p-4 border-0 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 38px; height: 38px; border: 1px solid rgba(0,0,0,0.05);">
                        <i class="fa-solid fa-clock" style="font-size: 14px;"></i>
                    </div>
                    <div class="card-arrow-btn">
                        <i class="fa-solid fa-arrow-trend-up" style="font-size: 11px;"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <h2 class="fw-bold m-0" style="font-family: var(--font-outfit); font-size: 32px; letter-spacing: -1px;">{{ $batchesMendekati }}</h2>
                    <span class="badge bg-warning bg-opacity-10 text-warning ms-2 px-2 py-1 rounded-pill" style="font-size: 10px; font-weight: 600;">Batch</span>
                </div>
            </div>
            <span class="small text-muted fw-medium">Mendekati Kadaluarsa</span>
        </div>
    </div>

    <!-- Card 4: Batch Telah Kadaluarsa -->
    <div class="col-sm-6 col-xl-3">
        <div class="card card-custom stat-card-white h-100 p-4 border-0 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-dark" style="width: 38px; height: 38px; border: 1px solid rgba(0,0,0,0.05);">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size: 14px;"></i>
                    </div>
                    <div class="card-arrow-btn">
                        <i class="fa-solid fa-arrow-trend-up" style="font-size: 11px;"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <h2 class="fw-bold text-danger m-0" style="font-family: var(--font-outfit); font-size: 32px; letter-spacing: -1px;">{{ $batchesKadaluarsa }}</h2>
                    <span class="badge bg-danger bg-opacity-10 text-danger ms-2 px-2 py-1 rounded-pill" style="font-size: 10px; font-weight: 600;">Batch</span>
                </div>
            </div>
            <span class="small text-muted fw-medium">Telah Kadaluarsa</span>
        </div>
    </div>
</div>

<div class="row g-4 animate-fade-in mb-4">
    <!-- Chart Section: Statistics -->
    <div class="col-lg-8">
        <div class="card card-custom border-0 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold m-0 text-dark" style="font-family: var(--font-outfit);"><i class="fa-solid fa-chart-simple me-2 text-primary"></i> Statistik Inventaris</h5>
                    <p class="text-muted small m-0" style="font-size: 12px;">Perbandingan stok masuk dan stok keluar sistem</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="badge-pill-filter">Bulanan</button>
                    <button class="badge-pill-filter"><i class="fa-solid fa-sliders"></i></button>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-6 col-sm-4 border-end">
                    <span class="small text-muted d-block">Total Masuk</span>
                    <h4 class="fw-bold text-dark m-0" style="font-family: var(--font-outfit);">
                        {{ $categoriesChart->sum('total_masuk') }} <span class="small fw-normal text-muted" style="font-size: 12px;">Unit</span>
                    </h4>
                    <span class="small text-success fw-bold" style="font-size: 11px;"><i class="fa-solid fa-arrow-up me-1"></i>4.1% vs Tahun Lalu</span>
                </div>
                <div class="col-6 col-sm-4">
                    <span class="small text-muted d-block">Total Keluar</span>
                    <h4 class="fw-bold text-dark m-0" style="font-family: var(--font-outfit);">
                        {{ max(0, $categoriesChart->sum('total_masuk') - $totalAvailableStock) }} <span class="small fw-normal text-muted" style="font-size: 12px;">Unit</span>
                    </h4>
                    <span class="small text-success fw-bold" style="font-size: 11px;"><i class="fa-solid fa-arrow-up me-1"></i>2% vs Tahun Lalu</span>
                </div>
            </div>

            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="inventoryDualChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Category / Target bubble visualization -->
    <div class="col-lg-4">
        <div class="card card-custom border-0 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0 text-dark" style="font-family: var(--font-outfit);"><i class="fa-solid fa-tags me-2 text-success"></i> Kategori Utama</h5>
                <button class="badge-pill-filter py-1" style="font-size: 11px;">Bulan Ini <i class="fa-solid fa-chevron-down ms-1" style="font-size: 8px;"></i></button>
            </div>

            <!-- Double Overlapping Circles Visualization -->
            <div class="bubble-visual-container">
                @php
                    $sortedCategories = collect($categoriesChart)->sortByDesc('stok_tersedia')->values();
                    $cat1Name = $sortedCategories->get(0)->nama_kategori ?? 'Nugget';
                    $cat1Val = $sortedCategories->get(0)->stok_tersedia ?? 0;
                    $cat2Name = $sortedCategories->get(1)->nama_kategori ?? 'Sosis';
                    $cat2Val = $sortedCategories->get(1)->stok_tersedia ?? 0;
                    $cat3Name = $sortedCategories->get(2)->nama_kategori ?? 'Bakso';
                    $cat3Val = $sortedCategories->get(2)->stok_tersedia ?? 0;
                    
                    $totalChartStok = max(1, $sortedCategories->sum('stok_tersedia'));
                    $p1 = round(($cat1Val / $totalChartStok) * 100);
                    $p2 = round(($cat2Val / $totalChartStok) * 100);
                    $p3 = round(($cat3Val / $totalChartStok) * 100);
                @endphp
                <div class="bubble-item bubble-lime">
                    <span class="bubble-value">{{ $p1 }}%</span>
                    <span class="bubble-label text-truncate" style="max-width: 80px;">{{ $cat1Name }}</span>
                </div>
                <div class="bubble-item bubble-blue">
                    <span class="bubble-value">{{ $p2 }}%</span>
                    <span class="bubble-label text-truncate" style="max-width: 70px;">{{ $cat2Name }}</span>
                </div>
                <div class="bubble-item bubble-gray">
                    <span class="bubble-value" style="font-size: 14px;">{{ $p3 }}%</span>
                    <span class="bubble-label text-truncate" style="max-width: 60px;">{{ $cat3Name }}</span>
                </div>
            </div>

            <!-- Target Progress Bars -->
            <div class="target-list-wrapper mt-3">
                <div class="target-list-item">
                    <div class="target-list-label">
                        <span>{{ $cat1Name }}</span>
                        <span>{{ $cat1Val }} Pcs ({{ $p1 }}%)</span>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-lime" style="width: {{ $p1 }}%"></div>
                    </div>
                </div>
                
                <div class="target-list-item">
                    <div class="target-list-label">
                        <span>{{ $cat2Name }}</span>
                        <span>{{ $cat2Val }} Pcs ({{ $p2 }}%)</span>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-blue" style="width: {{ $p2 }}%"></div>
                    </div>
                </div>

                <div class="target-list-item">
                    <div class="target-list-label">
                        <span>{{ $cat3Name }}</span>
                        <span>{{ $cat3Val }} Pcs ({{ $p3 }}%)</span>
                    </div>
                    <div class="progress-custom">
                        <div class="progress-bar-gray" style="width: {{ $p3 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 animate-fade-in">
    <!-- Expiration alerts (FEFO) table -->
    <div class="col-lg-8">
        <div class="card card-custom border-0 mb-4">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold m-0 text-dark" style="font-family: var(--font-outfit);"><i class="fa-solid fa-hourglass-half me-2 text-warning"></i> Peringatan Kadaluarsa Terdekat</h5>
                    <p class="text-muted small m-0" style="font-size: 12px;">Sisa masa simpan batch produk terdekat</p>
                </div>
                <a href="{{ route('monitoring.index') }}" class="btn btn-sm btn-light border rounded-3 fw-bold px-3 py-1.5" style="font-size: 12px; font-family: var(--font-outfit);">Lihat Semua</a>
            </div>
            
            <div class="card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover m-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Batch ID</th>
                                <th class="text-end">Qty Tersedia</th>
                                <th class="text-center">Kadaluarsa</th>
                                <th class="text-center">Sisa Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expiringSoon as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                            <span class="small text-muted" style="font-size: 11px;">Merek: {{ $item->produk->merek ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center fw-semibold text-secondary">#{{ $item->id_masuk }}</td>
                                    <td class="text-end fw-bold text-dark">{{ $item->sisa_stok }} {{ $item->produk->satuan ?? '' }}</td>
                                    <td class="text-center text-muted" style="font-size: 13px;">{{ date('d M Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                                    <td class="text-center">
                                        @if($item->sisa_hari <= 0)
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">Expired</span>
                                        @elseif($item->sisa_hari <= 7)
                                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">{{ $item->sisa_hari }} hari</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">{{ $item->sisa_hari }} hari</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-hourglass-empty mb-2 fs-4 d-block text-secondary"></i>
                                        <span>Semua stok batch aman. Tidak ada peringatan aktif.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification panel -->
    <div class="col-lg-4">
        <div class="card card-custom border-0">
            <div class="card-header-custom">
                <h5 class="fw-bold m-0 text-dark" style="font-family: var(--font-outfit);"><i class="fa-solid fa-bell me-2 text-primary"></i> Notifikasi</h5>
                <p class="text-muted small m-0" style="font-size: 12px;">Aktivitas notifikasi peringatan sistem</p>
            </div>
            
            <div class="card-body-custom p-0" style="max-height: 380px; overflow-y: auto;">
                <div class="d-flex flex-column">
                    @forelse($recentNotifications as $notif)
                        <div class="notif-feed-item d-flex gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ str_contains($notif->pesan, 'telah kadaluarsa') ? 'bg-danger bg-opacity-10 text-danger' : 'bg-warning bg-opacity-10 text-warning' }}" style="width: 32px; height: 32px;">
                                @if(str_contains($notif->pesan, 'telah kadaluarsa'))
                                    <i class="fa-solid fa-circle-exclamation" style="font-size: 12px;"></i>
                                @else
                                    <i class="fa-solid fa-clock" style="font-size: 12px;"></i>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-1 text-dark small fw-medium" style="line-height: 1.4; font-size: 13px;">{{ $notif->pesan }}</p>
                                <span class="small text-muted" style="font-size: 11px;"><i class="fa-regular fa-clock me-1"></i> {{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 text-center text-muted">
                            <i class="fa-regular fa-bell-slash mb-2 fs-3 d-block text-secondary"></i>
                            <span class="small">Tidak ada notifikasi sistem</span>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 border-top text-center bg-light bg-opacity-25 rounded-bottom-4">
                <a href="{{ route('notifikasi.index') }}" class="btn btn-sm btn-link text-primary fw-bold text-decoration-none" style="font-size: 12px;">Buka Semua Notifikasi</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('inventoryDualChart');
        if (ctx) {
            const chartData = @json($categoriesChart);
            
            const labels = chartData.map(item => item.nama_kategori);
            const masukValues = chartData.map(item => item.total_masuk);
            const keluarValues = chartData.map(item => {
                const stock = item.stok_tersedia || 0;
                return Math.max(0, item.total_masuk - stock);
            });
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Stok Masuk',
                            data: masukValues,
                            backgroundColor: '#cbf200', // Neon Lime
                            borderRadius: 6,
                            borderWidth: 0,
                            barThickness: 12,
                            maxBarThickness: 16
                        },
                        {
                            label: 'Stok Keluar',
                            data: keluarValues,
                            backgroundColor: '#508bfc', // Sky Blue
                            borderRadius: 6,
                            borderWidth: 0,
                            barThickness: 12,
                            maxBarThickness: 16
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                boxWidth: 10,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    family: 'Outfit',
                                    weight: 'bold',
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f1012',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            cornerRadius: 8,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.dataset.label}: ${context.raw} Unit`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#eef1f5',
                                drawTicks: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#8a8f99',
                                font: {
                                    family: 'Inter',
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            },
                            ticks: {
                                color: '#0f1012',
                                font: {
                                    family: 'Outfit',
                                    weight: 'bold',
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
