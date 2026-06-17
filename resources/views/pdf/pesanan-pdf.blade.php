<!DOCTYPE html>
<html>

<head>

    <title>Laporan Pesanan</title>

    <style>

        body{
            font-family: sans-serif;
        }

        h2{
            text-align: center;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td{
            border: 1px solid black;
        }

        th, td{
            padding: 10px;
            text-align: left;
        }

        th{
            background-color: #f2f2f2;
        }

    </style>

</head>

<body>

    <h2>Laporan Data Pesanan</h2>

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Pelanggan</th>
                <th>Karyawan</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>

            </tr>

        </thead>

        <tbody>

            @foreach ($pesanan as $item)

                <tr>

                    <td>{{ $item->id_pesanan }}</td>

                    <td>
                        {{ $item->pelanggan->nama_pelanggan }}
                    </td>

                    <td>
                        {{ $item->karyawan->nama_karyawan }}
                    </td>

                    <td>{{ $item->tgl_pesanan }}</td>

                    <td>
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </td>

                    <td>{{ $item->status }}</td>

                </tr>

            @endforeach

        </tbody>

    </table>

</body>

</html>