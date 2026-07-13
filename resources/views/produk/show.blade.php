@extends('layouts.admin')

@section('title', 'Detail Produk - Vinaty Inventory System')
@section('header-title', 'Detail Produk')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('produk.index') }}" class="text-decoration-none">Produk</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $produk->id_produk }}</li>
@endsection

@section('content')
<div class="row g-4 animate-fade-in">
    <!-- Left Column: Specs and Stat -->
    <div class="col-lg-4">
        <!-- Stock Stat Card -->
        <div class="card card-custom mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color: white; border: none;">
            <div class="card-body-custom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-white-50 small text-uppercase fw-bold mb-1" style="font-family: var(--font-outfit);">Total Stok Tersedia</h6>
                    <h2 class="fw-bold m-0" style="font-family: var(--font-outfit); font-size: 36px;">
                        {{ $produk->stok_tersedia }} <span class="fs-5 fw-normal text-white-50">{{ $produk->satuan }}</span>
                    </h2>
                </div>
                <div class="stat-icon-wrapper text-white" style="background-color: rgba(255, 255, 255, 0.15); width: 60px; height: 60px; border-radius: 14px;">
                    <i class="fa-solid fa-boxes-stacked fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Specifications Card -->
        <div class="card card-custom">
            <div class="card-header-custom border-bottom">
                <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);">Informasi Produk</h5>
            </div>
            <div class="card-body-custom p-0">
                <table class="table table-borderless m-0">
                    <tbody>
                        <tr class="border-bottom">
                            <td class="text-muted fw-medium py-3 px-4" style="width: 130px;">ID Produk</td>
                            <td class="fw-bold text-primary py-3 px-4">{{ $produk->id_produk }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted fw-medium py-3 px-4">Nama Produk</td>
                            <td class="fw-semibold text-dark py-3 px-4">{{ $produk->nama_produk }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted fw-medium py-3 px-4">Kategori</td>
                            <td class="py-3 px-4">{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted fw-medium py-3 px-4">Merek</td>
                            <td class="py-3 px-4 fw-medium text-secondary">{{ $produk->merek }}</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted fw-medium py-3 px-4">Satuan</td>
                            <td class="py-3 px-4">{{ $produk->satuan }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium py-3 px-4">Keterangan</td>
                            <td class="py-3 px-4 small text-secondary" style="line-height: 1.5;">{{ $produk->keterangan ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light bg-opacity-50 p-3 border-top text-center">
                <a href="{{ route('produk.index') }}" class="btn btn-secondary-custom w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Expiration Batch Breakdown & Feeds -->
    <div class="col-lg-8">
        <!-- Active Batches (FEFO Breakdown) -->
        <div class="card card-custom mb-4">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);">Rincian Batch Aktif (Urutan FEFO)</h5>
                    <p class="text-muted small m-0">Batch stok yang tersedia berdasarkan masa kadaluarsa terdekat</p>
                </div>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-semibold">
                    {{ $batches->count() }} Batch Aktif
                </span>
            </div>
            
            <div class="card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover m-0">
                        <thead>
                            <tr>
                                <th>ID Batch</th>
                                <th class="text-center">Tanggal Masuk</th>
                                <th class="text-center">Kadaluarsa</th>
                                <th class="text-center">Sisa Hari</th>
                                <th class="text-end">Stok Tersedia</th>
                                <th class="text-center" style="width: 150px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                                <tr>
                                    <td class="fw-semibold text-secondary">#{{ $batch->id_masuk }}</td>
                                    <td class="text-center">{{ date('d M Y', strtotime($batch->tanggal_masuk)) }}</td>
                                    <td class="text-center fw-medium">{{ date('d M Y', strtotime($batch->tanggal_kadaluarsa)) }}</td>
                                    <td class="text-center">
                                        @if($batch->sisa_hari <= 0)
                                            <span class="text-danger fw-bold">Expired</span>
                                        | @elseif($batch->sisa_hari <= 7)
                                            <span class="text-warning fw-bold">{{ $batch->sisa_hari }} hari</span>
                                        @else
                                            <span class="text-success fw-medium">{{ $batch->sisa_hari }} hari</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">{{ $batch->sisa_stok }} / <span class="small text-muted">{{ $batch->jumlah_masuk }}</span></td>
                                    <td class="text-center">
                                        @if($batch->status_kadaluarsa === 'aman')
                                            <span class="badge status-badge-aman rounded-pill px-3 py-1.5 fs-8">Aman</span>
                                        @elseif($batch->status_kadaluarsa === 'mendekati')
                                            <span class="badge status-badge-mendekati rounded-pill px-3 py-1.5 fs-8">Mendekati</span>
                                        @else
                                            <span class="badge status-badge-kadaluarsa rounded-pill px-3 py-1.5 fs-8">Kadaluarsa</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-hourglass-empty mb-3 fs-3 d-block text-secondary"></i>
                                        <span>Tidak ada batch stok aktif tersedia untuk produk ini.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- History Tabs -->
        <div class="card card-custom">
            <div class="card-header-custom p-0 border-bottom">
                <ul class="nav nav-tabs border-0 px-4 pt-3" id="historyTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold text-dark py-3 px-3 border-0 border-bottom border-3 border-transparent" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk-tab-pane" type="button" role="tab" aria-controls="masuk-tab-pane" aria-selected="true" style="font-family: var(--font-outfit); font-size: 15px;">
                            Riwayat Stok Masuk (Terbaru)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold text-dark py-3 px-3 border-0 border-bottom border-3 border-transparent" id="keluar-tab" data-bs-toggle="tab" data-bs-target="#keluar-tab-pane" type="button" role="tab" aria-controls="keluar-tab-pane" aria-selected="false" style="font-family: var(--font-outfit); font-size: 15px;">
                            Riwayat Stok Keluar (Terbaru)
                        </button>
                    </li>
                </ul>
            </div>
            
            <div class="card-body-custom p-0">
                <div class="tab-content" id="historyTabContent">
                    <!-- Incoming History Pane -->
                    <div class="tab-pane fade show active" id="masuk-tab-pane" role="tabpanel" aria-labelledby="masuk-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>ID Batch</th>
                                        <th class="text-center">Tgl Masuk</th>
                                        <th class="text-center">Tgl Expired</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Operator</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stokMasukHistories as $sm)
                                        <tr>
                                            <td class="fw-bold text-secondary">#{{ $sm->id_masuk }}</td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($sm->tanggal_masuk)) }}</td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($sm->tanggal_kadaluarsa)) }}</td>
                                            <td class="text-end fw-bold text-success">+{{ $sm->jumlah_masuk }}</td>
                                            <td><span class="small text-muted">{{ $sm->user->name ?? '-' }}</span></td>
                                            <td class="small text-secondary">{{ $sm->keterangan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                Tidak ada riwayat stok masuk.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Outgoing History Pane -->
                    <div class="tab-pane fade" id="keluar-tab-pane" role="tabpanel" aria-labelledby="keluar-tab" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>ID Keluar</th>
                                        <th>Batch Masuk</th>
                                        <th class="text-center">Tgl Keluar</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Operator</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stokKeluarHistories as $sk)
                                        <tr>
                                            <td class="fw-bold text-secondary">#{{ $sk->id_keluar }}</td>
                                            <td class="fw-semibold">Batch #{{ $sk->id_masuk }}</td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($sk->tanggal_keluar)) }}</td>
                                            <td class="text-end fw-bold text-danger">-{{ $sk->jumlah_keluar }}</td>
                                            <td><span class="small text-muted">{{ $sk->user->name ?? '-' }}</span></td>
                                            <td class="small text-secondary">{{ $sk->keterangan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                Tidak ada riwayat stok keluar.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Styling tabs bottom-borders active state dynamically
    const tabElList = document.querySelectorAll('button[data-bs-toggle="tab"]')
    tabElList.forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', event => {
            tabElList.forEach(t => t.style.borderBottomColor = 'transparent');
            event.target.style.borderBottomColor = '#4f46e5';
        })
    });
    // Set initial border color
    document.querySelector('button[data-bs-toggle="tab"].active').style.borderBottomColor = '#4f46e5';
</script>
@endsection
