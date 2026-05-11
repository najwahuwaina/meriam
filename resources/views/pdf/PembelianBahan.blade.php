<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian Bahan</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 4px 0;
            font-size: 11px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #eaeaea;
            border: 1px solid #444;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        td {
            border: 1px solid #444;
            padding: 6px;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge-lunas {
            color: green;
            font-weight: bold;
        }

        .badge-sebagian {
            color: orange;
            font-weight: bold;
        }

        .badge-belum {
            color: red;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>LAPORAN PEMBELIAN BAHAN BAKU</h2>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Kode</th>
                <th width="15%">Bahan Baku</th>
                <th width="10%">Tanggal</th>
                <th width="7%">Jumlah</th>
                <th width="12%">Harga</th>
                <th width="12%">Total</th>
                <th width="12%">Dibayar</th>
                <th width="10%">Sisa</th>
                <th width="8%">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $i => $row)
                <tr>
                    <td class="text-center">
                        {{ $i + 1 }}
                    </td>

                    <td>
                        {{ $row->kode_pembelian }}
                    </td>

                    <td>
                        {{ $row->bahanBaku->nama_bahan ?? '-' }}
                    </td>

                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y') }}
                    </td>

                    <td class="text-center">
                        {{ $row->jumlah }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($row->harga_beli, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($row->total_harga, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($row->dibayar, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($row->sisa, 0, ',', '.') }}
                    </td>

                    <td class="text-center">
                        @if($row->status_pembayaran == 'lunas')
                            <span class="badge-lunas">Lunas</span>
                        @elseif($row->status_pembayaran == 'sebagian')
                            <span class="badge-sebagian">Sebagian</span>
                        @else
                            <span class="badge-belum">Belum</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh sistem.
    </div>

</body>
</html>