<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('invoices.index');
});

Route::resource('invoices', InvoiceController::class);

/*
|--------------------------------------------------------------------------
| Invoice Status
|--------------------------------------------------------------------------
*/

Route::post(
    '/invoices/{invoice}/status',
    [InvoiceController::class, 'updateStatus']
)->name('invoices.status');

/*
|--------------------------------------------------------------------------
| PDF Download
|--------------------------------------------------------------------------
*/

Route::get(
    '/invoices/{invoice}/pdf',
    [InvoiceController::class, 'downloadPdf']
)->name('invoices.pdf');

/*
|--------------------------------------------------------------------------
| Send Invoice via Email
|--------------------------------------------------------------------------
*/

Route::post(
    '/invoices/{invoice}/send-email',
    [InvoiceController::class, 'sendEmail']
)->name('invoices.send-email');

/*
|--------------------------------------------------------------------------
| Public Invoice
|--------------------------------------------------------------------------
*/

Route::get(
    '/invoice/{token}/public',
    [InvoiceController::class, 'publicInvoice']
)->name('invoices.public');