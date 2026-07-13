@extends('layouts.admin')

@section('title', 'Monitoring Kadaluarsa - Vinaty Inventory System')
@section('header-title', 'Monitoring Kadaluarsa (FEFO)')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Monitoring Kadaluarsa</li>
@endsection

@section('content')
<div class="row animate-fade-in">
    <div class="col-12">
        <!-- Status Filter Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('monitoring.index') }}" class="text-decoration-none">
                    <div class="card card-custom p-3 text-center border-start border-4 border-primary {{ !$status ? 'bg-primary-subtle text-primary border-primary' : 'bg-white' }}" style="border-left-width: 5px !important;">
                        <h6 class="text-muted small fw-bold mb-1">SEMUA BATCH</h6>
                        <span class="fs-5 fw-bold {{ !$status ? 'text-primary' : 'text-dark' }}">Tampilkan Semua</span>
                    </div>
                </a>
            </div>
            
            <div class="col-6 col-md-3">
                <a href="{{ route('monitoring.index', ['status' => 'aman']) }}" class="text-decoration-none">
                    <div class="card card-custom p-3 text-center border-start border-4 border-success {{ $status === 'aman' ? 'bg-success-subtle border-success' : 'bg-white' }}" style="border-left-width: 5px !important;">
                        <h6 class="text-muted small fw-bold mb-1">STATUS AMAN</h6>
                        <span class="fs-5 fw-bold text-success">> 7 Hari</span>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ route('monitoring.index', ['status' => 'mendekati']) }}" class="text-decoration-none">
                    <div class="card card-custom p-3 text-center border-start border-4 border-warning {{ $status === 'mendekati' ? 'bg-warning-subtle border-warning' : 'bg-white' }}" style="border-left-width: 5px !important;">
                        <h6 class="text-muted small fw-bold mb-1">MENDEKATI EXP</h6>
                        <span class="fs-5 fw-bold text-warning">1 - 7 Hari</span>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-3">
                <a href="{{ route('monitoring.index', ['status' => 'kadaluarsa']) }}" class="text-decoration-none">
                    <div class="card card-custom p-3 text-center border-start border-4 border-danger {{ $status === 'kadaluarsa' ? 'bg-danger-subtle border-danger' : 'bg-white' }}" style="border-left-width: 5px !important;">
                        <h6 class="text-muted small fw-bold mb-1">KADALUARSA</h6>
                        <span class="fs-5 fw-bold text-danger">Telah Lewat</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Monitoring List Card -->
        <div class="card card-custom">
            <div class="card-header-custom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);">Monitoring Masa Kadaluarsa Batch</h5>
                    <p class="text-muted small m-0">Memantau sisa umur simpan produk frozen food aktif di gudang</p>
                </div>
                <span class="badge bg-light text-dark border rounded-pill px-3 py-2 fw-semibold">
                    {{ $batches->count() }} Batch Terfilter
                </span>
            </div>
            
            <div class="card-body-custom p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover m-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center" style="width: 120px;">ID Batch</th>
                                <th class="text-end" style="width: 140px;">Qty Tersedia</th>
                                <th class="text-center" style="width: 180px;">Tanggal Kadaluarsa</th>
                                <th class="text-center" style="width: 140px;">Sisa Umur</th>
                                <th class="text-center" style="width: 180px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batches as $batch)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('produk.show', $batch->id_produk) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                                                {{ $batch->produk->nama_produk ?? 'Produk Dihapus' }}
                                            </a>
                                            <span class="small text-muted">{{ $batch->id_produk }} | Merek: {{ $batch->produk->merek ?? '' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center fw-semibold text-secondary">#{{ $batch->id_masuk }}</td>
                                    <td class="text-end fw-bold text-primary">
                                        {{ $batch->sisa_stok }} <span class="small text-muted fw-normal">{{ $batch->produk->satuan ?? '' }}</span>
                                    </td>
                                    <td class="text-center fw-medium">{{ date('d M Y', strtotime($batch->tanggal_kadaluarsa)) }}</td>
                                    <td class="text-center">
                                        @if($batch->sisa_hari <= 0)
                                            <span class="text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation"></i> Kadaluarsa</span>
                                        @elseif($batch->sisa_hari <= 7)
                                            <span class="text-warning fw-bold">{{ $batch->sisa_hari }} hari</span>
                                        @else
                                            <span class="text-success fw-medium">{{ $batch->sisa_hari }} hari</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($batch->status_kadaluarsa === 'aman')
                                            <span class="badge status-badge-aman rounded-pill px-4 py-2 fs-7">
                                                <i class="fa-solid fa-circle-check me-1"></i> Aman
                                            </span>
                                        @elseif($batch->status_kadaluarsa === 'mendekati')
                                            <span class="badge status-badge-mendekati rounded-pill px-4 py-2 fs-7">
                                                <i class="fa-solid fa-clock me-1"></i> Mendekati
                                            </span>
                                        @else
                                            <span class="badge status-badge-kadaluarsa rounded-pill px-4 py-2 fs-7">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i> Kadaluarsa
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-hourglass-empty mb-3 fs-3 d-block text-secondary"></i>
                                        <span>Tidak ada batch produk dengan filter status ini.</span>
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
@endsection
