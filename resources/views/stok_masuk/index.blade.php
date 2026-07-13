@extends('layouts.admin')

@section('title', 'Stok Masuk - Vinaty Inventory System')
@section('header-title', 'Stok Masuk')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Stok Masuk</li>
@endsection

@section('content')
<div class="row animate-fade-in">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);">Riwayat Stok Masuk</h5>
                    <p class="text-muted small m-0">Catat dan pantau transaksi pemasukan produk frozen food</p>
                </div>
                <div>
                    <button class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addStockInModal">
                        <i class="fa-solid fa-circle-arrow-down"></i> Catat Stok Masuk
                    </button>
                </div>
            </div>
            
            <div class="card-body-custom">
                <!-- Search -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-4">
                        <form action="{{ route('stok-masuk.index') }}" method="GET">
                            <div class="input-group border rounded-3 overflow-hidden shadow-sm" style="background-color: #f8fafc;">
                                <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" name="search" class="form-control bg-transparent border-0 py-2 fs-7" placeholder="Cari nama atau ID produk..." value="{{ $search }}">
                                @if($search)
                                    <a href="{{ route('stok-masuk.index') }}" class="btn btn-light bg-transparent border-0 text-muted d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary px-3 border-0" style="background-color: var(--primary-color);">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Stock In Table -->
                <div class="table-responsive rounded-3 border">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th style="width: 110px;">ID Batch</th>
                                <th>Produk</th>
                                <th class="text-end" style="width: 130px;">Jumlah</th>
                                <th class="text-center" style="width: 150px;">Tanggal Masuk</th>
                                <th class="text-center" style="width: 180px;">Kadaluarsa</th>
                                <th class="text-center" style="width: 150px;">Status</th>
                                <th style="width: 150px;">Operator</th>
                                <th>Keterangan</th>
                                <th class="text-center" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokMasuk as $item)
                                <tr>
                                    <td class="fw-bold text-secondary">
                                        #{{ $item->id_masuk }}
                                        @if($item->updated_at && $item->updated_at->gt($item->created_at))
                                            <span class="d-block small text-primary fw-normal" style="font-size: 11px; white-space: nowrap;"><i class="fa-solid fa-pen-to-square"></i> Telah Diperbarui</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('produk.show', $item->id_produk) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                                                {{ $item->produk->nama_produk ?? 'Produk Dihapus' }}
                                            </a>
                                            <span class="small text-muted">{{ $item->id_produk }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-success">
                                        +{{ $item->jumlah_masuk }} <span class="small text-muted fw-normal">{{ $item->produk->satuan ?? '' }}</span>
                                    </td>
                                    <td class="text-center">{{ date('d M Y', strtotime($item->tanggal_masuk)) }}</td>
                                    <td class="text-center fw-medium">{{ date('d M Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                                    <td class="text-center">
                                        @if($item->status_kadaluarsa === 'aman')
                                            <span class="badge status-badge-aman rounded-pill px-3 py-1.5 fs-8">Aman</span>
                                        @elseif($item->status_kadaluarsa === 'mendekati')
                                            <span class="badge status-badge-mendekati rounded-pill px-3 py-1.5 fs-8">Mendekati</span>
                                        @else
                                            <span class="badge status-badge-kadaluarsa rounded-pill px-3 py-1.5 fs-8">Kadaluarsa</span>
                                        @endif
                                    </td>
                                    <td><span class="small text-muted">{{ $item->user->name ?? '-' }}</span></td>
                                    <td class="small text-secondary">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-light border btn-action rounded-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editStockInModal{{ $item->id_masuk }}"
                                                    title="Edit Catatan">
                                                <i class="fa-regular fa-pen-to-square text-primary"></i>
                                            </button>
                                            
                                            <form action="{{ route('stok-masuk.destroy', $item->id_masuk) }}" method="POST" class="d-inline delete-form" data-confirm="Apakah Anda yakin ingin menghapus catatan stok masuk Batch #{{ $item->id_masuk }}?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border btn-action rounded-3" title="Hapus Catatan">
                                                    <i class="fa-regular fa-trash-can text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                @push('modals')
                                <!-- Edit Stock In Modal -->
                                <div class="modal fade" id="editStockInModal{{ $item->id_masuk }}" tabindex="-1" aria-labelledby="editStockInModalLabel{{ $item->id_masuk }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 shadow">
                                            <div class="modal-header border-bottom p-4">
                                                <h5 class="modal-title fw-bold" id="editStockInModalLabel{{ $item->id_masuk }}" style="font-family: var(--font-outfit);">Edit Stok Masuk: Batch #{{ $item->id_masuk }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('stok-masuk.update', $item->id_masuk) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-light border rounded-3 small p-3 mb-3">
                                                        <strong>Produk:</strong> {{ $item->id_produk }} - {{ $item->produk->nama_produk ?? '' }}
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="jumlah_masuk{{ $item->id_masuk }}" class="form-label fw-semibold">Jumlah Masuk</label>
                                                        <div class="input-group">
                                                            <input type="number" name="jumlah_masuk" id="jumlah_masuk{{ $item->id_masuk }}" class="form-control rounded-3 py-2" min="1" value="{{ old('jumlah_masuk', $item->jumlah_masuk) }}" required>
                                                            <span class="input-group-text bg-light text-muted border border-start-0 rounded-end-3">{{ $item->produk->satuan ?? 'Unit' }}</span>
                                                        </div>
                                                        <span class="form-text text-muted small">Sudah digunakan: <strong>{{ $item->stokKeluars()->sum('jumlah_keluar') }}</strong> {{ $item->produk->satuan ?? 'Unit' }}</span>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="tanggal_masuk{{ $item->id_masuk }}" class="form-label fw-semibold">Tanggal Masuk</label>
                                                            <input type="date" name="tanggal_masuk" id="tanggal_masuk{{ $item->id_masuk }}" class="form-control rounded-3 py-2" value="{{ old('tanggal_masuk', $item->tanggal_masuk) }}" required>
                                                        </div>
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label for="tanggal_kadaluarsa{{ $item->id_masuk }}" class="form-label fw-semibold">Tanggal Kadaluarsa</label>
                                                            <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa{{ $item->id_masuk }}" class="form-control rounded-3 py-2" value="{{ old('tanggal_kadaluarsa', $item->tanggal_kadaluarsa) }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="keterangan{{ $item->id_masuk }}" class="form-label fw-semibold">Keterangan</label>
                                                        <textarea name="keterangan" id="keterangan{{ $item->id_masuk }}" class="form-control rounded-3 py-2" rows="3" placeholder="(opsional)">{{ old('keterangan', $item->keterangan) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top p-4 bg-light bg-opacity-50">
                                                    <button type="button" class="btn btn-secondary-custom rounded-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary-custom rounded-3">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endpush
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-circle-arrow-down mb-3 fs-3 d-block text-secondary"></i>
                                        <span>Tidak ada riwayat stok masuk.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-4">
                    {{ $stokMasuk->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Add Stock In Modal -->
<div class="modal fade" id="addStockInModal" tabindex="-1" aria-labelledby="addStockInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold" id="addStockInModalLabel" style="font-family: var(--font-outfit);">Catat Stok Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('stok-masuk.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="id_produk" class="form-label fw-semibold">Produk</label>
                        <select name="id_produk" id="id_produk" class="form-select rounded-3 py-2 @error('id_produk') is-invalid @enderror" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id_produk }}" {{ old('id_produk') == $p->id_produk ? 'selected' : '' }}>
                                    {{ $p->id_produk }} - {{ $p->nama_produk }} ({{ $p->satuan }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_masuk" class="form-label fw-semibold">Jumlah Masuk</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control rounded-3 py-2 @error('jumlah_masuk') is-invalid @enderror" min="1" placeholder="Contoh: 50" value="{{ old('jumlah_masuk') }}" required>
                            <span class="input-group-text bg-light text-muted border border-start-0 rounded-end-3" id="satuanLabel">Unit</span>
                        </div>
                        @error('jumlah_masuk')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_masuk" class="form-label fw-semibold">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control rounded-3 py-2 @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_kadaluarsa" class="form-label fw-semibold">Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa" id="tanggal_kadaluarsa" class="form-control rounded-3 py-2 @error('tanggal_kadaluarsa') is-invalid @enderror" value="{{ old('tanggal_kadaluarsa') }}" required>
                            @error('tanggal_kadaluarsa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control rounded-3 py-2" rows="3" placeholder="(opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-4 bg-light bg-opacity-50">
                    <button type="button" class="btn btn-secondary-custom rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom rounded-3">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endsection

@section('scripts')
<script>
    // Dynamically update the unit label based on selected product
    const productSelect = document.getElementById('id_produk');
    const satuanLabel = document.getElementById('satuanLabel');

    if(productSelect) {
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex].text;
            const match = selectedOption.match(/\(([^)]+)\)$/);
            if(match && match[1]) {
                satuanLabel.textContent = match[1];
            } else {
                satuanLabel.textContent = 'Unit';
            }
        });
        
        // Trigger on load if there's an old value
        if (productSelect.value) {
            productSelect.dispatchEvent(new Event('change'));
        }
    }
</script>
@endsection
