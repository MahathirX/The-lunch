<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MainIndexController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Subscriber\App\Http\Controllers\SubscriberController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//------------ Disable Route ------------//
Auth::routes([
    'register'         => false,
    'verify'           => false,
    'password.reset'   => false,
    'password.update'  => false,
    'password.email'   => false,
    'password.request' => false
]);
//------------ Subcriber Store  ------------//
Route::post('/subcriber-store', [MainIndexController::class, 'subcriberStore'])->name('frontend.subscriber.store');

//------------ Home Route ------------//
Route::get('/', [MainIndexController::class, 'index'])->name('index');

//------------ Dynamic Style CSS ------------//
Route::get('/dynamic-style.css', [MainIndexController::class, 'generateCSS'])->name('dynamic.style');


//------------ Register Route ------------//
Route::get('register', [AuthController::class, 'index'])->name('user.register');
Route::post('register/store', [AuthController::class, 'store'])->name('user.register.store');

//------------ Varify User ------------//
Route::get('verify-code/{token}', [AuthController::class, 'verifiedCode'])->name('verify.code');

//------------ Forgot password  ------------//
Route::get('forgot-passowrd', [AuthController::class, 'forgotPassword'])->name('forgot.password');
Route::post('forgot-passowrd/sent', [AuthController::class, 'forgotPasswordSent'])->name('forgot.password.sent');
Route::get('forgot-passowrd-token/{token}', [AuthController::class, 'forgotPasswordToken'])->name('forgot.password.token');
Route::post('password-update', [AuthController::class, 'passwordUpdate'])->name('user.password.update');

//------------ Category Details Show  ------------//
Route::get('/categories/{slug}', [MainIndexController::class, 'show'])->name('categories.show');
Route::post('category-search', [MainIndexController::class, 'categorySearch'])->name('category.search');


//------------ Product Details  ------------//
Route::get('/product/{id}', [MainIndexController::class, 'productshow'])->name('view.product');
Route::post('product-search', [MainIndexController::class, 'productSearch'])->name('product.search');
Route::post('product-view-search', [MainIndexController::class, 'productViewSearch'])->name('product.view.search');
Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');

//------------ Cart Start  ------------//
Route::get('/veiw-cart', [MainIndexController::class, 'veiwcart'])->name('view.cart');
Route::get('/updatecart', [MainIndexController::class, 'updatecart'])->name('cart.update');
Route::post('/updatecartview', [MainIndexController::class, 'updatecartview'])->name('cart.update.view');
Route::post('/removecart', [MainIndexController::class, 'removecart'])->name('cart.remove');
Route::post('/cart/update', [MainIndexController::class, 'update'])->name('cart.update');
Route::post('add-to-cart', [MainIndexController::class, 'addToCart'])->name('add.to.cart');
Route::get('check-out', [MainIndexController::class, 'checkout'])->name('check.out');
Route::post('order-store', [OrderController::class, 'orderStore'])->name('oreder.store');
Route::post('landing-page-order', [OrderController::class, 'landingPageOrder'])->name('landing.page.order.store');
Route::post('product-qnt-update', [MainIndexController::class, 'productQuntityUpdate'])->name('product.qnt.update');


//------------ Product Reveiw  ------------//
Route::post('/review/store', [ReviewController::class, 'store'])->name('review.store');
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'is_verify']], function () {
    Route::get('review/index', [ReviewController::class, 'index'])->name('review.index');
    Route::post('review-get-data', [ReviewController::class, 'getData'])->name('review.get.data');
    Route::get('review-delete/{id}', [ReviewController::class, 'destroy'])->name('review.delete');
    Route::get('review-status/{id}/{status}', [ReviewController::class, 'ReviewStatus'])->name('review.status');
});


//------------ Pages Setting  ------------//
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact-submit', [PageController::class, 'contactFrom'])->name('contact.form.submit');
Route::get('/about-us', [PageController::class, 'aboutus'])->name('aboutus');
Route::get('/shop', [PageController::class, 'shop'])->name('shop');
Route::get('/shop/{slug}', [PageController::class, 'shopByBrand'])->name('shop.by.brand');
Route::post('/shop-category-search', [PageController::class, 'shopCategorySearch'])->name('shop.category.search');
Route::get('/order-success',[PageController::class,'orderSuccess'])->name('order.success.page');

//------------ Single Page  ------------//
Route::get('/{slug}', [PageController::class, 'singlePage'])->name('single.page');

Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => ['auth', 'is_verify']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('dashboard-update', [DashboardController::class, 'dashboardUpdate'])->name('dashboard.update');
    Route::post('dashboard-password-update', [DashboardController::class, 'dashboardPasswordUpdate'])->name('dashboard.password.update');
});





