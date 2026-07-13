@extends('layouts.admin')

@section('title', 'Manajemen Produk - Vinaty Inventory System')
@section('header-title', 'Manajemen Produk')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Produk</li>
@endsection

@section('content')
<div class="row animate-fade-in">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);">Daftar Produk</h5>
                    <p class="text-muted small m-0">Kelola master data produk frozen food</p>
                </div>
                <div>
                    <button class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fa-solid fa-plus"></i> Tambah Produk
                    </button>
                </div>
            </div>
            
            <div class="card-body-custom">
                <!-- Search and Filters -->
                <form action="{{ route('produk.index') }}" method="GET" class="row g-3 mb-4">
                    <div class="col-md-5 col-lg-4">
                        <div class="input-group border rounded-3 overflow-hidden shadow-sm" style="background-color: #f8fafc;">
                            <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" name="search" class="form-control bg-transparent border-0 py-2 fs-7" placeholder="Cari ID, nama, atau merek..." value="{{ $search }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-lg-3">
                        <select name="kategori_id" class="form-select border rounded-3 shadow-sm py-2 fs-7" style="background-color: #f8fafc;">
                            <option value="">-- Semua Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ $kategoriId == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-custom px-4" style="background-color: var(--primary-color);">Filter</button>
                            @if($search || $kategoriId)
                                <a href="{{ route('produk.index') }}" class="btn btn-secondary-custom d-flex align-items-center justify-content-center px-3" title="Reset Filter">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Products Table -->
                <div class="table-responsive rounded-3 border">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th style="width: 120px;">ID Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Merek</th>
                                <th>Satuan</th>
                                <th class="text-center" style="width: 160px;">Stok Tersedia</th>
                                <th class="text-center" style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produk as $item)
                                <tr>
                                    <td class="fw-bold text-primary">{{ $item->id_produk }}</td>
                                    <td class="fw-semibold text-dark">{{ $item->nama_produk }}</td>
                                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                                    <td>{{ $item->merek }}</td>
                                    <td><span class="badge bg-light text-dark border px-2 py-1.5 fw-normal">{{ $item->satuan }}</span></td>
                                    <td class="text-center">
                                        @if($item->stok_tersedia > 0)
                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-semibold">
                                                {{ $item->stok_tersedia }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 fw-semibold">
                                                Habis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- View Button -->
                                            <a href="{{ route('produk.show', $item->id_produk) }}" class="btn btn-sm btn-light border btn-action rounded-3" title="Detail Produk">
                                                <i class="fa-regular fa-eye text-success"></i>
                                            </a>

                                            <!-- Edit Button -->
                                            <button class="btn btn-sm btn-light border btn-action rounded-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editProductModal{{ $item->id_produk }}"
                                                    title="Edit Produk">
                                                <i class="fa-regular fa-pen-to-square text-primary"></i>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <form action="{{ route('produk.destroy', $item->id_produk) }}" method="POST" class="d-inline delete-form" data-confirm="Apakah Anda yakin ingin menghapus produk &quot;{{ $item->nama_produk }}&quot;?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border btn-action rounded-3" title="Hapus Produk">
                                                    <i class="fa-regular fa-trash-can text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Product Modal -->
                                @push('modals')
                                <div class="modal fade" id="editProductModal{{ $item->id_produk }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $item->id_produk }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 shadow">
                                            <div class="modal-header border-bottom p-4">
                                                <h5 class="modal-title fw-bold" id="editProductModalLabel{{ $item->id_produk }}" style="font-family: var(--font-outfit);">Edit Produk: {{ $item->id_produk }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('produk.update', $item->id_produk) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label for="nama_produk{{ $item->id_produk }}" class="form-label fw-semibold">Nama Produk</label>
                                                        <input type="text" name="nama_produk" id="nama_produk{{ $item->id_produk }}" class="form-control rounded-3 py-2" value="{{ old('nama_produk', $item->nama_produk) }}" required>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="id_kategori{{ $item->id_produk }}" class="form-label fw-semibold">Kategori</label>
                                                        <select name="id_kategori" id="id_kategori{{ $item->id_produk }}" class="form-select rounded-3 py-2" required>
                                                            @foreach($kategoris as $kat)
                                                                <option value="{{ $kat->id }}" {{ $item->id_kategori == $kat->id ? 'selected' : '' }}>
                                                                    {{ $kat->nama_kategori }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="merek{{ $item->id_produk }}" class="form-label fw-semibold">Merek</label>
                                                            <input type="text" name="merek" id="merek{{ $item->id_produk }}" class="form-control rounded-3 py-2" value="{{ old('merek', $item->merek) }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="satuan{{ $item->id_produk }}" class="form-label fw-semibold">Satuan</label>
                                                            <input type="text" name="satuan" id="satuan{{ $item->id_produk }}" class="form-control rounded-3 py-2" placeholder="Contoh: Pcs, Kg, Box" value="{{ old('satuan', $item->satuan) }}" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="keterangan{{ $item->id_produk }}" class="form-label fw-semibold">Keterangan</label>
                                                        <textarea name="keterangan" id="keterangan{{ $item->id_produk }}" class="form-control rounded-3 py-2" rows="3">{{ old('keterangan', $item->keterangan) }}</textarea>
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
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-cube mb-3 fs-3 d-block text-secondary"></i>
                                        <span>Tidak ada data produk ditemukan.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-4">
                    {{ $produk->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold" id="addProductModalLabel" style="font-family: var(--font-outfit);">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('produk.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label fw-semibold">Nama Produk</label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control rounded-3 py-2 @error('nama_produk') is-invalid @enderror" placeholder="Contoh: Nugget Ayam Premium" value="{{ old('nama_produk') }}" required>
                        @error('nama_produk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="id_kategori" class="form-label fw-semibold">Kategori</label>
                        <select name="id_kategori" id="id_kategori" class="form-select rounded-3 py-2 @error('id_kategori') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" {{ old('id_kategori') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="merek" class="form-label fw-semibold">Merek</label>
                            <input type="text" name="merek" id="merek" class="form-control rounded-3 py-2 @error('merek') is-invalid @enderror" placeholder="Contoh: Fiesta" value="{{ old('merek') }}" required>
                            @error('merek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="satuan" id="satuan" class="form-control rounded-3 py-2 @error('satuan') is-invalid @enderror" placeholder="Contoh: Pcs, Kg, Box" value="{{ old('satuan') }}" required>
                            @error('satuan')
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
                    <button type="submit" class="btn btn-primary-custom rounded-3">Tambah Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endsection
