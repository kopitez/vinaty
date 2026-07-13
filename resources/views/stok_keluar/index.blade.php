@extends('layouts.admin')

@section('title', 'Stok Keluar - Vinaty Inventory System')
@section('header-title', 'Stok Keluar')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Stok Keluar</li>
@endsection

@section('content')
<div class="row animate-fade-in">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);">Riwayat Stok Keluar</h5>
                    <p class="text-muted small m-0">Catat pengeluaran stok otomatis berdasarkan aturan FEFO (First Expired First Out)</p>
                </div>
                <div>
                    <button class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addStockOutModal">
                        <i class="fa-solid fa-circle-arrow-up"></i> Catat Stok Keluar
                    </button>
                </div>
            </div>
            
            <div class="card-body-custom">
                <!-- Search -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-4">
                        <form action="{{ route('stok-keluar.index') }}" method="GET">
                            <div class="input-group border rounded-3 overflow-hidden shadow-sm" style="background-color: #f8fafc;">
                                <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" name="search" class="form-control bg-transparent border-0 py-2 fs-7" placeholder="Cari nama atau ID produk..." value="{{ $search }}">
                                @if($search)
                                    <a href="{{ route('stok-keluar.index') }}" class="btn btn-light bg-transparent border-0 text-muted d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary px-3 border-0" style="background-color: var(--primary-color);">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Stock Out Table -->
                <div class="table-responsive rounded-3 border">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th style="width: 110px;">ID Keluar</th>
                                <th>Produk</th>
                                <th style="width: 150px;">Batch Asal</th>
                                <th class="text-end" style="width: 130px;">Jumlah</th>
                                <th class="text-center" style="width: 160px;">Tanggal Keluar</th>
                                <th style="width: 150px;">Operator</th>
                                <th>Keterangan</th>
                                <th class="text-center" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokKeluar as $item)
                                <tr>
                                    <td class="fw-bold text-secondary">
                                        #{{ $item->id_keluar }}
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
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-secondary">Batch #{{ $item->id_masuk }}</span>
                                            <span class="small text-muted">Exp: {{ date('d/m/Y', strtotime($item->stokMasuk->tanggal_kadaluarsa ?? '')) }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-danger">
                                        -{{ $item->jumlah_keluar }} <span class="small text-muted fw-normal">{{ $item->produk->satuan ?? '' }}</span>
                                    </td>
                                    <td class="text-center">{{ date('d M Y', strtotime($item->tanggal_keluar)) }}</td>
                                    <td><span class="small text-muted">{{ $item->user->name ?? '-' }}</span></td>
                                    <td class="small text-secondary">{{ $item->keterangan ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button class="btn btn-sm btn-light border btn-action rounded-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editStockOutModal{{ $item->id_keluar }}"
                                                    title="Edit Catatan">
                                                <i class="fa-regular fa-pen-to-square text-primary"></i>
                                            </button>
                                            
                                            <form action="{{ route('stok-keluar.destroy', $item->id_keluar) }}" method="POST" class="d-inline delete-form" data-confirm="Apakah Anda yakin ingin menghapus catatan stok keluar #{{ $item->id_keluar }}? Tindakan ini akan mengembalikan stok ke batch asalnya.">
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
                                <!-- Edit Stock Out Modal -->
                                <div class="modal fade" id="editStockOutModal{{ $item->id_keluar }}" tabindex="-1" aria-labelledby="editStockOutModalLabel{{ $item->id_keluar }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 shadow">
                                            <div class="modal-header border-bottom p-4">
                                                <h5 class="modal-title fw-bold" id="editStockOutModalLabel{{ $item->id_keluar }}" style="font-family: var(--font-outfit);">Edit Stok Keluar: #{{ $item->id_keluar }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('stok-keluar.update', $item->id_keluar) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="alert alert-light border rounded-3 small p-3 mb-3">
                                                        <div class="mb-1"><strong>Produk:</strong> {{ $item->id_produk }} - {{ $item->produk->nama_produk ?? '' }}</div>
                                                        <div class="mb-1"><strong>Batch Asal:</strong> Batch #{{ $item->id_masuk }}</div>
                                                        <div><strong>Jumlah Keluar:</strong> {{ $item->jumlah_keluar }} {{ $item->produk->satuan ?? 'Unit' }}</div>
                                                    </div>

                                                    <div class="alert alert-warning border-0 rounded-3 small p-2 mb-3 d-flex align-items-start gap-2">
                                                        <i class="fa-solid fa-triangle-exclamation text-warning mt-0.5"></i>
                                                        <div>Untuk menjaga keakuratan FEFO, jumlah keluar bersifat permanen. Jika ingin merubah jumlah, silakan hapus transaksi ini lalu catat kembali.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="tanggal_keluar{{ $item->id_keluar }}" class="form-label fw-semibold">Tanggal Keluar</label>
                                                        <input type="date" name="tanggal_keluar" id="tanggal_keluar{{ $item->id_keluar }}" class="form-control rounded-3 py-2" value="{{ old('tanggal_keluar', $item->tanggal_keluar) }}" required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="keterangan{{ $item->id_keluar }}" class="form-label fw-semibold">Keterangan</label>
                                                        <textarea name="keterangan" id="keterangan{{ $item->id_keluar }}" class="form-control rounded-3 py-2" rows="3" placeholder="(opsional)">{{ old('keterangan', $item->keterangan) }}</textarea>
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
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-circle-arrow-up mb-3 fs-3 d-block text-secondary"></i>
                                        <span>Tidak ada riwayat stok keluar.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-4">
                    {{ $stokKeluar->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Add Stock Out Modal -->
<div class="modal fade" id="addStockOutModal" tabindex="-1" aria-labelledby="addStockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold" id="addStockOutModalLabel" style="font-family: var(--font-outfit);">Catat Stok Keluar (FEFO)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('stok-keluar.store') }}" method="POST" id="stockOutForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 small p-3 mb-3 d-flex align-items-start gap-2">
                        <i class="fa-solid fa-circle-info text-primary mt-0.5"></i>
                        <div>
                            <strong>Informasi FEFO:</strong> Pengeluaran stok akan diambil secara otomatis dari batch dengan tanggal kadaluarsa paling dekat. Anda tidak perlu memilih batch secara manual.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_produk" class="form-label fw-semibold">Produk</label>
                        <select name="id_produk" id="id_produk" class="form-select rounded-3 py-2 @error('id_produk') is-invalid @enderror" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $p)
                                <option value="{{ $p->id_produk }}" data-stock="{{ $p->stok_tersedia }}" data-unit="{{ $p->satuan }}" {{ old('id_produk') == $p->id_produk ? 'selected' : '' }}>
                                    {{ $p->id_produk }} - {{ $p->nama_produk }} (Tersedia: {{ $p->stok_tersedia }} {{ $p->satuan }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_keluar" class="form-label fw-semibold">Jumlah Keluar</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="form-control rounded-3 py-2 @error('jumlah_keluar') is-invalid @enderror" min="1" placeholder="Contoh: 15" value="{{ old('jumlah_keluar') }}" required>
                            <span class="input-group-text bg-light text-muted border border-start-0 rounded-end-3" id="satuanLabel">Unit</span>
                        </div>
                        <span class="form-text text-muted small" id="stockLimitText" style="display: none;"></span>
                        @error('jumlah_keluar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_keluar" class="form-label fw-semibold">Tanggal Keluar</label>
                        <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control rounded-3 py-2 @error('tanggal_keluar') is-invalid @enderror" value="{{ old('tanggal_keluar', date('Y-m-d')) }}" required>
                        @error('tanggal_keluar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control rounded-3 py-2" rows="3" placeholder="(opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top p-4 bg-light bg-opacity-50">
                    <button type="button" class="btn btn-secondary-custom rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom rounded-3" id="submitBtn">Keluarkan Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const productSelect = document.getElementById('id_produk');
        const jumlahInput = document.getElementById('jumlah_keluar');
        const satuanLabel = document.getElementById('satuanLabel');
        const stockLimitText = document.getElementById('stockLimitText');
        const submitBtn = document.getElementById('submitBtn');

        function updateLimits() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const stockAvailable = parseInt(selectedOption.getAttribute('data-stock'));
                const unit = selectedOption.getAttribute('data-unit');
                
                satuanLabel.textContent = unit;
                jumlahInput.max = stockAvailable;
                
                stockLimitText.textContent = `Batas pengeluaran maksimal: ${stockAvailable} ${unit}`;
                stockLimitText.style.display = 'block';
                stockLimitText.className = 'form-text text-muted small';
            } else {
                satuanLabel.textContent = 'Unit';
                jumlahInput.removeAttribute('max');
                stockLimitText.style.display = 'none';
            }
        }

        if (productSelect) {
            productSelect.addEventListener('change', updateLimits);
            
            // Trigger on load if there's an old value
            if (productSelect.value) {
                updateLimits();
            }
        }

        // Live validation for input quantity exceeding available stock
        if (jumlahInput) {
            jumlahInput.addEventListener('input', function() {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const stockAvailable = parseInt(selectedOption.getAttribute('data-stock'));
                    const enteredVal = parseInt(this.value);

                    if (enteredVal > stockAvailable) {
                        stockLimitText.textContent = `Peringatan: Jumlah melebihi stok yang tersedia (${stockAvailable})!`;
                        stockLimitText.className = 'form-text text-danger small fw-bold';
                        jumlahInput.classList.add('is-invalid');
                        submitBtn.disabled = true;
                    } else {
                        stockLimitText.textContent = `Batas pengeluaran maksimal: ${stockAvailable} ${selectedOption.getAttribute('data-unit')}`;
                        stockLimitText.className = 'form-text text-muted small';
                        jumlahInput.classList.remove('is-invalid');
                        submitBtn.disabled = false;
                    }
                }
            });
        }
    });
</script>
@endsection
