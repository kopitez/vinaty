@extends('layouts.admin')

@section('title', 'Notifikasi Sistem - Vinaty Inventory System')
@section('header-title', 'Daftar Notifikasi')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Notifikasi</li>
@endsection

@section('content')
<div class="row animate-fade-in">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="card card-custom">
            <div class="card-header-custom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);">Notifikasi Kadaluarsa</h5>
                    <p class="text-muted small m-0">Pengingat otomatis untuk produk mendekati kadaluarsa atau sudah kadaluarsa</p>
                </div>
                <div>
                    @if($notifications->where('status_baca', false)->count() > 0)
                        <form action="{{ route('notifikasi.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary-custom d-flex align-items-center gap-2">
                                <i class="fa-solid fa-check-double text-primary"></i> Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <div class="card-body-custom">
                <div class="list-group list-group-flush rounded-3 border overflow-hidden">
                    @forelse($notifications as $notif)
                        <div class="list-group-item p-4 d-flex justify-content-between align-items-start gap-3 transition {{ !$notif->status_baca ? 'bg-primary bg-opacity-10 border-start border-4 border-primary' : 'bg-white' }}" style="{{ !$notif->status_baca ? 'border-left-width: 5px !important;' : '' }}">
                            <div class="d-flex gap-3">
                                <div class="stat-icon-wrapper rounded-circle mt-1 {{ str_contains($notif->pesan, 'telah kadaluarsa') ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning' }}" style="width: 42px; height: 42px;">
                                    @if(str_contains($notif->pesan, 'telah kadaluarsa'))
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                    @else
                                        <i class="fa-solid fa-clock"></i>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="fw-semibold mb-1 text-dark">{{ $notif->pesan }}</h6>
                                    <span class="small text-muted d-block mt-1">
                                        <i class="fa-regular fa-clock me-1"></i> {{ $notif->created_at->diffForHumans() }} | {{ $notif->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>
                            
                            @if(!$notif->status_baca)
                                <form action="{{ route('notifikasi.mark-read', $notif->id) }}" method="POST" class="flex-shrink-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light border rounded-3 px-3 py-1.5 fw-semibold text-primary fs-7" title="Tandai dibaca">
                                        Tandai Dibaca
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-light text-secondary border rounded-pill px-3 py-1.5 fs-8 flex-shrink-0">
                                    <i class="fa-solid fa-check"></i> Dibaca
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted bg-white">
                            <i class="fa-regular fa-bell-slash mb-3 fs-3 d-block text-secondary"></i>
                            <span>Tidak ada notifikasi sistem saat ini.</span>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
