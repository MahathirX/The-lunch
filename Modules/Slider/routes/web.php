<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\App\Http\Controllers\SliderController;

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
    //slider route
    Route::get('home-slider', [SliderController::class,'indexSlider'])->name('home.slider');
    Route::get('home-slider-create', [SliderController::class,'createSlider'])->name('home.slider.create');
    Route::post('home-slider-store', [SliderController::class,'storeSlider'])->name('home.slider.store');
    Route::post('get-data', [SliderController::class,'getData'])->name('home.get.data');

    Route::get('slider-edit/{id}', [SliderController::class,'edit'])->name('slider.edit');
    Route::post('slider-update/{id}', [SliderController::class,'sliderUpdate'])->name('slider.update');
    Route::get('status/{id}/{status}', [SliderController::class,'sliderStatus'])->name('slider.status');
    Route::get('slider-delete/{id}', [SliderController::class,'destroy'])->name('slider.delete');
});


