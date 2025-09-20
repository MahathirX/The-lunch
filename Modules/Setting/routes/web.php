<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\App\Http\Controllers\SettingController;

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
    //-----------------Setting Route --------------------------------------//
    Route::resource('setting', SettingController::class)->names('setting')->except(['show']);
    Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
        Route::resource('setting', SettingController::class)->names('setting');

        Route::get('header-section-style',[SettingController::class,'headerSectionStyle'])->name('header.section.style');
        Route::post('header-section-style-store',[SettingController::class,'headerSectionStyleStore'])->name('header.section.style.store');
        Route::get('slider-section-style',[SettingController::class,'sliderSectionStyle'])->name('slider.section.style');
        Route::post('slider-section-style-store',[SettingController::class,'sliderSectionStyleStore'])->name('slider.section.style.store');
        Route::get('category-section-style',[SettingController::class,'categorySectionStyle'])->name('category.section.style');
        Route::post('category-section-style-store',[SettingController::class,'categorySectionStyleStore'])->name('category.section.style.store');
        Route::get('product-card-section-style',[SettingController::class,'productCardSectionStyle'])->name('product.card.section.style');
        Route::post('product-card-section-style-store',[SettingController::class,'productCardSectionStyleStore'])->name('product.card.section.style.store');
        Route::get('brand-section-style',[SettingController::class,'brandectionStyle'])->name('brand.section.style');
        Route::post('brand-section-style-store',[SettingController::class,'brandSectionStyleStore'])->name('brand.section.style.store');
        Route::get('subcribe-section-style',[SettingController::class,'subcribeSectionStyle'])->name('subcribe.section.style');
        Route::post('subcribe-section-style-store',[SettingController::class,'subcribeSectionStyleStore'])->name('subcribe.section.style.store');
        Route::get('footer-section-style',[SettingController::class,'footerSectionStyle'])->name('footer.section.style');
        Route::post('footer-section-style-store',[SettingController::class,'footerSectionStyleStore'])->name('footer.section.style.store');
        Route::get('breadcrumb-section-style',[SettingController::class,'breadcrumbSectionStyle'])->name('breadcrumb.section.style');
        Route::post('breadcrumb-section-style-store',[SettingController::class,'breadcrumbSectionStyleStore'])->name('breadcrumb.section.style.store');
        Route::get('product-details-section-style',[SettingController::class,'productDetailsSectionStyle'])->name('product.details.section.style');
        Route::post('product-details-section-style-store',[SettingController::class,'productDetailsSectionStyleStore'])->name('product.details.section.style.store');
        Route::get('category-details-section-style',[SettingController::class,'categoryDetailsSectionStyle'])->name('category.details.section.style');
        Route::post('category-details-section-style-store',[SettingController::class,'categoryDetailsSectionStyleStore'])->name('category.details.section.style.store');
        Route::get('login-section-style',[SettingController::class,'loginSectionStyle'])->name('login.section.style');
        Route::post('login-section-style-store',[SettingController::class,'loginSectionStyleStore'])->name('login.section.style.store');
        Route::get('check-out-section-style',[SettingController::class,'checkOutSectionStyle'])->name('check.out.section.style');
        Route::post('check-out-section-style-store',[SettingController::class,'checkOutSectionStyleStore'])->name('check.out.section.style.store');
        Route::get('view-cart-section-style',[SettingController::class,'viewCartSectionStyle'])->name('view.cart.section.style');
        Route::post('view-cart-section-style-store',[SettingController::class,'viewCartSectionStyleStore'])->name('view.cart.section.style.store');

        Route::get('section-show-hide-style',[SettingController::class,'sectionShowHideStyle'])->name('section.show.hide.style');
        Route::post('section-show-hide-style-store',[SettingController::class,'sectionShowHideStyleStore'])->name('section.show.hide.style.store');

        Route::post('section-title-store',[SettingController::class,'SectionTitleStore'])->name('section.title.store');
        Route::post('category-section-store',[SettingController::class,'categorySectionStore'])->name('category.section.store');
        Route::post('cart-page-store',[SettingController::class,'cartPageStore'])->name('cart.page.store');
        Route::post('account-page-store',[SettingController::class,'accountPageStore'])->name('account.page.store');
        Route::post('socal-media-store',[SettingController::class,'socalMediaStore'])->name('socal.media.store');
        Route::post('invoice-note-store',[SettingController::class,'invoiceNoteStore'])->name('invoice.note.store');
        Route::post('top-selling-product',[SettingController::class,'topSellingProduct'])->name('top.selling.product');
        Route::post('contact-info-store',[SettingController::class,'contactInfoStore'])->name('contact.info.store');
        Route::post('about-info-store',[SettingController::class,'aboutInfoStore'])->name('about.info.store');
        Route::get('typography-index',[SettingController::class,'typographyIndex'])->name('typography.index');
        Route::post('typography-store',[SettingController::class,'typographyStore'])->name('typography.store');
        Route::post('shop-meta-store',[SettingController::class,'shopMetaStore'])->name('shop-meta.store');
        Route::get('courier',[SettingController::class,'courierIndex'])->name('courier.index');
        Route::post('stead-fast-store',[SettingController::class,'steadFastStore'])->name('stead.fast.store');
        Route::post('pathao-store',[SettingController::class,'pathaoStore'])->name('pathao.store');
        Route::post('footer-section-store',[SettingController::class,'footerSectionStore'])->name('footer.section.store');
    });
});
