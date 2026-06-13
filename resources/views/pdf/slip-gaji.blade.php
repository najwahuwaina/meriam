<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .judul { text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 8px; border: 1px solid black; }
        .total { font-weight: bold; background: #f2f2f2; }
        .section-header { background: #d9d9d9; font-weight: bold; }
    </style>
</head>
<body>

    <div class="judul">
        <h2>SLIP GAJI KARYAWAN</h2>
    </div>

    <table>
        <tr>
            <td>Nama Karyawan</td>
            <td>{{ $penggajian->karyawan->nama_karyawan }}</td>
        </tr>
        <tr>
            <td>Bulan</td>
            <td>
                @php
                    $bulan = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                        4 => 'April', 5 => 'Mei', 6 => 'Juni',
                        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                    ];
                @endphp
                {{ $bulan[$penggajian->bulan] }}
            </td>
        </tr>
        <tr>
            <td>Tahun</td>
            <td>{{ $penggajian->tahun }}</td>
        </tr>

        <tr><td colspan="2" class="section-header">Kehadiran</td></tr>
        <tr>
            <td>Jumlah Hadir</td>
            <td>{{ $penggajian->jumlah_hadir }} hari</td>
        </tr>
        <tr>
            <td>Jumlah Izin</td>
            <td>{{ $penggajian->jumlah_izin }} hari</td>
        </tr>
        <tr>
            <td>Jumlah Sakit</td>
            <td>{{ $penggajian->jumlah_sakit }} hari</td>
        </tr>
        <tr>
            <td>Jumlah Alpa</td>
            <td>{{ $penggajian->jumlah_alpa }} hari</td>
        </tr>

        <tr><td colspan="2" class="section-header">Komponen Gaji</td></tr>
        <tr>
            <td>Gaji per Hari</td>
            <td>Rp {{ number_format($penggajian->gaji_per_hari, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Gaji Pokok ({{ $penggajian->jumlah_hadir }} hari x Rp {{ number_format($penggajian->gaji_per_hari, 0, ',', '.') }})</td>
            <td>Rp {{ number_format($penggajian->jumlah_hadir * $penggajian->gaji_per_hari, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="2" class="section-header">Tunjangan</td></tr>
        <tr>
            <td>Tunjangan Transport ({{ $penggajian->jumlah_hadir }} hari x Rp {{ number_format($penggajian->tunjangan_transport, 0, ',', '.') }})</td>
            <td>Rp {{ number_format($penggajian->jumlah_hadir * $penggajian->tunjangan_transport, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tunjangan Makan ({{ $penggajian->jumlah_hadir }} hari x Rp {{ number_format($penggajian->tunjangan_makan, 0, ',', '.') }})</td>
            <td>Rp {{ number_format($penggajian->jumlah_hadir * $penggajian->tunjangan_makan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Tunjangan</td>
            <td>Rp {{ number_format($penggajian->total_tunjangan, 0, ',', '.') }}</td>
        </tr>

        <tr class="total">
            <td>Total Gaji (Gaji Pokok + Tunjangan)</td>
            <td>Rp {{ number_format($penggajian->total_gaji, 0, ',', '.') }}</td>
        </tr>
    </table>

</body>
</html>