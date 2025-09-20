@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .product-card-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .product-card-image-show {
            height: 200px;
        }

        .product-card-gap {
            gap: 25px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Product Card Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="productCardStyleSettingForm" action="{{ route('admin.setting.product.card.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Number Of Card Show In One Section Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center product-card-gap">
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home"
                                        id="numberofcardshowinhome1" value="1"
                                        {{ config('settings.number_of_card_show_in_home') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome1">
                                        {{ convertToLocaleNumber(1) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home"
                                        id="numberofcardshowinhome2" value="2"
                                        {{ config('settings.number_of_card_show_in_home') == 2 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome2">
                                        {{ convertToLocaleNumber(2) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home"
                                        id="numberofcardshowinhome3" value="3"
                                        {{ config('settings.number_of_card_show_in_home') == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome3">
                                        {{ convertToLocaleNumber(3) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home"
                                        id="numberofcardshowinhome4" value="4"
                                        {{ config('settings.number_of_card_show_in_home') == 4 || is_null(config('settings.number_of_card_show_in_home')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome4">
                                        {{ convertToLocaleNumber(4) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home"
                                        id="numberofcardshowinhome5" value="5"
                                        {{ config('settings.number_of_card_show_in_home') == 5 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome5">
                                        {{ convertToLocaleNumber(5) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home"
                                        id="numberofcardshowinhome6" value="6"
                                        {{ config('settings.number_of_card_show_in_home') == 6 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome6">
                                        {{ convertToLocaleNumber(6) }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Product Card Navbar Position Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center product-card-gap">
                                <div>
                                    <label class="form-check-label" for="productnavbarpositionchosevalue1">
                                        {{ __f('Product Card Navbar Position One Title') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productnavbarpositionchosevalue"
                                        id="productnavbarpositionchosevalue1" value="1"
                                        {{ config('settings.productnavbarpositionchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="form-check-label" for="productnavbarpositionchosevalue2">
                                        {{ __f('Product Card Navbar Position Two Title') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productnavbarpositionchosevalue"
                                        id="productnavbarpositionchosevalue2" value="2"
                                         {{ config('settings.productnavbarpositionchosevalue') == 2 || is_null(config('settings.productnavbarpositionchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Product Card Navbar Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center product-card-gap">
                                <div>
                                    <label class="form-check-label" for="productnavbarshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productnavbarshowchosevalue"
                                        id="productnavbarshowchosevalue1" value="1"
                                        {{ config('settings.productnavbarshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="form-check-label" for="productnavbarshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productnavbarshowchosevalue"
                                        id="productnavbarshowchosevalue2" value="2"
                                         {{ config('settings.productnavbarshowchosevalue') == 2 || is_null(config('settings.productnavbarshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Product Card Dotted Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center product-card-gap">
                                <div>
                                    <label class="form-check-label" for="productdottedshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productdottedshowchosevalue"
                                        id="productdottedshowchosevalue1" value="1"
                                        {{ config('settings.productdottedshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="form-check-label" for="productdottedshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productdottedshowchosevalue"
                                        id="productdottedshowchosevalue2" value="2"
                                         {{ config('settings.productdottedshowchosevalue') == 2 || is_null(config('settings.productdottedshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productcardchosevalue"
                                    id="productcardchosevalue1" value="1"
                                    {{ config('settings.productcardchosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="productcardchosevalue1">
                                    {{ __f('Product Card Style One Title') }} <br>
                                    <span
                                        class="product-card-chose-speacal-text">{{ __f('Product Card Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 product-card-image-show"
                                src="{{ asset('backend/assets/productimage/productimage_1.png') }}"
                                alt="product-card-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productcardchosevalue"
                                    id="productcardchosevalue2" value="2"
                                    {{ config('settings.productcardchosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="productcardchosevalue2">
                                    {{ __f('Product Card Style Two Title') }} <br>
                                    <span
                                        class="product-card-chose-speacal-text">{{ __f('Product Card Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 product-card-image-show"
                                src="{{ asset('backend/assets/productimage/productimage_2.png') }}"
                                alt="product-card-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productcardchosevalue"
                                    id="productcardchosevalue3" value="3"
                                    {{ config('settings.productcardchosevalue') == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="productcardchosevalue3">
                                    {{ __f('Product Card Style Three Title') }} <br>
                                    <span
                                        class="product-card-chose-speacal-text">{{ __f('Product Card Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 product-card-image-show"
                                src="{{ asset('backend/assets/productimage/productimage_3.png') }}"
                                alt="product-card-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productcardchosevalue"
                                    id="productcardchosevalue4" value="4"
                                    {{ config('settings.productcardchosevalue') == 4 ? 'checked' : '' }}>
                                <label class="form-check-label" for="productcardchosevalue4">
                                    {{ __f('Product Card Style Four Title') }} <br>
                                    <span
                                        class="product-card-chose-speacal-text">{{ __f('Product Card Style Four Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 product-card-image-show"
                                src="{{ asset('backend/assets/productimage/productimage_4.png') }}"
                                alt="product-card-image">
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productcardchosevalue"
                                    id="productcardchosevalue5" value="5"
                                    {{ config('settings.productcardchosevalue') == 5 || is_null(config('settings.productcardchosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="productcardchosevalue5">
                                    {{ __f('Product Card Style Five Title') }} <br>
                                    <span
                                        class="product-card-chose-speacal-text">{{ __f('Product Card Style Five Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 product-card-image-show"
                                src="{{ asset('backend/assets/productimage/productimage_5.png') }}"
                                alt="product-card-image">
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
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#productCardStyleSettingForm').on('submit', function(e) {
                e.preventDefault();
                $('.theme_setting_loader').removeClass('d-none');
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
            });
        });
    </script>
@endpush
