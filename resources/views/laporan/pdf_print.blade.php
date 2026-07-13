<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Vinaty Inventory System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #333;
            padding: 20px;
        }
        .print-header {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .table th {
            background-color: #f8fafc !important;
            color: black !important;
            border-color: #ddd !important;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Print Trigger Bar -->
        <div class="row no-print mb-4 p-3 bg-light border rounded-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <span><strong>Mode Cetak Fallback:</strong> Gunakan menu cetak browser Anda untuk menyimpan sebagai PDF.</span>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" onclick="window.print()"><i class="fa-solid fa-print"></i> Cetak Sekarang</button>
                    <button class="btn btn-secondary" onclick="window.close()">Tutup Halaman</button>
                </div>
            </div>
        </div>

        <div class="print-header text-center">
            <h2 class="fw-bold text-uppercase m-0">Vinaty Culinary</h2>
            <h4 class="fw-semibold m-0 mt-1">
                @if($tipeLaporan === 'stok_masuk') LAPORAN STOK MASUK INVENTORY
                @elseif($tipeLaporan === 'stok_keluar') LAPORAN STOK KELUAR INVENTORY
                @elseif($tipeLaporan === 'stok_tersedia') LAPORAN PERSIDIAAN STOK (TERSEDIA)
                @elseif($tipeLaporan === 'mendekati_kadaluarsa') LAPORAN PRODUK MENDEKATI KADALUARSA
                @elseif($tipeLaporan === 'kadaluarsa') LAPORAN PRODUK KADALUARSA
                @endif
            </h4>
            <p class="text-muted m-0 mt-1">{{ $periodeText }} | Waktu Cetak: {{ date('d M Y, H:i') }}</p>
        </div>

        <!-- STOK MASUK TABLE -->
        @if($tipeLaporan === 'stok_masuk')
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>ID Batch</th>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center">Tanggal Masuk</th>
                        <th class="text-center">Tanggal Expired</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold">#{{ $item->id_masuk }}</td>
                            <td>{{ $item->id_produk }}</td>
                            <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                            <td class="text-end fw-bold text-success">+{{ $item->jumlah_masuk }} {{ $item->produk->satuan ?? '' }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_masuk)) }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- STOK KELUAR TABLE -->
        @if($tipeLaporan === 'stok_keluar')
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>ID Keluar</th>
                        <th>Batch Asal</th>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center">Tanggal Keluar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold">#{{ $item->id_keluar }}</td>
                            <td>Batch #{{ $item->id_masuk }}</td>
                            <td>{{ $item->id_produk }}</td>
                            <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                            <td class="text-end fw-bold text-danger">-{{ $item->jumlah_keluar }} {{ $item->produk->satuan ?? '' }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_keluar)) }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- STOK TERSEDIA TABLE -->
        @if($tipeLaporan === 'stok_tersedia')
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Merek</th>
                        <th class="text-end">Total Masuk</th>
                        <th class="text-end">Total Keluar</th>
                        <th class="text-end">Stok Tersedia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold text-primary">{{ $item->id_produk }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $item->merek }}</td>
                            <td class="text-end text-success">+{{ $item->total_masuk }}</td>
                            <td class="text-end text-danger">-{{ $item->total_keluar }}</td>
                            <td class="text-end fw-bold">{{ $item->stok_tersedia }} {{ $item->satuan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- EXPIRED / NEARING EXPIRED TABLE -->
        @if($tipeLaporan === 'mendekati_kadaluarsa' || $tipeLaporan === 'kadaluarsa')
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>ID Batch</th>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                        <th class="text-end">Sisa Stok</th>
                        <th class="text-center">Tanggal Kadaluarsa</th>
                        <th class="text-center">Sisa Hari</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="fw-bold">#{{ $item->id_masuk }}</td>
                            <td>{{ $item->id_produk }}</td>
                            <td>{{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                            <td class="text-end fw-bold">{{ $item->sisa_stok }} {{ $item->produk->satuan ?? '' }}</td>
                            <td class="text-center">{{ date('d/m/Y', strtotime($item->tanggal_kadaluarsa)) }}</td>
                            <td class="text-center">{{ $item->sisa_hari }} hari</td>
                            <td class="text-center fw-bold">
                                {{ strtoupper($item->status_kadaluarsa) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <script>
        // Automatically trigger print on load
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
