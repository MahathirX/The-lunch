<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\App\Http\Controllers\BrandController;
use Modules\Category\App\Http\Controllers\CategoryController;
use Modules\Expense\App\Http\Controllers\ExpenseController;
use Modules\ExpenseList\App\Http\Controllers\ExpenseListController;
use Modules\Product\App\Http\Controllers\ProductController;
use Modules\SalesInvoice\App\Http\Controllers\SalesInvoiceController;
use Modules\SalesInvoice\App\Http\Controllers\SalesProfileController;
use Modules\StaffDashboad\App\Http\Controllers\StaffDashboadController;


Route::group(['prefix' => 'staff', 'as' => 'staff.', 'middleware' => ['auth','is_verify']], function () {
    //------------------------ Dashboard -----------------------//
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [StaffDashboadController::class, 'dashboard'])->name('index');
    });
});



