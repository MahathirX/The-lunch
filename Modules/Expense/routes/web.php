<?php

use Illuminate\Support\Facades\Route;
use Modules\Expense\App\Http\Controllers\ExpenseController;

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
    //-----------------Expense route --------------------------------------//
    Route::resource('expense', ExpenseController::class)->names('expense');
    Route::get('expense-data-get',[ExpenseController::class,'getData'])->name('expense.get.data');
    Route::get('expense-status/{id}/{status}',[ExpenseController::class,'statusChange'])->name('expense.status');
    Route::get('expense-delete/{id}',[ExpenseController::class,'destroy'])->name('expense.destroy');
});

Route::group(['prefix' => 'staff', 'as' => 'staff.', 'middleware' => ['auth','is_verify']], function () {
    //-----------------Expense route --------------------------------------//
    Route::resource('expense', ExpenseController::class)->names('expense');
    Route::get('expense-data-get',[ExpenseController::class,'getData'])->name('expense.get.data');
    Route::get('expense-status/{id}/{status}',[ExpenseController::class,'statusChange'])->name('expense.status');
    Route::get('expense-delete/{id}',[ExpenseController::class,'destroy'])->name('expense.destroy');
});
