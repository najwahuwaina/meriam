<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use Illuminate\Support\Facades\Mail;
=======
use App\Http\Controllers\PembelianBahanPDFController;

Route::get('/export/pembelian-bahan/pdf', [PembelianBahanPDFController::class, 'export'])
    ->name('export.pembelian-bahan.pdf');
>>>>>>> f21a4d2 (nana)

use App\Http\Controllers\MidtransController;

use App\Mail\TesMail;

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