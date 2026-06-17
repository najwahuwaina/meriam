<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JurnalPdfController extends Controller
{
    public function laporanPdf(Request $request)
    {
        $periode = $request->get('periode', now()->format('Y-m'));
        [$tahun, $bulan] = explode('-', $periode);

        $bulanId = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',       '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',   '09' => 'September',
            '10' => 'Oktober', '11' => 'November',  '12' => 'Desember',
        ];

        $rows = JurnalDetail::query()
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

        $pdf = Pdf::loadView('pdf.jurnal-umum', [
            'mode'      => 'laporan',
            'nama_toko' => 'Geprek Meriam',
            'judul'     => 'Jurnal Umum',
            'periode'   => 'Periode ' . ($bulanId[$bulan] ?? '') . ' ' . $tahun,
            'rows'      => $rows,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('jurnal-umum-' . $periode . '.pdf');
    }

    public function transaksiPdf(Request $request)
    {
        $ids = $request->get('ids');

        $query = Jurnal::with(['jurnaldetail.akun']);

        if ($ids) {
            $query->whereIn('id', (array) $ids);
        }

        $jurnals = $query->orderBy('tanggal')->get();

        $pdf = Pdf::loadView('pdf.jurnal-umum', [
            'mode'      => 'transaksi',
            'nama_toko' => 'Geprek Meriam',
            'judul'     => 'Detail Transaksi Jurnal',
            'periode'   => 'Per ' . now()->format('d/m/Y'),
            'jurnals'   => $jurnals,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('jurnal-transaksi.pdf');
    }
}