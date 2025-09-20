<?php

use Illuminate\Support\Facades\Route;
use Modules\Account\App\Http\Controllers\AccountController;

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
    Route::resource('account', AccountController::class)->names('account');
    Route::get('search-by-date',[AccountController::class,'filterByDate'])->name('filter.date');
});


