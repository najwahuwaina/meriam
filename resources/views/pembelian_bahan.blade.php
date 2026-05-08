<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembelian Bahan</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Pembelian Bahan Baku</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Bahan Baku</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Harga Beli</th>
                <th>Tagihan</th>
                <th>Dibayar</th>
                <th>Sisa</th>
                <th>Status</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->kode_pembelian }}</td>
                <td>{{ $row->bahanBaku->nama ?? '-' }}</td>
                <td>{{ $row->tanggal }}</td>
                <td>{{ $row->jumlah }}</td>
                <td>Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($row->tagihan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($row->dibayar, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($row->sisa, 0, ',', '.') }}</td>
                <td>{{ $row->status_pembayaran }}</td>
                <td>{{ $row->supplier ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>