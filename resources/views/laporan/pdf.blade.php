<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventory</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .brand-name {
            font-size: 18pt;
            font-weight: bold;
            color: #4f46e5;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-title {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0 0 0;
        }
        .doc-meta {
            font-size: 9pt;
            color: #64748b;
            margin: 5px 0 0 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
            letter-spacing: 0.5px;
            border: 1px solid #cbd5e1;
            padding: 8px 6px;
            text-align: center;
        }
        td {
            border: 1px solid #e2e8f0;
            padding: 7px 6px;
            vertical-align: middle;
        }
        .text-center {
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
        .status-badge {
            font-size: 8pt;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-aman {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-mendekati {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-kadaluarsa {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            position: fixed;
            bottom: -0.5cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="brand-name">Vinaty Culinary</h1>
        <div class="doc-title">
            @if($tipeLaporan === 'stok_masuk') LAPORAN STOK MASUK INVENTORY
            @elseif($tipeLaporan === 'stok_keluar') LAPORAN STOK KELUAR INVENTORY
            @elseif($tipeLaporan === 'stok_tersedia') LAPORAN STOK PERSIDIAAN (TERSEDIA)
            @elseif($tipeLaporan === 'mendekati_kadaluarsa') LAPORAN PRODUK MENDEKATI KADALUARSA
            @elseif($tipeLaporan === 'kadaluarsa') LAPORAN PRODUK KADALUARSA
            @endif
        </div>
        <div class="doc-meta">
            {{ $periodeText }} | Waktu Unduh: {{ date('d M Y, H:i') }}
        </div>
    </div>

    <!-- STOK MASUK -->
    @if($tipeLaporan === 'stok_masuk')
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Batch ID</th>
                    <th style="width: 12%;">ID Produk</th>
                    <th>Nama Produk</th>
                    <th style="width: 10%;">Jumlah</th>
                    <th style="width: 12%;">Tgl Masuk</th>
                    <th style="width: 15%;">Tgl Expired</th>
                    <th style="width: 15%;">Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold">#{{ $item->id_masuk }}</td>
                        <td class="text-center text-muted">{{ $item->id_produk }}</td>
                        <td class="fw-bold">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                        <td class="text-end fw-bold" style="color: #10b981;">+{{ $item->jumlah_masuk }} {{ $item->produk->satuan ?? '' }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_masuk)) }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada data pemasukan stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- STOK KELUAR -->
    @if($tipeLaporan === 'stok_keluar')
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">ID Keluar</th>
                    <th style="width: 15%;">Batch Asal</th>
                    <th style="width: 12%;">ID Produk</th>
                    <th>Nama Produk</th>
                    <th style="width: 10%;">Jumlah</th>
                    <th style="width: 12%;">Tgl Keluar</th>
                    <th style="width: 15%;">Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold">#{{ $item->id_keluar }}</td>
                        <td class="text-center fw-bold text-muted">Batch #{{ $item->id_masuk }}</td>
                        <td class="text-center text-muted">{{ $item->id_produk }}</td>
                        <td class="fw-bold">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                        <td class="text-end fw-bold" style="color: #ef4444;">-{{ $item->jumlah_keluar }} {{ $item->produk->satuan ?? '' }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_keluar)) }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada data pengeluaran stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- STOK TERSEDIA -->
    @if($tipeLaporan === 'stok_tersedia')
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">ID Produk</th>
                    <th>Nama Produk</th>
                    <th style="width: 18%;">Kategori</th>
                    <th style="width: 15%;">Merek</th>
                    <th style="width: 10%;">Total Masuk</th>
                    <th style="width: 10%;">Total Keluar</th>
                    <th style="width: 12%;">Stok tersedia</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold text-primary">{{ $item->id_produk }}</td>
                        <td class="fw-bold">{{ $item->nama_produk }}</td>
                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $item->merek }}</td>
                        <td class="text-end" style="color: #10b981;">+{{ $item->total_masuk }}</td>
                        <td class="text-end" style="color: #ef4444;">-{{ $item->total_keluar }}</td>
                        <td class="text-end fw-bold">{{ $item->stok_tersedia }} {{ $item->satuan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada data persediaan stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <!-- EXPIRED OR NEARING EXPIRED -->
    @if($tipeLaporan === 'mendekati_kadaluarsa' || $tipeLaporan === 'kadaluarsa')
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Batch ID</th>
                    <th style="width: 15%;">ID Produk</th>
                    <th>Nama Produk</th>
                    <th style="width: 12%;">Sisa Stok</th>
                    <th style="width: 15%;">Tgl Kadaluarsa</th>
                    <th style="width: 12%;">Sisa Hari</th>
                    <th style="width: 15%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($results as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold">#{{ $item->id_masuk }}</td>
                        <td class="text-center text-muted">{{ $item->id_produk }}</td>
                        <td class="fw-bold">{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                        <td class="text-end fw-bold">{{ $item->sisa_stok }} {{ $item->produk->satuan ?? '' }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                        <td class="text-center">{{ $item->sisa_hari }} hari</td>
                        <td class="text-center">
                            @if($item->status_kadaluarsa === 'mendekati')
                                <span class="status-badge status-mendekati">Mendekati</span>
                            @else
                                <span class="status-badge status-kadaluarsa">Kadaluarsa</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada data batch terfilter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        Vinaty Inventory System &copy; {{ date('Y') }} Vinaty Culinary. Laporan ini dicetak secara otomatis oleh sistem.
    </div>
</body>
</html>
