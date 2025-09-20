<?php

use Illuminate\Support\Facades\Route;
use Modules\Supplier\App\Http\Controllers\SupplierController;

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
    Route::resource('supplier', SupplierController::class)->names('supplier');
    Route::get('supplier-get-data',[SupplierController::class,'getData'])->name('supplier.get.data');
    Route::get('supplier-delete/{id}',[SupplierController::class,'destroy'])->name('supplier.destroy');
    Route::get('supplier-delete/{id}/{status}',[SupplierController::class,'changeStatus'])->name('supplier.status');
    Route::get('supplier/profile/{id}',[SupplierController::class,'profile'])->name('supplier.profile');
    Route::post('supplier-due-amount-payment',[SupplierController::class,'dueAmoutPayment'])->name('supplier.due.amount.payment');
    Route::get('supplier-user-due-get-data',[SupplierController::class,'userDueGetData'])->name('supplier.user.due.get.data');
    Route::get('supplier-purchase-get-data',[SupplierController::class,'purchaseGetData'])->name('supplier.purchase.get.data');
    Route::get('supplier-regenerate-paid-PDF/{id}',[SupplierController::class,'regeneratePaidPDF'])->name('supplier.regenerate.paid.PDF');
});

Route::group(['prefix'=>'staff','as'=>'staff.','middlware'=>['auth'=>'is_verify']], function () {
    Route::resource('supplier', SupplierController::class)->names('supplier');
    Route::get('supplier-get-data',[SupplierController::class,'getData'])->name('supplier.get.data');
    Route::get('supplier-delete/{id}',[SupplierController::class,'destroy'])->name('supplier.destroy');
    Route::get('supplier-delete/{id}/{status}',[SupplierController::class,'changeStatus'])->name('supplier.status');
    Route::get('supplier/profile/{id}',[SupplierController::class,'profile'])->name('supplier.profile');
    Route::post('supplier-due-amount-payment',[SupplierController::class,'dueAmoutPayment'])->name('supplier.due.amount.payment');
    Route::get('supplier-user-due-get-data',[SupplierController::class,'userDueGetData'])->name('supplier.user.due.get.data');
    Route::get('supplier-purchase-get-data',[SupplierController::class,'purchaseGetData'])->name('supplier.purchase.get.data');
    Route::get('supplier-regenerate-paid-PDF/{id}',[SupplierController::class,'regeneratePaidPDF'])->name('supplier.regenerate.paid.PDF');
});
