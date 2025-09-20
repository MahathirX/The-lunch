<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\App\Http\Controllers\BrandController;

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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth','is_verify']], function () {
    //-----------------Brand route --------------------------------------//
    Route::resource('brand', BrandController::class)->names('brand');
    Route::post('brand-get-data', [BrandController::class,'getData'])->name('brand.get.data');
    Route::get('brand-delete/{id}', [BrandController::class,'destroy'])->name('brand.delete');
    Route::post('brand-update/{id}', [BrandController::class,'update'])->name('brand.updates');
    Route::get('brand-status/{id}/{status}', [BrandController::class,'brandStatus'])->name('brand.status');
});


Route::group(['prefix' => 'staff', 'as' => 'staff.', 'middleware' => ['auth','is_verify']], function () {
    //-----------------Brand route --------------------------------------//
    Route::resource('brand', BrandController::class)->names('brand');
    Route::post('brand-get-data', [BrandController::class,'getData'])->name('brand.get.data');
    Route::get('brand-delete/{id}', [BrandController::class,'destroy'])->name('brand.delete');
    Route::post('brand-update/{id}', [BrandController::class,'update'])->name('brand.updates');
    Route::get('brand-status/{id}/{status}', [BrandController::class,'brandStatus'])->name('brand.status');
});
