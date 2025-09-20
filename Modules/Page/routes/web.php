<?php

use Illuminate\Support\Facades\Route;
use Modules\Page\App\Http\Controllers\PageController;

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
    //-----------------category route --------------------------------------//
    Route::resource('page', PageController::class)->names('page');
    Route::get('status-change/{id}/{status}', [PageController::class, 'changeStatus'])->name('page.status.change');
    Route::post('page-get-data', [PageController::class, 'getData'])->name('page.get.data');
    Route::get('page-delete/{id}', [PageController::class, 'destroy'])->name('page.delete');
    Route::delete('page-image-delete/{id}', [PageController::class, 'galleryImageDelete'])->name('page.image.delete');
    Route::post('page-update/{id}', [PageController::class, 'Update'])->name('page.updates');
});
