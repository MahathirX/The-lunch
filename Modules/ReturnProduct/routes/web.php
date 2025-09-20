<?php

use Illuminate\Support\Facades\Route;
use Modules\ReturnProduct\App\Http\Controllers\ReturnProductController;

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


Route::group(['prefix'=>'admin','as'=>'admin.','middlware'=>['auth'=>'is_verify']], function () {
   Route::resource('returnproduct', ReturnProductController::class)->names('returnproduct');
   Route::get('returnproduct-get-data', [ReturnProductController::class, 'getData'])->name('returnproduct.get.data');
   Route::get('/returnproduct-invoice-search',[ReturnProductController::class,'seacrhInvoice'])->name('returnproduct.invoice.search');
   Route::get('/returnproduct-search',[ReturnProductController::class,'seacrhReturnProduct'])->name('returnproduct.search');
   Route::get('returnproduct-status/{id}/{status}', [ReturnProductController::class, 'changeStatus'])->name('returnproduct.status.change');
   Route::get('returnproduct-delete/{id}',[ ReturnProductController::class,'destroy'])->name('returnproduct.delete');
   Route::get('returnproduct-print/download/{id}', [ReturnProductController::class, 'downloadPrint'])->name('returnproduct.download.print');
   Route::get('invoice/{id}/download', [ReturnProductController::class, 'downloadInvoicePDF'])->name('returnproduct.invoice.download');


   Route::get('returnpurchase-index', [ReturnProductController::class,'purchasReturnIndex'])->name('returnpurchase.index');
   Route::get('returnpurchase', [ReturnProductController::class,'purchasReturncreate'])->name('returnpurchase.create');
   Route::get('returnpurchase-get-data', [ReturnProductController::class, 'PurchaseGetData'])->name('returnpurchase.get.data');
   Route::get('/returnpurchase-invoice-search',[ReturnProductController::class,'seacrhPurchaseInvoice'])->name('returnpurchase.invoice.search');
   Route::get('/returnpurchase-search',[ReturnProductController::class,'seacrhReturnPurchase'])->name('returnpurchase.search');
   Route::post('/returnpurchase-store',[ReturnProductController::class,'purchaseStore'])->name('returnpurchase.store');
   Route::get('/returnpurchase-show/{id}',[ReturnProductController::class,'purchaseShow'])->name('returnpurchase.show');
   Route::get('/returnpurchase-delete/{id}',[ReturnProductController::class,'returnpurchaseDelete'])->name('returnpurchase.delete');
   Route::get('returnpurchase-status/{id}/{status}', [ReturnProductController::class, 'returnPurchaseChangeStatus'])->name('returnpurchase.status.change');
   Route::get('returnpurchase-print/download/{id}', [ReturnProductController::class, 'returnPurchaseDownloadPrint'])->name('returnpurchase.download.print');
   Route::get('returnpurchase/{id}/download', [ReturnProductController::class, 'returnPurchasedownloadInvoicePDF'])->name('returnpurchase.invoice.download');

});

Route::group(['prefix'=>'staff','as'=>'staff.','middlware'=>['auth'=>'is_verify']], function () {
    Route::resource('returnproduct', ReturnProductController::class)->names('returnproduct');
    Route::get('returnproduct-get-data', [ReturnProductController::class, 'getData'])->name('returnproduct.get.data');
    Route::get('/returnproduct-invoice-search',[ReturnProductController::class,'seacrhInvoice'])->name('returnproduct.invoice.search');
    Route::get('/returnproduct-search',[ReturnProductController::class,'seacrhReturnProduct'])->name('returnproduct.search');
    Route::get('returnproduct-status/{id}/{status}', [ReturnProductController::class, 'changeStatus'])->name('returnproduct.status.change');
    Route::get('returnproduct-delete/{id}',[ ReturnProductController::class,'destroy'])->name('returnproduct.delete');
    Route::get('returnproduct-print/download/{id}', [ReturnProductController::class, 'downloadPrint'])->name('returnproduct.download.print');
    Route::get('invoice/{id}/download', [ReturnProductController::class, 'downloadInvoicePDF'])->name('returnproduct.invoice.download');
});
