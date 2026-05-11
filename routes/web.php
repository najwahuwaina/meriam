<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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