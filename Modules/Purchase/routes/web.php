<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\App\Http\Controllers\PurchaseController;
use Modules\Purchase\App\Http\Controllers\StockProductController;

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
    //-----------------Purchase Route --------------------------------------//
    Route::resource('purchase', PurchaseController::class)->names('purchase');
    Route::post('purchase-product-search', [PurchaseController::class,'searchProducts'])->name('purchase.product.search');
    Route::post('purchase-get-data', [PurchaseController::class, 'getData'])->name('purchase.get.data');
    Route::get('purchase-status-change/{id}/{status}', [PurchaseController::class, 'changeStatus'])->name('purchase.status.change');
    Route::get('purchase-delete/{id}', [PurchaseController::class, 'destroy'])->name('purchase.delete');
    Route::get('purchase-product-search', [PurchaseController::class, 'Search'])->name('product.purchase.search');
    Route::post('purchase-update/{id}', [PurchaseController::class, 'Update'])->name('purchase.updates');
    Route::get('purchase-print/download/{invoiceId}', [PurchaseController::class, 'downloadPrint'])->name('purchase.download.print');
    Route::get('purchase-invoice/{invoiceId}/download', [PurchaseController::class, 'downloadInvoicePDF'])->name('purchase.invoice.download');
    Route::get('purchase-invoice/{invoiceId}/mail-send', [PurchaseController::class, 'downloadMailSend'])->name('purchase.invoice.mail.send');
    Route::get('stock-product-list', [StockProductController::class, 'StockProductList'])->name('stock.product.list');
    Route::get('stock-product-list-data', [StockProductController::class, 'getData'])->name('stock.product.list.data');
});

Route::group(['prefix' => 'staff', 'as' => 'staff.', 'middleware' => ['auth', 'is_verify']], function () {
    //-----------------Staff Purchase Route --------------------------------------//
    Route::get('/purchase',[PurchaseController::class, 'staffIndex'])->name('purchase.index');
    Route::get('/purchase-create',[PurchaseController::class, 'staffCreate'])->name('purchase.create');
    Route::get('/purchase-show/{purchase}',[PurchaseController::class, 'show'])->name('purchase.show');
    Route::get('/purchase-edit/{purchase}',[PurchaseController::class, 'staffEdit'])->name('purchase.edit');
    Route::post('/purchase-store',[PurchaseController::class, 'staffStore'])->name('purchase.store');
    Route::post('purchase-get-data', [PurchaseController::class, 'staffGetData'])->name('purchase.get.data');
    Route::get('purchase-status-change/{id}/{status}', [PurchaseController::class, 'changeStatus'])->name('purchase.status.change');
    Route::get('purchase-delete/{id}', [PurchaseController::class, 'destroy'])->name('purchase.delete');
    Route::get('purchase-product-search', [PurchaseController::class, 'Search'])->name('product.purchase.search');
    Route::post('purchase-update/{id}', [PurchaseController::class, 'staffUpdate'])->name('purchase.updates');
    Route::get('purchase-print/download/{invoiceId}', [PurchaseController::class, 'downloadPrint'])->name('purchase.download.print');
    Route::get('purchase-invoice/{invoiceId}/download', [PurchaseController::class, 'downloadInvoicePDF'])->name('purchase.invoice.download');
    Route::get('purchase-invoice/{invoiceId}/mail-send', [PurchaseController::class, 'downloadMailSend'])->name('purchase.invoice.mail.send');
    Route::get('stock-product-list', [StockProductController::class, 'StockProductList'])->name('stock.product.list');
    Route::get('stock-product-list-data', [StockProductController::class, 'getData'])->name('stock.product.list.data');
});
