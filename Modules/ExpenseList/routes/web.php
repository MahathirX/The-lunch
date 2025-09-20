<?php

use Illuminate\Support\Facades\Route;
use Modules\ExpenseList\App\Http\Controllers\ExpenseListController;

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
    Route::resource('expenselist', ExpenseListController::class)->names('expenselist');
    Route::get('expense-list-data-get',[ExpenseListController::class,'getData'])->name('expense.list.get.data');
    Route::get('expense-list-status/{id}/{status}',[ExpenseListController::class,'statusChange'])->name('expense.list.status');
    Route::post('expense-list-update/{id}',[ExpenseListController::class,'Update'])->name('expense.list.update');
    Route::get('expense-list-delete/{id}',[ExpenseListController::class,'destroy'])->name('expense.list.destroy');
});


Route::group(['prefix' => 'staff', 'as' => 'staff.', 'middleware' => ['auth','is_verify']], function () {
    //-----------------Expense List route --------------------------------------//
    Route::resource('expenselist', ExpenseListController::class)->names('expenselist');
    Route::get('expense-list-data-get',[ExpenseListController::class,'getData'])->name('expense.list.get.data');
    Route::post('expense-list-update/{id}',[ExpenseListController::class,'Update'])->name('expense.list.update');
    Route::get('expense-list-status/{id}/{status}',[ExpenseListController::class,'statusChange'])->name('expense.list.status');
    Route::get('expense-list-delete/{id}',[ExpenseListController::class,'destroy'])->name('expense.list.destroy');
});
