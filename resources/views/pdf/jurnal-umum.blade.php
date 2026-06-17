<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 16px; }
        .header h2 { margin: 0; font-size: 14px; }
        .header p { margin: 2px 0; font-size: 11px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead tr { background-color: #f0f0f0; }
        th { border: 1px solid #ccc; padding: 6px 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { border: 1px solid #ddd; padding: 5px 8px; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #f5f5f5; font-weight: bold; }
        .balance-ok { color: #16a34a; font-weight: bold; }
        .balance-fail { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 12px; font-size: 10px; color: #888; text-align: right; }
        .jurnal-block { margin-bottom: 20px; }
        .jurnal-header { background: #f9f9f9; border: 1px solid #ddd; padding: 6px 10px; margin-bottom: 4px; }
        .jurnal-header span { margin-right: 16px; }
        .label { color: #888; }
    </style>
</head>
<body>

<div class="header">
    <h2>{{ $nama_toko }}</h2>
    <p>{{ $judul }}</p>
    <p>{{ $periode }}</p>
</div>

@if ($mode === 'laporan')
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Reff</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Kredit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td class="text-center">{{ $row->jurnal_id }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $row->kode_akun }}</td>
                    <td>{{ $row->nama_akun }}</td>
                    <td>{{ $row->reff ?? '-' }}</td>
                    <td class="text-right">{{ $row->debit > 0 ? 'Rp ' . number_format($row->debit, 0, ',', '.') : '' }}</td>
                    <td class="text-right">{{ $row->kredit > 0 ? 'Rp ' . number_format($row->kredit, 0, ',', '.') : '' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($rows->sum('debit'), 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($rows->sum('kredit'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="7" class="text-center">
                    @if ($rows->sum('debit') == $rows->sum('kredit'))
                        <span class="balance-ok">✔ Balance</span>
                    @else
                        <span class="balance-fail">✘ Tidak Balance — Selisih: Rp {{ number_format(abs($rows->sum('debit') - $rows->sum('kredit')), 0, ',', '.') }}</span>
                    @endif
                </td>
            </tr>
        </tfoot>
    </table>
@endif

@if ($mode === 'transaksi')
    @foreach ($jurnals as $jurnal)
        <div class="jurnal-block">
            <div class="jurnal-header">
                <span><span class="label">Tanggal:</span> {{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d/m/Y') }}</span>
                <span><span class="label">No. Bukti:</span> {{ $jurnal->no_bukti ?? '-' }}</span>
                <span><span class="label">Keterangan:</span> {{ $jurnal->keterangan ?? '-' }}</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Keterangan Baris</th>
                        <th class="text-right">Debit</th>
                        <th class="text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jurnal->jurnaldetail as $detail)
                        <tr>
                            <td>{{ $detail->akun }}</td>
                            <td>{{ optional($detail->akun)->nama_akun ?? '-' }}</td>
                            <td>{{ $detail->deskripsi ?? '-' }}</td>
                            <td class="text-right">{{ $detail->debit > 0 ? 'Rp ' . number_format($detail->debit, 0, ',', '.') : '' }}</td>
                            <td class="text-right">{{ $detail->kredit > 0 ? 'Rp ' . number_format($detail->kredit, 0, ',', '.') : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Total</td>
                        <td class="text-right">Rp {{ number_format($jurnal->jurnaldetail->sum('debit'), 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($jurnal->jurnaldetail->sum('kredit'), 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endforeach
@endif

<div class="footer">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>

</body>
</html>