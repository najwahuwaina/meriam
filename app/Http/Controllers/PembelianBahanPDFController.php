<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahan;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianBahanPDFController extends Controller
{
    public function export()
    {
        $data = PembelianBahan::with('bahanBaku')->latest()->get();

        $pdf = Pdf::loadView('pdf.pembelian_bahan', compact('data'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pembelian-bahan.pdf');
    }
}