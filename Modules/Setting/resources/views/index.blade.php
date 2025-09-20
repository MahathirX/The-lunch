@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <style>
        #cropped_result img {
            width: 120px;
        }

        #cropped_result_secondary img {
            width: 120px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{  __f('Theme Setting Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="themeSettingForm" action="{{ route('admin.setting.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('App Name Title') }}" parantClass="col-12 col-md-6" name="company_name"
                            placeholder="{{ __f('App Name Placeholder') }}" errorName="company_name" class="py-2"
                            value="{!! config('settings.company_name') ?? old('company_name') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Company Email Title') }}" parantClass="col-12 col-md-6" name="company_email"
                            placeholder="{{ __f('Company Email Placeholder') }}" errorName="company_email" class="py-2" type="email"
                            value="{!! config('settings.company_email') ?? old('company_email') !!}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Company Cell Title') }}" parantClass="col-12 col-md-6" name="company_cell"
                            type="tel" placeholder="{{ __f('Company Cell Placeholder') }}" errorName="company_cell" class="py-2"
                            value="{!! config('settings.company_cell') ?? old('company_cell') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Company Copy Right Title') }}" parantClass="col-12 col-md-6"
                            name="company_copy_right" placeholder="{{ __f('Company Copy Right Placeholder') }}" errorName="company_copy_right"
                            class="py-2" value="{!! config('settings.company_copy_right') ?? old('company_copy_right') !!}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Currency Icon Title') }}" parantClass="col-12 col-md-6" name="currency"
                            type="text" placeholder="{{ __f('Currency Icon Placeholder') }}" errorName="currency" class="py-2"
                            value="{!! config('settings.currency') ?? old('currency') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Browse Categories Title') }}" parantClass="col-12 col-md-6"
                            name="browse_cetegories_title" placeholder="{{ __f('Browse Categories Placeholder') }}"
                            errorName="browse_cetegories_title" class="py-2"
                            value="{!! config('settings.browse_cetegories_title') ?? old('browse_cetegories_title') !!}"></x-form.textbox>
                    </div>
                    {{-- <div class="row mt-3">
                        <x-form.textbox labelName="M-Menu Title" parantClass="col-12 col-md-6" name="mmenutitle"
                            type="text" placeholder="Enter M-Menu Title..!" errorName="mmenutitle" class="py-2"
                            value="{{ config('settings.mmenutitle') ?? old('mmenutitle') }}"></x-form.textbox>

                        <x-form.textbox labelName="M Section Title" parantClass="col-12 col-md-6" name="msectiontitle"
                            placeholder="Enter  Browse Categories Title..!" errorName="msectiontitle" class="py-2"
                            value="{{ config('settings.msectiontitle') ?? old('msectiontitle') }}"></x-form.textbox>
                    </div> --}}

                    {{-- <div class="row mt-3">
                        <x-form.textbox labelName="Price Slider Min Range" parantClass="col-12 col-md-6"
                            name="price_min_range" type="number" placeholder="Enter Price Slider Min Range..!"
                            errorName="price_min_range" class="py-2"
                            value="{{ config('settings.price_min_range') ?? old('price_min_range') }}"></x-form.textbox>

                        <x-form.textbox labelName="Price Slider Max Range" parantClass="col-12 col-md-6"
                            name="price_max_range" type="number" placeholder="Enter  Price Slider Max Range..!"
                            errorName="price_max_range" class="py-2"
                            value="{{ config('settings.price_max_range') ?? old('price_max_range') }}"></x-form.textbox>
                    </div> --}}

                    <div class="row mt-3">
                        <x-form.textbox labelName="{{  __f('Delivery Charge Inside Dhaka Title') }}" parantClass="col-12 col-md-6"
                            name="deliveryinsidedhake" type="number" placeholder="{{ __f('Delivery Charge Inside Dhaka Placeholder') }}"
                            errorName="deliveryinsidedhake" class="py-2"
                            value="{{ config('settings.deliveryinsidedhake') ?? old('deliveryinsidedhake') }}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Delivery Charge Outside Dhaka Title') }} " parantClass="col-12 col-md-6"
                            name="deliveryoutsidedhake" type="number"
                            placeholder="{{ __f('Delivery Charge Outside Dhaka Placeholder') }} " errorName="deliveryoutsidedhake"
                            class="py-2"
                            value="{{ config('settings.deliveryoutsidedhake') ?? old('deliveryoutsidedhake') }}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Contact Email Title') }}" parantClass="col-12 col-md-6" name="admincontactmail"
                            type="email" placeholder="{{ __f('Contact Email Placeholder') }}" errorName="admincontactmail"
                            class="py-2" value="{!! config('settings.admincontactmail') ?? old('admincontactmail') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Add To Cart Btn Title') }}" parantClass="col-12 col-md-6"
                            name="addtocartbtntitle" type="text" placeholder="{{ __f('Add To Cart Btn Placeholder') }}"
                            errorName="addtocartbtntitle" class="py-2"
                            value="{!! config('settings.addtocartbtntitle') ?? old('addtocartbtntitle') !!}"></x-form.textbox>
                    </div>
                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Subcribe Title Title') }}" parantClass="col-12 col-md-6" name="subcribetitle"
                            type="text" placeholder="{{ __f('Subcribe Title Placeholder') }}" errorName="subcribetitle"
                            class="py-2" value="{!! config('settings.subcribetitle') ?? old('subcribetitle') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Subcribe Description Title') }}" parantClass="col-12 col-md-6"
                            name="subsribesdecription" type="text" placeholder="{{ __f('Subcribe Description Placeholder') }}"
                            errorName="subsribesdecription" class="py-2"
                            value="{!! config('settings.subsribesdecription') ?? old('subsribesdecription') !!}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="message-text" class="col-form-label">{{ __f('Company Primary Logo Title') }}  :</label>
                                    <input type="file" id="companyPrimaryLogo" accept="image/*" class="form-control">
                                    <img id="companyPrimaryLogoPriview" style="max-width: 100%;">
                                    <div id="cropped_result" style="width: 120px;"></div>
                                    <canvas id="canvas" style="display:none;"></canvas>
                                    <button id="crop_button" class="btn btn-primary text-white d-none"
                                        type="button">Crop</button>
                                </div>
                                <div class="col-md-6">
                                    <label for="message-text" class="col-form-label">{{ __f('Preview Primary Logo Title') }}  :</label>
                                    @if (config('settings.company_primary_logo') !== null)
                                        <img class="w-100" src="{{ asset(config('settings.company_primary_logo')) }}"
                                            alt="company primary logo">
                                    @endif
                                </div>
                            </div>
                            {{-- <label class="text-dark font-weight-medium">Company Primary Logo</label>
                            <div>
                                <label class="first__picture" for="first__image" tabIndex="0">
                                    <span class="picture__first"></span>
                                </label>
                                <input type="file" name="company_primary_logo" id="first__image" accept="image/*">
                                <span class="text-danger error-text image-error"></span>
                            </div> --}}
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="message-text" class="col-form-label">{{ __f('Company Secondary Logo Title') }}  :</label>
                                    <input type="file" id="companySecondaryLogo" accept="image/*"
                                        class="form-control">
                                    <img id="companySecondaryLogoPriview" style="max-width: 100%;">
                                    <div id="cropped_result_secondary" style="width: 120px;"></div>
                                    <canvas id="canvas" style="display:none;"></canvas>
                                    <button id="crop_button_secondary" class="btn btn-primary text-white d-none"
                                        type="button">Crop</button>
                                </div>
                                <div class="col-md-6">
                                    <label for="message-text" class="col-form-label">{{ __f('Preview Secondary Logo Title') }}  :</label>
                                    @if (config('settings.company_secondary_logo') !== null)
                                        <img class="w-100" src="{{ asset(config('settings.company_secondary_logo')) }}"
                                            alt="company secondary logo">
                                    @endif
                                </div>
                            </div>
                            {{-- <label class="text-dark font-weight-medium">Company Secondary Logo</label>
                            <div>
                                <label class="second_picture" for="second__image" tabIndex="0">
                                    <span class="picture__second"></span>
                                </label>
                                <input type="file" name="company_secondary_logo" id="second__image" accept="image/*">
                                <span class="text-danger error-text threed_image-error"></span>
                            </div> --}}
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium">{{ __f('Favicon 32*32 Title') }}</label>
                            <div>
                                <label class="third_picture" for="third__image" tabIndex="0">
                                    <span class="picture_third_image"></span>
                                </label>
                                <input type="file" name="favicon_first" id="third__image" accept="image/*">
                                <span class="text-danger error-text image-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium">{{ __f('Favicon 16*16 Title') }}</label>
                            <div>
                                <label class="fourth_picture" for="fourth__image" tabIndex="0">
                                    <span class="picture_fourth_image"></span>
                                </label>
                                <input type="file" name="favicon_second" id="fourth__image" accept="image/*">
                                <span class="text-danger error-text threed_image-error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        {{-- <div class="col-md-6">
                            <label class="text-dark font-weight-medium">Order Success Page Image</label>
                            <div>
                                <label class="fivth_picture" for="fivth__image" tabIndex="0">
                                    <span class="picture_fivth_image"></span>
                                </label>
                                <input type="file" name="ordersuccessimage" id="fivth__image" accept="image/*">
                                <span class="text-danger error-text image-error"></span>
                            </div>
                        </div> --}}
                        <x-form.textbox labelName="{{ __f('Order Success Page Text Title') }}" parantClass="col-12 col-md-6"
                            name="ordersuccesstext" type="text" placeholder="{{ __f('Order Success Page Text Placeholder') }}"
                            errorName="ordersuccesstext" class="py-2"
                            value="{{ config('settings.ordersuccesstext') ?? old('ordersuccesstext') }}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="{{ __f('Home Page Meta Title Title') }}" parantClass="col-12 col-md-6"
                            name="homemetatitle" type="text" errorName="homemetatitle" class="summernote py-2"
                            placeholder="{{ __f('Home Page Meta Placeholder') }}"
                            value="{{ config('settings.homemetatitle') ?? old('homemetatitle') }}"></x-form.textarea>

                        <x-form.textarea labelName="{{ __f('Home Page Meta Description Title') }}" parantClass="col-12 col-md-6"
                            name="homemetadescription" type="text" placeholder="{{ __f('Home Page Meta Description Placeholder') }}"
                            errorName="homemetadescription" class="summernote py-2"
                            value="{{ config('settings.homemetadescription') ?? old('homemetadescription') }}"></x-form.textarea>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="{{ __f('Home Page Meta Keyword Title') }}" parantClass="col-12 col-md-6"
                            name="homemetakeyword" type="text" placeholder="{{ __f('Home Page Meta Keyword Placeholder') }}"
                            errorName="homemetakeyword" class="summernote py-2"
                            value="{{ config('settings.homemetakeyword') ?? old('homemetakeyword') }}"></x-form.textarea>

                        <x-form.textarea labelName="{{ __f('Pixcel Setup Title') }}" parantClass="col-12 col-md-6"
                            optionalText="{{ __f('Pixcel Setup Placeholder') }}" name="pixcelsetupcode" type="text"
                            placeholder="{{ __f('Pixcel Setup Placeholder') }}" errorName="pixcelsetupcode" class="py-2"
                            value="{{ config('settings.pixcelsetupcode') ?? old('pixcelsetupcode') }}"></x-form.textarea>
                    </div>
                    <div class="row mt-3">
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="commingsoonmode"
                            labelName="{{ __f('Coming Soon Mode Title') }}" errorName="commingsoonmode">
                            <option value="0" {{ config('settings.commingsoonmode') == 0 ? 'selected' : '' }}>{{ __f('OFF Title') }}
                            </option>
                            <option value="1" {{ config('settings.commingsoonmode') == 1 ? 'selected' : '' }}>{{ __f('ON Title') }}
                            </option>
                        </x-form.selectbox>
                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium">{{ __f('Breadcrumb Background Image Title') }} </label>
                            <div>
                                <label class="fivth_picture" for="fivth__image" tabIndex="0">
                                    <span class="picture_fivth_image"></span>
                                </label>
                                <input type="file" name="breadcrumbbackgroundimage" id="fivth__image"
                                    accept="image/*">
                                <span class="text-danger error-text image-error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none theme_setting_loader" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f('Submit Title') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Section Title Setting Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="sectionSettingForm" action="{{ route('admin.setting.section.title.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Home Page Product Title Title') }} " parantClass="col-12 col-md-6"
                            name="deals_product_title" placeholder="{{ __f('Home Page Product Placeholder') }}"
                            errorName="deals_product_title" class="py-2" type="text"
                            value="{!! config('settings.deals_product_title') ?? old('deals_product_title') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Development By Title') }}" parantClass="col-12 col-md-6" name="development_by"
                            placeholder="{{ __f('Development By Placeholder') }}" errorName="development_by" class="py-2"
                            type="text" value="{!! config('settings.development_by') ?? old('development_by') !!}"></x-form.textbox>
                    </div>
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Menu Title Title') }}" parantClass="col-12 col-md-6" name="menu_title"
                            placeholder="{{ __f('Menu Title Placeholder') }}" errorName="menu_title" class="py-2"
                            value="{{ config('settings.menu_title') ?? old('menu_title') }}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Brands Title Title') }}" parantClass="col-12 col-md-6" name="bands_title"
                            type="text" placeholder="{{ __f('Brands Title Placeholder') }}" errorName="bands_title" class="py-2"
                            value="{{ config('settings.bands_title') ?? old('bands_title') }}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Category Title Title') }}" parantClass="col-12 col-md-6" name="category_title"
                            placeholder="{{ __f('Category Title Placeholder') }}" errorName="category_title" class="py-2"
                            value="{{ config('settings.category_title') ?? old('category_title') }}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Deals Product Title Title') }} " parantClass="col-12 col-md-6"
                            name="deals_product_title" placeholder="{{ __f('Deals Product Placeholder') }}"
                            errorName="deals_product_title" class="py-2" type="text"
                            value="{{ config('settings.deals_product_title') ?? old('deals_product_title') }}"></x-form.textbox>
                    </div>
                    <div class="row mt-3">
                        <x-form.textarea labelName="{{ __f('Product Single Page Warning Text Title') }}" parantClass="col-12 col-md-6"
                            name="productsinglepagewarningtext" type="text"
                            placeholder="{{ __f('Product Single Page Warning Text Placeholder') }}" errorName="productsinglepagewarningtext"
                            class="summernote py-2" value="{!! config('settings.productsinglepagewarningtext') ?? old('productsinglepagewarningtext') !!}"></x-form.textarea>

                        <x-form.textbox labelName="{{ __f('Product Single Page Title Title') }}" parantClass="col-12 col-md-6"
                            name="product_singpage_title" type="text"
                            placeholder="{{ __f('Product Single Page Placeholder') }}" errorName="product_singpage_title"
                            class="py-2" value="{!! config('settings.product_singpage_title') ?? old('product_singpage_title') !!}"></x-form.textbox>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none section_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f('Submit Title') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Cart Page Settings Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="cartSettingForm" action="{{ route('admin.setting.cart.page.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Cart Page Title Title') }}" parantClass="col-12 col-md-6" name="cartpagetitle"
                            placeholder="{{ __f('Cart Page Title Placeholder') }}" errorName="cartpagetitle" class="py-2"
                            type="text" value="{!! config('settings.cartpagetitle') ?? old('cartpagetitle') !!}"></x-form.textbox>

                        <x-form.textarea labelName="{{ __f('Cart Page Warning Text Title') }}" parantClass="col-12 col-md-6"
                            name="cartpagewarningtext" type="text" placeholder="{{ __f('Cart Page Warning Text Placeholder') }}"
                            errorName="cartpagewarningtext" class="summernote py-2"
                            value="{!! config('settings.cartpagewarningtext') ?? old('cartpagewarningtext') !!}"></x-form.textarea>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none cart_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f('Submit Title') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Account Settings Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="accountSettingForm" action="{{ route('admin.setting.account.page.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Login Title Title') }}" parantClass="col-12 col-md-6" name="logintitle"
                            placeholder="{{ __f('Login Placeholder') }}" errorName="logintitle" class="py-2" type="text"
                            value="{!! config('settings.logintitle') ?? old('logintitle') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Login Page Register Title') }}" parantClass="col-12 col-md-6"
                            name="loginpageregistertitle" placeholder="{{ __f('Login Page Register Title Placeholder') }}"
                            errorName="loginpageregistertitle" class="py-2" type="text"
                            value="{!! config('settings.loginpageregistertitle') ?? old('loginpageregistertitle') !!}"></x-form.textbox>
                    </div>
                    <div class="row">
                        <x-form.textarea labelName="{{ __f('Login Page Register Description Title') }}" parantClass="col-12 col-md-6"
                            name="loginpageregisterdescription" type="text"
                            placeholder="{{ __f('Login Page Register Description Placeholder') }}" errorName="loginpageregisterdescription"
                            class="py-2" value="{!! config('settings.loginpageregisterdescription') ?? old('loginpageregisterdescription') !!}"></x-form.textarea>

                        <x-form.textbox labelName="{{ __f('Register Page Warning Text Title') }}" parantClass="col-12 col-md-6"
                            name="registerpagewarningtext" placeholder="{{ __f('Register Page Warning Text Placeholder') }}"
                            errorName="registerpagewarningtext" class="py-2" type="text"
                            value="{!! config('settings.registerpagewarningtext') ?? old('registerpagewarningtext') !!}"></x-form.textbox>
                    </div>
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Register Page First Title Title') }}" parantClass="col-12 col-md-6"
                            name="registerpagefirsttitle" placeholder="{{ __f('Register Page First Placeholder') }}"
                            errorName="registerpagefirsttitle" class="py-2" type="text"
                            value="{!! config('settings.registerpagefirsttitle') ?? old('registerpagefirsttitle') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Register Page Second Title Title') }}" parantClass="col-12 col-md-6"
                            name="registerpagesecondtitle" placeholder="{{ __f('Register Page Second Placeholder') }}"
                            errorName="registerpagesecondtitle" class="py-2" type="text"
                            value="{!! config('settings.registerpagesecondtitle') ?? old('registerpagesecondtitle') !!}"></x-form.textbox>
                    </div>

                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Register Page Third Title Title') }}" parantClass="col-12 col-md-6"
                            name="registerpagethirdtitle" placeholder="{{ __f('Register Page Third Placeholder') }}"
                            errorName="registerpagethirdtitle" class="py-2" type="text"
                            value="{!! config('settings.registerpagethirdtitle') ?? old('registerpagethirdtitle') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Forgot Page Title Title') }}" parantClass="col-12 col-md-6"
                            name="forgotpagetitle" placeholder="{{ __f('Forgot Page Placeholder') }}" errorName="forgotpagetitle"
                            class="py-2" type="text" value="{!! config('settings.forgotpagetitle') ?? old('forgotpagetitle') !!}"></x-form.textbox>

                    </div>

                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Forgot Page Warning Text Title') }}" parantClass="col-12 col-md-6"
                            name="forgotpagewarningtext" placeholder="{{ __f('Forgot Page Warning Text Placeholder') }}"
                            errorName="forgotpagewarningtext" class="py-2" type="text"
                            value="{!! config('settings.forgotpagewarningtext') ?? old('forgotpagewarningtext') !!}"></x-form.textbox>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none account_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f('Submit Title') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Socal Media Settings Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="socalMediaSettingForm" action="{{ route('admin.setting.socal.media.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <x-form.textbox labelName="{{ __f('Facebook Url Title') }}" parantClass="col-12 col-md-6" name="facebookurl"
                            placeholder="{{ __f('Facebook Url Placeholder') }}" errorName="facebookurl" class="py-2" type="url"
                            value="{!! config('settings.facebookurl') ?? old('facebookurl') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Instagram Url Title') }}" parantClass="col-12 col-md-6" name="instagramurl"
                            placeholder="{{ __f('') }}" errorName="instagramurl" class="py-2" type="text"
                            placeholder="{{ __f('') }}" errorName="instagramurl" class="py-2" type="text"
                            value="{!! config('settings.instagramurl') ?? old('instagramurl') !!}"></x-form.textbox>
                    </div> 
                    <div class="row mb-3">
                        <x-form.textbox labelName="{{ __f('Youtube Url Title') }}" parantClass="col-12 col-md-6" name="youtubeurl"
                            placeholder="{{ __f('Youtube Url Placeholder') }}" errorName="youtubeurl" class="py-2" type="text"
                            value="{!! config('settings.youtubeurl') ?? old('youtubeurl') !!}"></x-form.textbox>

                            <x-form.textbox labelName="{{ __f('Linkedin Url Title') }}" parantClass="col-12 col-md-6" name="linkedinurl"
                            placeholder="{{ __f('Linkedin Url Placeholder') }}" errorName="linkedinurl" class="py-2" type="text"
                            value="{!! config('settings.linkedinurl') ?? old('linkedinurl') !!}"></x-form.textbox>
                    </div>
                    <div class="row mb-3">
                        <x-form.textbox labelName="{{ __f('Twitter Url Title') }}" parantClass="col-12 col-md-6" name="twitterurl"
                            placeholder="{{ __f('Twitter Url Placeholder') }}" errorName="twitterurl" class="py-2" type="text"
                            value="{!! config('settings.twitterurl') ?? old('twitterurl') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Pinterest Url Title') }}" parantClass="col-12 col-md-6" name="pinteresturl"
                            placeholder="{{ __f('Pinterest Url Placeholder') }}" errorName="pinteresturl" class="py-2" type="text"
                            value="{!! config('settings.pinteresturl') ?? old('pinteresturl') !!}"></x-form.textbox>
                    </div>
                    
                    <div class="row mb-3">
                        <x-form.textbox labelName="{{ __f('Skype Url Title') }}" parantClass="col-12 col-md-6" name="skypeurl"
                            placeholder="{{ __f('Skype Url Placeholder') }}" errorName="skypeurl" class="py-2" type="text"
                            value="{!! config('settings.skypeurl') ?? old('skypeurl') !!}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Whatsapp Url Title') }}" parantClass="col-12 col-md-6" name="whatsappurl"
                            placeholder="{{ __f('Whatsapp Url Placeholder') }}" errorName="whatsappurl" class="py-2" type="text"
                            value="{!! config('settings.whatsappurl') ?? old('whatsappurl') !!}"></x-form.textbox>
                    </div>

                    <div class="row mb-3">
                        <x-form.textbox labelName="{{ __f('Reddit Url Title') }}" parantClass="col-12 col-md-6" name="redditurl"
                            placeholder="{{ __f('Reddit Url Placeholder') }}" errorName="redditurl" class="py-2" type="text"
                            value="{!! config('settings.redditurl') ?? old('redditurl') !!}"></x-form.textbox>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none socal_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f('Submit Title') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Top Selling Product Add</h3>
            </div>
            @php
                 $top_selling_products = json_decode(config('settings.topsellingproducts'));
            @endphp
            <div class="card-body">
                <form id="topSellingProductForm" action="{{ route('admin.setting.top.selling.product') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Top Selling Product <span class="text-warning fs-7">(Select Maximum 9
                                    Items)</span></label>
                            <select class="form-select" id="multiple-select-field" data-placeholder="Choose a Products"
                                multiple name="topsellingproducts[]">
                                @forelse ($products as $product)
                                    <option value="{{ $product->id ?? '' }}" @isset($top_selling_products) {{ in_array($product->id, $top_selling_products) ? 'selected' : '' }}
                                        @endisset>{{ $product->name ?? '' }}</option>
                                @empty
                                @endforelse
                            </select>
                            <div class="text-danger topsellingproducts-error"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none top_selling_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>Submit
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}

        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Choose Category For Hot Deals & Section</h3>
            </div>
            <div class="card-body">
                <form id="categoryProductSettingForm" action="{{ route('admin.setting.category.section.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @php
                            $category_product = json_decode(config('settings.category_product'));
                            $category_section = json_decode(config('settings.category_section'));
                        @endphp
                        <div class="col-md-6">
                            <label for="">Hot Deals Category <span class="text-warning fs-7">(Select Maximum 4
                                    Items)</span></label>
                            <select class="form-select" id="multiple-select-field" data-placeholder="Choose a Category"
                                multiple name="category_product[]">
                                @forelse ($categories as $categorie)
                                    <option value="{{ $categorie->id ?? '' }}"
                                        @isset($category_product) {{ in_array($categorie->id, $category_product) ? 'selected' : '' }}
                                    @endisset>
                                        {{ $categorie->name ?? '' }}</option>
                                @empty
                                @endforelse
                            </select>
                            <div class="text-danger category_product-error"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="">Section Category <span class="text-warning fs-7"></span></label>
                            <select class="form-select" id="multiple-select2-field" data-placeholder="Choose a Category"
                                multiple name="category_section[]">
                                @forelse ($categories as $categorie)
                                    <option value="{{ $categorie->id ?? '' }}"
                                        @isset($category_section) {{ in_array($categorie->id, $category_section) ? 'selected' : '' }} @endisset>
                                        {{ $categorie->name ?? '' }}</option>
                                @empty
                                @endforelse
                            </select>

                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none section_category_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Contact Page Settings</h3>
            </div>
            <div class="card-body">
                <form id="contactFromSettingForm" action="{{ route('admin.setting.contact.info.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <x-form.textarea labelName="Contact Content" parantClass="col-12 col-md-6"
                            name="contactcontent" type="text" placeholder="Contact Content"
                            errorName="contactcontent" class="summernote py-2"
                            value="{{ config('settings.contactcontent') ?? old('contactcontent') }}"></x-form.textarea>

                        <x-form.textarea labelName="Contact Page Meta Keyword" parantClass="col-12 col-md-6"
                            name="contactmetakeyword" type="text" placeholder="Contact Page Meta Keyword"
                            errorName="contactmetakeyword" class="summernote py-2"
                            value="{{ config('settings.contactmetakeyword') ?? old('contactmetakeyword') }}"></x-form.textarea>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="Contact Page Meta Title" parantClass="col-12 col-md-6"
                            name="contactmetatitle" type="text" errorName="contactmetatitle" class="summernote py-2"
                            placeholder="Contact Page Meta Title"
                            value="{{ config('settings.contactmetatitle') ?? old('contactmetatitle') }}"></x-form.textarea>

                        <x-form.textarea labelName="Contact Page Meta Description" parantClass="col-12 col-md-6"
                            name="contactmetadescription" type="text" placeholder="Contact Page Meta Description"
                            errorName="contactmetadescription" class="summernote py-2"
                            value="{{ config('settings.contactmetadescription') ?? old('contactmetadescription') }}"></x-form.textarea>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none conatact_page_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>Submit
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}

        {{-- <div class="card">
            <div class="card-heading">
                <h3 class="p-2">About Us Page Settings</h3>
            </div>
            <div class="card-body">
                <form id="aboutFromSettingForm" action="{{ route('admin.setting.about.info.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <x-form.textarea labelName="About Content" parantClass="col-12 col-md-6"
                            name="aboutcontent" type="text" placeholder="About Content"
                            errorName="aboutcontent" class="summernote py-2"
                            value="{{ config('settings.aboutcontent') ?? old('aboutcontent') }}"></x-form.textarea>

                        <x-form.textarea labelName="About Page Meta Keyword" parantClass="col-12 col-md-6"
                            name="aboutmetakeyword" type="text" placeholder="About Page Meta Keyword"
                            errorName="aboutmetakeyword" class="summernote py-2"
                            value="{{ config('settings.aboutmetakeyword') ?? old('aboutmetakeyword') }}"></x-form.textarea>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="About Page Meta Title" parantClass="col-12 col-md-6"
                            name="aboutmetatitle" type="text" errorName="aboutmetatitle" class="summernote py-2"
                            placeholder="About Page Meta Title"
                            value="{{ config('settings.aboutmetatitle') ?? old('aboutmetatitle') }}"></x-form.textarea>

                        <x-form.textarea labelName="About Page Meta Description" parantClass="col-12 col-md-6"
                            name="aboutmetadescription" type="text" placeholder="About Page Meta Description"
                            errorName="aboutmetadescription" class="summernote py-2"
                            value="{{ config('settings.aboutmetadescription') ?? old('aboutmetadescription') }}"></x-form.textarea>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none about_page_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>Submit
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}

        {{-- <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Shop Page Settings</h3>
            </div>
            <div class="card-body">
                <form id="shopFromSettingForm" action="{{ route('admin.setting.shop-meta.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3">
                        <x-form.textarea labelName="Shop Page Meta Title" parantClass="col-12 col-md-6"
                        name="shopmetatitle" type="text" errorName="shopmetatitle" class="summernote py-2"
                        placeholder="Shop Page Meta Title"
                        value="{{ config('settings.shopmetatitle') ?? old('shopmetatitle') }}"></x-form.textarea>

                        <x-form.textarea labelName="Shop Page Meta Keyword" parantClass="col-12 col-md-6"
                            name="shopmetakeyword" type="text" placeholder="Shop Page Meta Keyword"
                            errorName="shopmetakeyword" class="summernote py-2"
                            value="{{ config('settings.shopmetakeyword') ?? old('shopmetakeyword') }}"></x-form.textarea>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="Shop Page Meta Description" parantClass="col-12 col-md-6"
                            name="shopmetadescription" type="text" placeholder="Shop Page Meta Description"
                            errorName="shopmetadescription" class="summernote py-2"
                            value="{{ config('settings.shopmetadescription') ?? old('shopmetadescription') }}"></x-form.textarea>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none shop_page_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>Submit
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}

        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Invoice Note Settings Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="invoiceNoteSettings" action="{{ route('admin.setting.invoice.note.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textarea labelName="{{ __f('Sales Invoice Note Title') }}" parantClass="col-12 col-md-12"
                            name="sales_invoice_note" type="text" placeholder="{{ __f('Sales Invoice Note Placeholder') }}"
                            errorName="sales_invoice_note" class="py-2"
                            value="{{ config('settings.sales_invoice_note') ?? old('sales_invoice_note') }}"></x-form.textarea>
                    </div>
                    <div class="row mt-3">
                        <x-form.textarea labelName="{{ __f('Purchase Invoice Note Title') }}" parantClass="col-12 col-md-12"
                            name="purchase_invoice_note" type="text" placeholder="{{ __f('Purchase Invoice Note Placeholder') }}"
                            errorName="purchase_invoice_note" class="py-2"
                            value="{{ config('settings.purchase_invoice_note') ?? old('purchase_invoice_note') }}"></x-form.textarea>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="">{{ __f('Authority Signature Image Title') }} </label>
                            <div class="form-check col-12 col-md-6">
                                @if (config('settings.authority_signature_status') == '1')
                                    <input class="form-check-input" type="radio" name="authority_signature_status"
                                        id="showauthrityimage" checked value="1">
                                @else
                                    <input class="form-check-input" type="radio" name="authority_signature_status"
                                        id="showauthrityimage" value="1" checked>
                                @endif
                                <label class="form-check-label" for="showauthrityimage">
                                    {{ __f('Show Authority Signature Image Title') }}
                                </label>
                            </div>
                            <div class="form-check">
                                @if (config('settings.authority_signature_status') == '2')
                                    <input class="form-check-input" type="radio" name="authority_signature_status"
                                        id="hideauthrityimage" checked value="2">
                                @else
                                    <input class="form-check-input" type="radio" name="authority_signature_status"
                                        id="hideauthrityimage" value="2">
                                @endif
                                <label class="form-check-label" for="hideauthrityimage">
                                   {{ __f('Hide Authority Signature Image Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium">{{ __f('Authority Signature Image Title') }}</label>
                            <div>
                                <label class="seventh_picture" for="seventh__image" tabIndex="0">
                                    <span class="picture_seventh_image"></span>
                                </label>
                                <input type="file" name="authority_signature_image" id="seventh__image">
                                <span class="text-danger error-text image-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Invoice Footer Text Title') }}" parantClass="col-12 col-md-6"
                            name="invoicefootertext" placeholder="{{ __f('Invoice Footer Text Placeholder') }}"
                            errorName="invoicefootertext" class="py-2" type="text"
                            value="{{ config('settings.invoicefootertext') ?? old('invoicefootertext') }}"></x-form.textbox>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none invoice_note_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f('Submit Title') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Footer Settings Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="footerSettings" action="{{ route('admin.setting.footer.section.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textarea labelName="{{ __f('Footer Description Title') }}" parantClass="col-12 col-md-12"
                            name="footer_description_text" type="text" placeholder="{{ __f('Footer Description Placeholder') }}"
                            errorName="footer_description_text" class="py-2"
                            value="{{ config('settings.footer_description_text') ?? old('footer_description_text') }}"></x-form.textarea>
                    </div>
                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Footer Call Us Text Title') }}" parantClass="col-12 col-md-6"
                            name="footer_call_us_text" placeholder="{{ __f('Footer Call Us Placeholder') }}"
                            errorName="footer_call_us_text" class="py-2" type="text"
                            value="{{ config('settings.footer_call_us_text') ?? old('footer_call_us_text') }}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Footer One Title Title') }}" parantClass="col-12 col-md-6"
                            name="footer_one_title" placeholder="{{ __f('Footer One Title Placeholder') }}" errorName="footer_one_title"
                            class="py-2" type="text"
                            value="{{ config('settings.footer_one_title') ?? old('footer_one_title') }}"></x-form.textbox>
                    </div>
                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Footer Two Title Title') }}" parantClass="col-12 col-md-6"
                            name="footer_two_title" placeholder="{{ __f('Footer Two Title Placeholder') }}" errorName="footer_two_title"
                            class="py-2" type="text"
                            value="{{ config('settings.footer_two_title') ?? old('footer_two_title') }}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Footer Three Title Title') }}" parantClass="col-12 col-md-6"
                            name="footer_three_title" placeholder="{{ __f('Footer Three Placeholder') }}"
                            errorName="footer_three_title" class="py-2" type="text"
                            value="{{ config('settings.footer_three_title') ?? old('footer_three_title') }}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textbox labelName="{{ __f('Footer Four Title') }}" parantClass="col-12 col-md-6"
                            name="footer_four_title" placeholder="{{ __f('Footer Four Placeholder') }}"
                            errorName="footer_four_title" class="py-2" type="text"
                            value="{{ config('settings.footer_four_title') ?? old('footer_four_title') }}"></x-form.textbox>

                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium">{{ __f('Footer Payment Method Image Title') }}</label>
                            <div>
                                <label class="eghit_picture" for="eghit__image" tabIndex="0">
                                    <span class="picture_eghit_image"></span>
                                </label>
                                <input type="file" name="footer_payment_image" id="eghit__image">
                                <span class="text-danger error-text image-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none footer_setting" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f('Submit Title') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#multiple-select-field, #multiple-select2-field').each(function() {
                $(this).select2({
                    theme: "bootstrap-5",
                    width: $(this).data('width') ? $(this).data('width') : $(this).hasClass(
                        'w-100') ? '100%' : 'style',
                    placeholder: $(this).data('placeholder'),
                    closeOnSelect: false,
                });
            });
        });
    </script>
    <script>
        $(function() {
            ImagePriviewInsert('third__image', 'picture_third_image', '{{ __f("Choose Favicon 32*32 Placeholder") }}');
            ImagePriviewInsert('fourth__image', 'picture_fourth_image', '{{ __f("Choose Favicon 16*16 Placeholder") }}');
            ImagePriviewInsert('fivth__image', 'picture_fivth_image', '{{ __f("Choose Breadcrumb Background Image Placeholder") }}');
            ImagePriviewInsert('seventh__image', 'picture_seventh_image', '{{ __f("Authority Signature Image Placeholder") }}');
            ImagePriviewInsert('eghit__image', 'picture_eghit_image', '{{ __f("Footer Payment Method Image Placeholder") }}');
        });


        var companyFaviconFirst = "{{ config('settings.favicon_first') ?? '' }}";
        var companyFaviconSecond = "{{ config('settings.favicon_second') ?? '' }}";
        var companybreadcrumbbackgroundimage = "{{ config('settings.breadcrumbbackgroundimage') ?? '' }}";
        var companyAuthoritySignatureImage = "{{ config('settings.authority_signature_image') ?? '' }}";
        var footerPaymentImage = "{{ config('settings.footer_payment_image') ?? '' }}";

        if (companyFaviconFirst != '') {
            var myFaviconFirstData = "{{ asset(config('settings.favicon_first') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('third__image', 'picture_third_image', '{{ __f("Choose Favicon 32*32 Placeholder") }}',
                    myFaviconFirstData);
            });
        }

        if (companyFaviconSecond != '') {
            var myFaviconSecondData = "{{ asset(config('settings.favicon_second') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('fourth__image', 'picture_fourth_image', '{{ __f("Choose Favicon 16*16 Placeholder") }}',
                    myFaviconSecondData);
            });
        }

        if (companybreadcrumbbackgroundimage != '') {
            var mybreadcrumbbackgroundimage = "{{ asset(config('settings.breadcrumbbackgroundimage') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('fivth__image', 'picture_fivth_image', '{{ __f("Choose Breadcrumb Background Image Placeholder") }}',
                    mybreadcrumbbackgroundimage);
            });
        }

        if (companyAuthoritySignatureImage != '') {
            var myAuthoritySignatureImage = "{{ asset(config('settings.authority_signature_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('seventh__image', 'picture_seventh_image', '{{ __f("Authority Signature Image Placeholder") }}',
                    myAuthoritySignatureImage);
            });
        }
        if (footerPaymentImage != '') {
            var myfooterPaymentImage = "{{ asset(config('settings.footer_payment_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('eghit__image', 'picture_eghit_image', '{{ __f("Footer Payment Method Image Placeholder") }}',
                    myfooterPaymentImage);
            });
        }

        $(document).ready(function() {
            let cropper;
            let secondaryCropper;

            document.getElementById('companyPrimaryLogo').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('companyPrimaryLogoPriview').src = event.target.result;
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(document.getElementById('companyPrimaryLogoPriview'), {
                            aspectRatio: false,
                            viewMode: 1,
                            autoCropArea: 1,
                            cropBoxResizable: false,
                            cropBoxMovable: true,
                        });
                    };
                    reader.readAsDataURL(file);
                    $('#crop_button').removeClass('d-none');
                }
            });

            document.getElementById('companySecondaryLogo').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('companySecondaryLogoPriview').src = event.target
                            .result;
                        if (secondaryCropper) secondaryCropper.destroy();
                        secondaryCropper = new Cropper(document.getElementById(
                            'companySecondaryLogoPriview'), {
                            aspectRatio: false,
                            viewMode: 1,
                            autoCropArea: 1,
                            cropBoxResizable: false,
                            cropBoxMovable: true,
                        });
                    };
                    reader.readAsDataURL(file);
                    $('#crop_button_secondary').removeClass('d-none');
                }
            });


            document.getElementById('crop_button').addEventListener('click', function() {
                var imgurl = cropper.getCroppedCanvas().toDataURL();
                var img = document.createElement("img");
                img.src = imgurl;
                $('.cropper-bg').css('display', 'none');
                document.getElementById("cropped_result").innerHTML = '';
                document.getElementById("cropped_result").appendChild(img);
            });

            document.getElementById('crop_button_secondary').addEventListener('click', function() {
                var imgurl = secondaryCropper.getCroppedCanvas().toDataURL();
                var img = document.createElement("img");
                img.src = imgurl;
                $('.cropper-bg').css('display', 'none');
                document.getElementById("cropped_result_secondary").innerHTML = '';
                document.getElementById("cropped_result_secondary").appendChild(img);
            });

            $('#invoiceNoteSettings').on('submit', function(e) {
                e.preventDefault();
                $('.invoice_note_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.invoice_note_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.invoice_note_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#themeSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.theme_setting_loader').removeClass('d-none');
                let formData = new FormData(this);
                if (cropper && secondaryCropper) {
                    cropper.getCroppedCanvas().toBlob(function(primaryBlob) {
                        formData.append('company_primary_logo', primaryBlob, 'cropped.jpg');
                        secondaryCropper.getCroppedCanvas().toBlob(function(secondaryBlob) {
                            formData.append('company_secondary_logo', secondaryBlob,
                                'croppedsecondary.jpg');
                            callTOThemeSettingFrom(formData);
                        });
                    });
                } else if (cropper) {
                    const canvas = cropper.getCroppedCanvas();
                    canvas.toBlob(function(blob) {
                        formData.append('company_primary_logo', blob, 'cropped.jpg');
                        callTOThemeSettingFrom(formData);
                    });
                } else if (secondaryCropper) {
                    const secondaryCanvas = secondaryCropper.getCroppedCanvas();
                    secondaryCanvas.toBlob(function(blob) {
                        formData.append('company_secondary_logo', blob, 'croppedsecondary.jpg');
                        callTOThemeSettingFrom(formData);
                    });
                } else {
                    callTOThemeSettingFrom(formData);
                };
            });

            function callTOThemeSettingFrom(formData) {
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.theme_setting_loader').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.theme_setting_loader').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            }

            $('#sectionSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.section_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.section_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.section_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#cartSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.cart_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.cart_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.cart_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#accountSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.account_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.account_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.account_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#socalMediaSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.socal_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.socal_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.socal_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#categoryProductSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.section_category_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.section_category_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.section_category_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#topSellingProductForm').on('submit', function(e) {
                e.preventDefault();
                $('.top_selling_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.top_selling_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.top_selling_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#contactFromSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.conatact_page_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.conatact_page_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.conatact_page_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#aboutFromSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.about_page_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.about_page_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.about_page_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });

            $('#shopFromSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.shop_page_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.shop_page_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.shop_page_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });
            $('#footerSettings').on('submit', function(e) {
                e.preventDefault();
                $('.footer_setting').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == 'success') {
                            flashMessage(res.status, res.message);
                            setTimeout(() => {
                                location.reload();
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.footer_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.footer_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
