<?php

use Illuminate\Support\Facades\Route;
use Modules\ProductTag\App\Http\Controllers\ProductTagController;

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
    Route::resource('producttag', ProductTagController::class)->names('producttag');
    Route::post('producttag-get-data', [ProductTagController::class,'getData'])->name('producttag.get.data');
    Route::post('producttag-update/{id}',[ ProductTagController::class,'Update'])->name('producttag.update');
    Route::get('producttag-delete/{id}',[ ProductTagController::class,'delete'])->name('producttag.delete');
    Route::get('producttag-status/{id}/{status}', [ProductTagController::class,'productTagStatus'])->name('producttag.status');
});
