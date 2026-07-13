@extends('layouts.admin')

@section('title', 'Laporan Inventory - Vinaty Inventory System')
@section('header-title', 'Laporan Inventory')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
@endsection

@section('content')
<div class="row g-4 animate-fade-in">
    <!-- Filter Card -->
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom">
                <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);"><i class="fa-solid fa-filter me-2 text-primary"></i> Filter Laporan</h5>
                <p class="text-muted small m-0">Tentukan tipe laporan dan rentang waktu data yang ingin ditampilkan</p>
            </div>
            <div class="card-body-custom">
                <form action="{{ route('laporan.index') }}" method="GET" id="filterForm" class="row g-3">
                    <!-- Tipe Laporan -->
                    <div class="col-md-4">
                        <label for="tipe_laporan" class="form-label fw-semibold">Tipe Laporan</label>
                        <select name="tipe_laporan" id="tipe_laporan" class="form-select rounded-3 py-2" required>
                            <option value="stok_masuk" {{ $tipeLaporan === 'stok_masuk' ? 'selected' : '' }}>Laporan Stok Masuk</option>
                            <option value="stok_keluar" {{ $tipeLaporan === 'stok_keluar' ? 'selected' : '' }}>Laporan Stok Keluar</option>
                            <option value="stok_tersedia" {{ $tipeLaporan === 'stok_tersedia' ? 'selected' : '' }}>Laporan Stok Tersedia</option>
                            <option value="mendekati_kadaluarsa" {{ $tipeLaporan === 'mendekati_kadaluarsa' ? 'selected' : '' }}>Laporan Produk Mendekati Kadaluarsa</option>
                            <option value="kadaluarsa" {{ $tipeLaporan === 'kadaluarsa' ? 'selected' : '' }}>Laporan Produk Kadaluarsa</option>
                        </select>
                    </div>

                    <!-- Periode -->
                    <div class="col-md-3">
                        <label for="periode" class="form-label fw-semibold">Periode</label>
                        <select name="periode" id="periode" class="form-select rounded-3 py-2" required>
                            <option value="harian" {{ $periode === 'harian' ? 'selected' : '' }}>Harian</option>
                            <option value="bulanan" {{ $periode === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="tahunan" {{ $periode === 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            <option value="custom" {{ $periode === 'custom' ? 'selected' : '' }}>Kustom Rentang Tanggal</option>
                            <!-- Only allow 'Semua' for available stock or states -->
                            <option value="semua" {{ $periode === 'semua' ? 'selected' : '' }}>Semua Waktu</option>
                        </select>
                    </div>

                    <!-- Harian Filter -->
                    <div class="col-md-3 filter-input" id="harian-container" style="display: none;">
                        <label for="tanggal" class="form-label fw-semibold">Pilih Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control rounded-3 py-2" value="{{ $tanggal }}">
                    </div>

                    <!-- Bulanan Filter -->
                    <div class="col-md-5 filter-input" id="bulanan-container" style="display: none;">
                        <div class="row g-2">
                            <div class="col-7">
                                <label for="bulan" class="form-label fw-semibold">Bulan</label>
                                <select name="bulan" id="bulan" class="form-select rounded-3 py-2">
                                    @for($m=1; $m<=12; $m++)
                                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-5">
                                <label for="tahun_bulan" class="form-label fw-semibold">Tahun</label>
                                <select name="tahun" id="tahun_bulan" class="form-select rounded-3 py-2">
                                    @for($y=date('Y'); $y>=date('Y')-5; $y--)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Tahunan Filter -->
                    <div class="col-md-3 filter-input" id="tahunan-container" style="display: none;">
                        <label for="tahun" class="form-label fw-semibold">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select rounded-3 py-2">
                            @for($y=date('Y'); $y>=date('Y')-5; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Custom Rentang Tanggal Filter -->
                    <div class="col-md-5 filter-input" id="custom-container" style="display: none;">
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="start_date" class="form-label fw-semibold">Mulai Tanggal</label>
                                <input type="date" name="start_date" id="start_date" class="form-control rounded-3 py-2" value="{{ $startDate }}">
                            </div>
                            <div class="col-6">
                                <label for="end_date" class="form-label fw-semibold">Sampai Tanggal</label>
                                <input type="date" name="end_date" id="end_date" class="form-control rounded-3 py-2" value="{{ $endDate }}">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary-custom px-4"><i class="fa-solid fa-magnifying-glass me-2"></i> Tampilkan Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Results Card -->
    @if($hasFilters)
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header-custom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    <div>
                        <h5 class="card-title fw-bold m-0 text-dark" style="font-family: var(--font-outfit);">
                            @if($tipeLaporan === 'stok_masuk') Laporan Pemasukan (Stok Masuk)
                            @elseif($tipeLaporan === 'stok_keluar') Laporan Pengeluaran (Stok Keluar)
                            @elseif($tipeLaporan === 'stok_tersedia') Laporan Persediaan (Stok Tersedia)
                            @elseif($tipeLaporan === 'mendekati_kadaluarsa') Laporan Batch Mendekati Kadaluarsa
                            @elseif($tipeLaporan === 'kadaluarsa') Laporan Batch Kadaluarsa
                            @endif
                        </h5>
                        <p class="text-muted small m-0">{{ $periodeText }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <script>
                        function downloadFile(el, url, filename) {
                            var labelEl = el.querySelector('span');
                            var originalText = labelEl.textContent;
                            labelEl.textContent = 'Mengunduh...';
                            el.style.pointerEvents = 'none';
                            el.style.opacity = '0.7';

                            fetch(url, { credentials: 'same-origin' })
                                .then(function(r) {
                                    if (!r.ok) throw new Error('Server error: ' + r.status);
                                    return r.blob();
                                })
                                .then(function(blob) {
                                    // Try File System Access API first (opens native Save As dialog)
                                    if (window.showSaveFilePicker) {
                                        var ext = filename.split('.').pop().toLowerCase();
                                        var mimeTypes = {
                                            'xls': 'application/vnd.ms-excel',
                                            'pdf': 'application/pdf'
                                        };
                                        return window.showSaveFilePicker({
                                            suggestedName: filename,
                                            types: [{
                                                description: ext.toUpperCase() + ' File',
                                                accept: {}
                                            }]
                                        }).then(function(handle) {
                                            return handle.createWritable();
                                        }).then(function(writable) {
                                            return writable.write(blob).then(function() {
                                                return writable.close();
                                            });
                                        });
                                    }
                                    // Fallback: blob URL download
                                    var a = document.createElement('a');
                                    a.href = URL.createObjectURL(blob);
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                    document.body.removeChild(a);
                                })
                                .catch(function(err) {
                                    // User cancelled Save As dialog - not an error
                                    if (err.name !== 'AbortError') {
                                        alert('Gagal mengunduh: ' + err.message);
                                    }
                                })
                                .finally(function() {
                                    labelEl.textContent = originalText;
                                    el.style.pointerEvents = '';
                                    el.style.opacity = '';
                                });
                        }
                        </script>
                        <!-- Excel Export -->
                        <a href="{{ route('laporan.export-excel', array_merge(['filename' => 'laporan_' . $tipeLaporan . '_' . date('Ymd_His') . '.xls'], request()->all())) }}"
                           onclick="event.preventDefault(); downloadFile(this, this.href, '{{ 'laporan_' . $tipeLaporan . '_' . date('Ymd_His') . '.xls' }}'); return false;"
                           data-label="Export Excel"
                           class="btn btn-success rounded-3 d-flex align-items-center gap-2 px-3 py-2 fw-semibold fs-7 border-0" style="background-color: #10b981;">
                            <i class="fa-regular fa-file-excel"></i> <span>Export Excel</span>
                        </a>
                        <!-- PDF Export -->
                        <a href="{{ route('laporan.export-pdf', array_merge(['filename' => 'laporan_' . $tipeLaporan . '_' . date('Ymd_His') . '.pdf'], request()->all())) }}"
                           onclick="event.preventDefault(); downloadFile(this, this.href, '{{ 'laporan_' . $tipeLaporan . '_' . date('Ymd_His') . '.pdf' }}'); return false;"
                           data-label="Export PDF"
                           class="btn btn-danger rounded-3 d-flex align-items-center gap-2 px-3 py-2 fw-semibold fs-7 border-0" style="background-color: #ef4444;">
                            <i class="fa-regular fa-file-pdf"></i> <span>Export PDF</span>
                        </a>
                    </div>
                </div>
                
                <div class="card-body-custom p-0">
                    <div class="table-responsive">
                        <!-- STOK MASUK TABLE -->
                        @if($tipeLaporan === 'stok_masuk')
                            <table class="table table-custom table-hover m-0">
                                <thead>
                                    <tr>
                                        <th style="width: 120px;">ID Batch</th>
                                        <th>Produk</th>
                                        <th class="text-end" style="width: 130px;">Jumlah</th>
                                        <th class="text-center">Tgl Masuk</th>
                                        <th class="text-center">Tgl Kadaluarsa</th>
                                        <th>Operator</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results as $item)
                                        <tr>
                                            <td class="fw-bold text-secondary">#{{ $item->id_masuk }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                                <span class="small text-muted">{{ $item->id_produk }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-success">+{{ $item->jumlah_masuk }} <span class="small text-muted fw-normal">{{ $item->produk->satuan ?? '' }}</span></td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_masuk)) }}</td>
                                            <td class="text-center fw-medium">{{ date('d/m/Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                                            <td><span class="small text-muted">{{ $item->user->name ?? '-' }}</span></td>
                                            <td class="small text-secondary">{{ $item->keterangan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                Tidak ada data pemasukan stok untuk filter yang dipilih.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif

                        <!-- STOK KELUAR TABLE -->
                        @if($tipeLaporan === 'stok_keluar')
                            <table class="table table-custom table-hover m-0">
                                <thead>
                                    <tr>
                                        <th style="width: 120px;">ID Keluar</th>
                                        <th>Produk</th>
                                        <th>Batch Asal</th>
                                        <th class="text-end" style="width: 130px;">Jumlah</th>
                                        <th class="text-center">Tgl Keluar</th>
                                        <th>Operator</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results as $item)
                                        <tr>
                                            <td class="fw-bold text-secondary">#{{ $item->id_keluar }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                                <span class="small text-muted">{{ $item->id_produk }}</span>
                                            </td>
                                            <td class="fw-semibold text-muted">Batch #{{ $item->id_masuk }}</td>
                                            <td class="text-end fw-bold text-danger">-{{ $item->jumlah_keluar }} <span class="small text-muted fw-normal">{{ $item->produk->satuan ?? '' }}</span></td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_keluar)) }}</td>
                                            <td><span class="small text-muted">{{ $item->user->name ?? '-' }}</span></td>
                                            <td class="small text-secondary">{{ $item->keterangan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                Tidak ada data pengeluaran stok untuk filter yang dipilih.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif

                        <!-- STOK TERSEDIA TABLE -->
                        @if($tipeLaporan === 'stok_tersedia')
                            <table class="table table-custom table-hover m-0">
                                <thead>
                                    <tr>
                                        <th style="width: 120px;">ID Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Merek</th>
                                        <th class="text-end">Total Masuk</th>
                                        <th class="text-end">Total Keluar</th>
                                        <th class="text-end" style="width: 160px;">Stok Tersedia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results as $item)
                                        <tr>
                                            <td class="fw-bold text-primary">{{ $item->id_produk }}</td>
                                            <td class="fw-semibold text-dark">{{ $item->nama_produk }}</td>
                                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                            <td>{{ $item->merek }}</td>
                                            <td class="text-end text-success fw-medium">+{{ $item->total_masuk }}</td>
                                            <td class="text-end text-danger fw-medium">-{{ $item->total_keluar }}</td>
                                            <td class="text-end fw-bold text-dark">
                                                @if($item->stok_tersedia > 0)
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fs-7">{{ $item->stok_tersedia }} {{ $item->satuan }}</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1.5 fs-7">Habis</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                Tidak ada data persediaan stok untuk filter yang dipilih.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif

                        <!-- EXPIRED OR NEARING EXPIRED TABLE -->
                        @if($tipeLaporan === 'mendekati_kadaluarsa' || $tipeLaporan === 'kadaluarsa')
                            <table class="table table-custom table-hover m-0">
                                <thead>
                                    <tr>
                                        <th style="width: 120px;">ID Batch</th>
                                        <th>Produk</th>
                                        <th class="text-end" style="width: 150px;">Sisa Stok</th>
                                        <th class="text-center">Tanggal Kadaluarsa</th>
                                        <th class="text-center">Sisa Hari</th>
                                        <th class="text-center" style="width: 180px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results as $item)
                                        <tr>
                                            <td class="fw-bold text-secondary">#{{ $item->id_masuk }}</td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                                                <span class="small text-muted">{{ $item->id_produk }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-dark">{{ $item->sisa_stok }} <span class="small text-muted fw-normal">{{ $item->produk->satuan ?? '' }}</span></td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                                            <td class="text-center fw-medium">
                                                @if($item->sisa_hari <= 0)
                                                    <span class="text-danger fw-bold">Expired</span>
                                                @else
                                                    <span>{{ $item->sisa_hari }} hari</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($item->status_kadaluarsa === 'mendekati')
                                                    <span class="badge status-badge-mendekati rounded-pill px-3 py-1.5 fs-7"><i class="fa-solid fa-clock me-1"></i> Mendekati</span>
                                                @else
                                                    <span class="badge status-badge-kadaluarsa rounded-pill px-3 py-1.5 fs-7"><i class="fa-solid fa-circle-exclamation me-1"></i> Kadaluarsa</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                Tidak ada data batch yang terfilter.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const periodeSelect = document.getElementById('periode');
        const containers = {
            'harian': document.getElementById('harian-container'),
            'bulanan': document.getElementById('bulanan-container'),
            'tahunan': document.getElementById('tahunan-container'),
            'custom': document.getElementById('custom-container')
        };

        function togglePeriodInputs() {
            const selectedPeriod = periodeSelect.value;
            
            // Hide all
            Object.values(containers).forEach(container => {
                if(container) container.style.display = 'none';
            });

            // Remove required attribute from hidden fields
            document.querySelectorAll('.filter-input input, .filter-input select').forEach(el => {
                el.removeAttribute('required');
            });

            // Show selected & add required
            const activeContainer = containers[selectedPeriod];
            if (activeContainer) {
                activeContainer.style.display = 'block';
                activeContainer.querySelectorAll('input, select').forEach(el => {
                    el.setAttribute('required', 'required');
                });
            }
        }

        if (periodeSelect) {
            periodeSelect.addEventListener('change', togglePeriodInputs);
            togglePeriodInputs(); // trigger initial state
        }
    });
</script>
@endsection
