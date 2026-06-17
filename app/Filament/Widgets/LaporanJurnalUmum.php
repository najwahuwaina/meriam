<?php

namespace App\Filament\Widgets;

use App\Models\JurnalDetail;
use Filament\Widgets\Widget;

class LaporanJurnalUmum extends Widget
{
    protected static string $view = 'filament.widgets.laporan-jurnal-umum';
    protected int | string | array $columnSpan = 'full';

    public string $periode = '';

    public function mount(): void
    {
        $this->periode = now()->format('Y-m');
    }

    public function filter(): void
    {
        // trigger re-render otomatis
    }

    public function getRows(): \Illuminate\Support\Collection
    {
        [$tahun, $bulan] = explode('-', $this->periode);

        return JurnalDetail::query()
            ->join('jurnals', 'jurnal_details.jurnal_id', '=', 'jurnals.id')
            ->join('akun', 'jurnal_details.akun', '=', 'akun.kode_akun')
            ->whereYear('jurnals.tanggal', $tahun)
            ->whereMonth('jurnals.tanggal', $bulan)
            ->select([
                'jurnals.id as jurnal_id',
                'jurnals.tanggal',
                'akun.kode_akun',
                'akun.nama_akun',
                'jurnals.no_bukti as reff',
                'jurnal_details.debit',
                'jurnal_details.kredit',
            ])
            ->orderBy('jurnals.tanggal')
            ->orderBy('jurnals.id')
            ->get();
    }

    public function getNamaBulan(): string
    {
        $bulanId = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',       '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',   '09' => 'September',
            '10' => 'Oktober', '11' => 'November',  '12' => 'Desember',
        ];
        [$tahun, $bulan] = explode('-', $this->periode);
        return ($bulanId[$bulan] ?? '') . ' ' . $tahun;
    }
}