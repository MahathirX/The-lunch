<?php

use Illuminate\Support\Facades\Route;
use Modules\Attribute\App\Http\Controllers\AttributeController;

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

// Route::group([], function () {
//     Route::resource('attribute', AttributeController::class)->names('attribute');
// });
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth','is_verify']], function () {
    Route::resource('attribute', AttributeController::class)->names('attribute');
    Route::post('atribute-get-data', [AttributeController::class,'getData'])->name('atribute.get.data');
    Route::get('atribute-delete/{id}', [AttributeController::class,'delete'])->name('atribute.delete');
    Route::get('atribute-status/{id}/{status}', [AttributeController::class,'atributeStatus'])->name('atribute.status');
});
