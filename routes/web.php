<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembelianBahanPDFController;
use App\Http\Controllers\MidtransController;

Route::get('/export/pembelian-bahan/pdf', [PembelianBahanPDFController::class, 'export'])
    ->name('export.pembelian-bahan.pdf');

Route::get('/payment/{id}', [MidtransController::class, 'payment'])
    ->name('payment');

Route::get('/payment/success/{id}', [MidtransController::class, 'paymentSuccess'])
    ->name('payment.success');

Route::get('/', function () {
    return view('welcome');
});