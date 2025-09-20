<?php

use Illuminate\Support\Facades\Route;
use Modules\SalesInvoice\App\Http\Controllers\SalesInvoiceController;
use Modules\SalesInvoice\App\Http\Controllers\SalesProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'is_verify']], function () {
    //-----------------Sales route --------------------------------------//
    Route::resource('salesinvoice', SalesInvoiceController::class)->names('salesinvoice');
    Route::put('sale-update/{id}', [SalesInvoiceController::class, 'update'])->name('sale.update');
    Route::get('sale-delete/{id}',[ SalesInvoiceController::class,'destroy'])->name('sale.delete');
    Route::get('sale-get-data', [SalesInvoiceController::class, 'getData'])->name('sale.get.data');
    Route::get('sale-user-profile-get-data', [SalesInvoiceController::class, 'userProfileGetData'])->name('sale.user.profile.get.data');
    Route::get('sale-status/{id}/{status}', [SalesInvoiceController::class, 'changeStatus'])->name('sale.status.change');
    Route::get('invoice-profile/{phone}', [SalesProfileController::class, 'index'])->name('profile.index');
    Route::get('single-invoice-edit/{salesinvoice}', [SalesProfileController::class, 'edit'])->name('salesprofile.edit');
    Route::get('customer-search', [SalesInvoiceController::class, 'customerSearch'])->name('customer.search');
    Route::get('product-search', [SalesInvoiceController::class, 'Search'])->name('product.sale.search');
    Route::get('single-customer-data', [SalesProfileController::class, 'getData'])->name('single.customer.data');
    Route::post('due-amount-payment',[SalesInvoiceController::class,'dueAmoutPayment'])->name('due.amount.payment');
    Route::get('regenerate-paid-PDF/{id}',[SalesInvoiceController::class,'regeneratePaidPDF'])->name('regenerate.paid.PDF');
    Route::get('salesinvoice-invoice/{invoiceId}/download', [SalesInvoiceController::class, 'downloadInvoicePDF'])->name('salesinvoice.invoice.download');
    Route::get('print/download/{invoiceId}', [SalesInvoiceController::class, 'downloadPrint'])->name('download.print');
    Route::post('/send-email', [SalesInvoiceController::class, 'sendEmail'])->name('send.email');
});


Route::group(['prefix' => 'staff', 'as' => 'staff.', 'middleware' => ['auth','is_verify']], function () {
    //-----------------Sales route --------------------------------------//
    Route::resource('salesinvoice', SalesInvoiceController::class)->names('salesinvoice');
    Route::put('sale-update/{id}', [SalesInvoiceController::class, 'update'])->name('sale.update');
    Route::get('sale-delete/{id}',[ SalesInvoiceController::class,'destroy'])->name('sale.delete');
    Route::get('sale-get-data', [SalesInvoiceController::class, 'getData'])->name('sale.get.data');
    Route::get('sale-status/{id}/{status}', [SalesInvoiceController::class, 'changeStatus'])->name('sale.status.change');
    Route::get('invoice-profile/{phone}', [SalesProfileController::class, 'index'])->name('profile.index');
    Route::get('single-invoice-edit/{salesinvoice}', [SalesProfileController::class, 'edit'])->name('salesprofile.edit');
    Route::get('customer-search', [SalesInvoiceController::class, 'customerSearch'])->name('customer.search');
    Route::get('product-search', [SalesInvoiceController::class, 'Search'])->name('product.sale.search');
    Route::get('single-customer-data', [SalesProfileController::class, 'getData'])->name('single.customer.data');
    Route::post('due-amount-payment',[SalesInvoiceController::class,'dueAmoutPayment'])->name('due.amount.payment');
    Route::get('regenerate-paid-PDF/{id}',[SalesInvoiceController::class,'regeneratePaidPDF'])->name('regenerate.paid.PDF');
    Route::get('salesinvoice-invoice/{invoiceId}/download', [SalesInvoiceController::class, 'downloadInvoicePDF'])->name('salesinvoice.invoice.download');
    Route::get('print/download/{invoiceId}', [SalesInvoiceController::class, 'downloadPrint'])->name('download.print');
    Route::post('/send-email', [SalesInvoiceController::class, 'sendEmail'])->name('send.email');
});
