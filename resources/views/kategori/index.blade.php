@extends('layouts.admin')

@section('title', 'Kategori Produk - Vinaty Inventory System')
@section('header-title', 'Manajemen Kategori')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Kategori</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-custom">
            <div class="card-header-custom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h5 class="card-title fw-bold m-0" style="font-family: var(--font-outfit);">Daftar Kategori</h5>
                    <p class="text-muted small m-0">Kelola kategori produk frozen food Anda</p>
                </div>
                <div>
                    <button class="btn btn-primary-custom d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fa-solid fa-plus"></i> Tambah Kategori
                    </button>
                </div>
            </div>
            
            <div class="card-body-custom">
                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-6 col-lg-4">
                        <form action="{{ route('kategori.index') }}" method="GET">
                            <div class="input-group border rounded-3 overflow-hidden shadow-sm" style="background-color: #f8fafc;">
                                <span class="input-group-text bg-transparent border-0 pe-0 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" name="search" class="form-control bg-transparent border-0 py-2 fs-7" placeholder="Cari nama kategori..." value="{{ $search }}">
                                @if($search)
                                    <a href="{{ route('kategori.index') }}" class="btn btn-light bg-transparent border-0 text-muted d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary px-3 border-0" style="background-color: var(--primary-color);">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Category Table -->
                <div class="table-responsive rounded-3 border">
                    <table class="table table-custom table-hover">
                        <thead>
                            <tr>
                                <th style="width: 80px;">No</th>
                                <th>Nama Kategori</th>
                                <th style="width: 200px;" class="text-center">Jumlah Produk</th>
                                <th style="width: 180px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $index => $item)
                                <tr>
                                    <td>{{ $kategori->firstItem() + $index }}</td>
                                    <td class="fw-semibold text-dark">{{ $item->nama_kategori }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 fw-medium fs-7">
                                            {{ $item->produks_count }} Produk
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Edit Button (trigger modal) -->
                                            <button class="btn btn-sm btn-light border btn-action rounded-3" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editCategoryModal{{ $item->id }}"
                                                    title="Edit Kategori">
                                                <i class="fa-regular fa-pen-to-square text-primary"></i>
                                            </button>
                                            
                                            <!-- Delete Form -->
                                            <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="d-inline delete-form" data-confirm="Apakah Anda yakin ingin menghapus kategori &quot;{{ $item->nama_kategori }}&quot;?">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border btn-action rounded-3" title="Hapus Kategori">
                                                    <i class="fa-regular fa-trash-can text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Category Modal -->
                                @push('modals')
                                <div class="modal fade" id="editCategoryModal{{ $item->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 shadow">
                                            <div class="modal-header border-bottom p-4">
                                                <h5 class="modal-title fw-bold" id="editCategoryModalLabel{{ $item->id }}" style="font-family: var(--font-outfit);">Edit Kategori</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('kategori.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label for="nama_kategori{{ $item->id }}" class="form-label fw-semibold">Nama Kategori</label>
                                                        <input type="text" name="nama_kategori" id="nama_kategori{{ $item->id }}" class="form-control rounded-3 py-2 @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $item->nama_kategori) }}" required>
                                                        @error('nama_kategori')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
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
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-tags mb-3 fs-3 d-block text-secondary"></i>
                                        <span>Tidak ada data kategori ditemukan.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-4">
                    {{ $kategori->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('modals')
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom p-4">
                <h5 class="modal-title fw-bold" id="addCategoryModalLabel" style="font-family: var(--font-outfit);">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label fw-semibold">Nama Kategori</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control rounded-3 py-2 @error('nama_kategori') is-invalid @enderror" placeholder="Contoh: Nugget, Sosis, dll." value="{{ old('nama_kategori') }}" required>
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-top p-4 bg-light bg-opacity-50">
                    <button type="button" class="btn btn-secondary-custom rounded-3" data-bs-modal="dismiss" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom rounded-3">Tambah Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endsection
