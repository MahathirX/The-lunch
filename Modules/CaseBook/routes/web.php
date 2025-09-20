<?php

use Illuminate\Support\Facades\Route;
use Modules\CaseBook\App\Http\Controllers\CaseBookController;

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
    //-----------------Casebook route --------------------------------------//
    Route::get('cashbook', [CaseBookController::class, 'adminIndex'])->name('cashbook.index');
    Route::get('cashbook-delete/{id}', [CaseBookController::class, 'destroy'])->name('cashbook.delete');
    Route::post('cashbook-update/{id}', [CaseBookController::class, 'Update'])->name('cashbook.updates');
    Route::post('cashbook-sub-menu', [CaseBookController::class, 'casebookSubMenu'])->name('cashbook.sub.menu');
    Route::get('cashbook-report', [CaseBookController::class,'cashbookReport'])->name('cashbook-report');
    Route::post('cashbook-report-get-data', [CaseBookController::class,'cashbookReportGetData'])->name('cashbook.report.get.data');
    Route::get('cashbook-status-change/{id}/{status}', [CaseBookController::class, 'changeStatus'])->name('cashbook.status.change');
    Route::post('cashbook-details', [CaseBookController::class, 'aminCashBookDetails'])->name('cashbook.details');
});

Route::group(['prefix' => 'staff', 'as' => 'staff.', 'middleware' => ['auth','is_verify']], function () {
    //-----------------Casebook route --------------------------------------//
    Route::resource('cashbook', CaseBookController::class)->names('cashbook');
    Route::get('cashbook-report', [CaseBookController::class,'cashbookReport'])->name('cashbook-report');
    Route::post('cashbook-report-get-data', [CaseBookController::class,'cashbookReportGetData'])->name('cashbook.report.get.data');
    Route::post('cashbook-get-data', [CaseBookController::class, 'getData'])->name('cashbook.get.data');
    Route::get('cashbook-delete/{id}', [CaseBookController::class, 'destroy'])->name('cashbook.delete');
    Route::post('cashbook-update/{id}', [CaseBookController::class, 'Update'])->name('cashbook.updates');
    Route::post('cashbook-details', [CaseBookController::class, 'cashBookDetails'])->name('cashbook.details');
});
