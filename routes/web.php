<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembelianBahanPDFController;

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/export/pembelian-bahan/pdf', [PembelianBahanPDFController::class, 'export'])
    ->name('export.pembelian-bahan.pdf');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});