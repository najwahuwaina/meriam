<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\MidtransController;
use App\Http\Controllers\PembelianBahanPDFController;
use App\Http\Controllers\JurnalPdfController;

use App\Mail\TesMail;

Route::get('/login', function () {

    return redirect('/admin/login');

})->name('login');

Route::get(
    '/export/pembelian-bahan/pdf',
    [PembelianBahanPDFController::class, 'export']
)->name('export.pembelian-bahan.pdf');

Route::get('/', function () {

    return view('welcome');

});

Route::get(
    '/payment/{id}',
    [MidtransController::class, 'payment']
)->name('payment');

Route::post(
    '/midtrans/notification',
    [MidtransController::class, 'notification']
);

Route::get(
    '/payment-success/{id}',
    [MidtransController::class, 'paymentSuccess']
)->name('payment.success');

Route::get('/test-mail', function () {

    Mail::to('julianidebora77@gmail.com')
        ->send(new TesMail());

    return 'Email berhasil dikirim';

});

Route::middleware(['auth'])->group(function () {
    Route::get('/jurnal/pdf/laporan', [JurnalPdfController::class, 'laporanPdf'])->name('jurnal.pdf.laporan');
    Route::get('/jurnal/pdf/transaksi', [JurnalPdfController::class, 'transaksiPdf'])->name('jurnal.pdf.transaksi');
});