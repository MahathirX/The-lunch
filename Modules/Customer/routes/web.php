<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\App\Http\Controllers\CustomerController;

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
    Route::resource('customer', CustomerController::class)->names('customer');
    Route::put('customer-update/{id}',[ CustomerController::class,'Update'])->name('customer.update');
    Route::get('customer-delete/{id}',[ CustomerController::class,'destroy'])->name('customer.delete');
    Route::get('customer-get-data', [CustomerController::class,'getData'])->name('customer.get.data');
    Route::get('customer-status/{id}/{status}', [CustomerController::class,'changeStatus'])->name('customer.status');
});

Route::group(['prefix'=>'staff','as'=>'staff.','middlware'=>['auth'=>'is_verify']], function () {
    Route::resource('customer', CustomerController::class)->names('customer');
    Route::put('customer-update/{id}',[ CustomerController::class,'Update'])->name('customer.update');
    Route::get('customer-delete/{id}',[ CustomerController::class,'destroy'])->name('customer.delete');
    Route::get('customer-get-data', [CustomerController::class,'getData'])->name('customer.get.data');
    Route::get('customer-status/{id}/{status}', [CustomerController::class,'changeStatus'])->name('customer.status');
});
