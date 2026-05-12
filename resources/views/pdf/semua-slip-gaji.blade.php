<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Semua Slip Gaji</title>

    <style>

        body{
            font-family: sans-serif;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table td,
        table th{
            border: 1px solid black;
            padding: 8px;
        }

        h2{
            text-align: center;
            margin-bottom: 20px;
        }

    </style>

</head>

<body>

<h2>DATA PENGGAJIAN KARYAWAN</h2>

<table>

    <thead>

        <tr>
            <th>Nama</th>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Alpa</th>
            <th>Gaji/Hari</th>
            <th>Total Gaji</th>
        </tr>

    </thead>

    <tbody>

    @foreach($records as $item)

        <tr>

            <td>
                {{ $item->karyawan->nama_karyawan }}
            </td>

            <td>

                @php

                    $bulan = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];

                @endphp

                {{ $bulan[$item->bulan] }}

            </td>

            <td>{{ $item->tahun }}</td>

            <td>{{ $item->jumlah_hadir }}</td>

            <td>{{ $item->jumlah_izin }}</td>

            <td>{{ $item->jumlah_sakit }}</td>

            <td>{{ $item->jumlah_alpa }}</td>

            <td>
                Rp {{ number_format($item->gaji_per_hari,0,',','.') }}
            </td>

            <td>
                Rp {{ number_format($item->total_gaji,0,',','.') }}
            </td>

        </tr>

    @endforeach

    </tbody>

</table>

</body>
</html>e