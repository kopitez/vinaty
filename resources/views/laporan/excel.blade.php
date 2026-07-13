<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        .title {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .subtitle {
            font-size: 11pt;
            text-align: center;
            color: #555555;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 10pt;
        }
        th {
            background-color: #4f46e5;
            color: #ffffff;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 10px;
            text-align: center;
        }
        td {
            border: 1px solid #cbd5e1;
            padding: 8px;
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
        .text-success {
            color: #10b981;
        }
        .text-danger {
            color: #ef4444;
        }
        .text-warning {
            color: #f59e0b;
        }
        .bg-light {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="title">
        @if($tipeLaporan === 'stok_masuk') LAPORAN STOK MASUK INVENTORY
        @elseif($tipeLaporan === 'stok_keluar') LAPORAN STOK KELUAR INVENTORY
        @elseif($tipeLaporan === 'stok_tersedia') LAPORAN STOK TERSEDIA INVENTORY
        @elseif($tipeLaporan === 'mendekati_kadaluarsa') LAPORAN PRODUK MENDEKATI KADALUARSA
        @elseif($tipeLaporan === 'kadaluarsa') LAPORAN PRODUK KADALUARSA
        @endif
    </div>
    <div class="subtitle">
        Vinaty Culinary - {{ $periodeText }} (Waktu Export: {{ date('d M Y, H:i') }})
    </div>

    <!-- STOK MASUK TABLE -->
    @if($tipeLaporan === 'stok_masuk')
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">No</th>
                    <th style="width: 120px;">ID Batch</th>
                    <th style="width: 120px;">ID Produk</th>
                    <th style="width: 250px;">Nama Produk</th>
                    <th style="width: 100px;">Jumlah</th>
                    <th style="width: 100px;">Satuan</th>
                    <th style="width: 120px;">Tgl Masuk</th>
                    <th style="width: 120px;">Tgl Kadaluarsa</th>
                    <th style="width: 150px;">Operator</th>
                    <th style="width: 200px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold">#{{ $item->id_masuk }}</td>
                        <td class="text-center">{{ $item->id_produk }}</td>
                        <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                        <td class="text-end text-success fw-bold">{{ $item->jumlah_masuk }}</td>
                        <td>{{ $item->produk->satuan ?? '' }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_masuk)) }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- STOK KELUAR TABLE -->
    @if($tipeLaporan === 'stok_keluar')
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">No</th>
                    <th style="width: 120px;">ID Keluar</th>
                    <th style="width: 120px;">Batch Asal</th>
                    <th style="width: 120px;">ID Produk</th>
                    <th style="width: 250px;">Nama Produk</th>
                    <th style="width: 100px;">Jumlah</th>
                    <th style="width: 100px;">Satuan</th>
                    <th style="width: 120px;">Tgl Keluar</th>
                    <th style="width: 150px;">Operator</th>
                    <th style="width: 200px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold">#{{ $item->id_keluar }}</td>
                        <td class="text-center fw-bold">Batch #{{ $item->id_masuk }}</td>
                        <td class="text-center">{{ $item->id_produk }}</td>
                        <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                        <td class="text-end text-danger fw-bold">-{{ $item->jumlah_keluar }}</td>
                        <td>{{ $item->produk->satuan ?? '' }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_keluar)) }}</td>
                        <td>{{ $item->user->name ?? '-' }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- STOK TERSEDIA TABLE -->
    @if($tipeLaporan === 'stok_tersedia')
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">No</th>
                    <th style="width: 120px;">ID Produk</th>
                    <th style="width: 250px;">Nama Produk</th>
                    <th style="width: 150px;">Kategori</th>
                    <th style="width: 150px;">Merek</th>
                    <th style="width: 100px;">Satuan</th>
                    <th style="width: 120px;">Total Masuk</th>
                    <th style="width: 120px;">Total Keluar</th>
                    <th style="width: 150px;">Stok Tersedia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold text-primary">{{ $item->id_produk }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $item->merek }}</td>
                        <td class="text-center">{{ $item->satuan }}</td>
                        <td class="text-end text-success">+{{ $item->total_masuk }}</td>
                        <td class="text-end text-danger">-{{ $item->total_keluar }}</td>
                        <td class="text-end fw-bold">{{ $item->stok_tersedia }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- EXPIRED OR NEARING EXPIRED TABLE -->
    @if($tipeLaporan === 'mendekati_kadaluarsa' || $tipeLaporan === 'kadaluarsa')
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">No</th>
                    <th style="width: 120px;">ID Batch</th>
                    <th style="width: 120px;">ID Produk</th>
                    <th style="width: 250px;">Nama Produk</th>
                    <th style="width: 120px;">Sisa Stok</th>
                    <th style="width: 100px;">Satuan</th>
                    <th style="width: 150px;">Tgl Kadaluarsa</th>
                    <th style="width: 120px;">Sisa Hari</th>
                    <th style="width: 150px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center fw-bold">#{{ $item->id_masuk }}</td>
                        <td class="text-center">{{ $item->id_produk }}</td>
                        <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                        <td class="text-end fw-bold">{{ $item->sisa_stok }}</td>
                        <td>{{ $item->produk->satuan ?? '' }}</td>
                        <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                        <td class="text-center">{{ $item->sisa_hari }} hari</td>
                        <td class="text-center fw-bold {{ $item->status_kadaluarsa === 'kadaluarsa' ? 'text-danger' : 'text-warning' }}">
                            {{ strtoupper($item->status_kadaluarsa) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
