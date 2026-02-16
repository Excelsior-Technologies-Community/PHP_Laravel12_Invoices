<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('invoices.index');
});

Route::resource('invoices', InvoiceController::class);
Route::post('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');