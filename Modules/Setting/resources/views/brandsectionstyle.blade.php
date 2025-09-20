@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .brand-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .brand-image-show {
            height: 200px;
        }

        .brand-gap {
            gap: 25px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Brand Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="brandStyleSettingForm" action="{{ route('admin.setting.brand.section.style.store') }}"
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
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home_brand"
                                        id="numberofcardshowinhome1" value="1"
                                        {{ config('settings.number_of_card_show_in_home_brand') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome1">
                                        {{ convertToLocaleNumber(1) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home_brand"
                                        id="numberofcardshowinhome2" value="2"
                                        {{ config('settings.number_of_card_show_in_home_brand') == 2 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome2">
                                        {{ convertToLocaleNumber(2) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home_brand"
                                        id="numberofcardshowinhome3" value="3"
                                        {{ config('settings.number_of_card_show_in_home_brand') == 3 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome3">
                                        {{ convertToLocaleNumber(3) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home_brand"
                                        id="numberofcardshowinhome4" value="4"
                                        {{ config('settings.number_of_card_show_in_home_brand') == 4 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome4">
                                        {{ convertToLocaleNumber(4) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home_brand"
                                        id="numberofcardshowinhome5" value="5"
                                        {{ config('settings.number_of_card_show_in_home_brand') == 5 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="numberofcardshowinhome5">
                                        {{ convertToLocaleNumber(5) }}
                                    </label>
                                </div>
                                <div>
                                    <input class="form-check-input" type="radio" name="number_of_card_show_in_home_brand"
                                        id="numberofcardshowinhome6" value="6"
                                        {{ config('settings.number_of_card_show_in_home_brand') == 6 || is_null(config('settings.number_of_card_show_in_home_brand')) ? 'checked' : '' }}>
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
                                    {{ __f('Brand Navbar Position Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="bradnavbarpositionchosevalue1">
                                        {{ __f('Brand Navbar Position One Title') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="bradnavbarpositionchosevalue"
                                        id="bradnavbarpositionchosevalue1" value="1"
                                        {{ config('settings.bradnavbarpositionchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="form-check-label" for="bradnavbarpositionchosevalue2">
                                        {{ __f('Brand Navbar Position Two Title') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="bradnavbarpositionchosevalue"
                                        id="bradnavbarpositionchosevalue2" value="2"
                                         {{ config('settings.bradnavbarpositionchosevalue') == 2 || is_null(config('settings.bradnavbarpositionchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Brand Navbar Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="brandnavbarshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="brandnavbarshowchosevalue"
                                        id="brandnavbarshowchosevalue1" value="1"
                                        {{ config('settings.brandnavbarshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="form-check-label" for="brandnavbarshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="brandnavbarshowchosevalue"
                                        id="brandnavbarshowchosevalue2" value="2"
                                         {{ config('settings.brandnavbarshowchosevalue') == 2 || is_null(config('settings.brandnavbarshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Brand Dotted Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="branddottedshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="branddottedshowchosevalue"
                                        id="branddottedshowchosevalue1" value="1"
                                        {{ config('settings.branddottedshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div>
                                    <label class="form-check-label" for="branddottedshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="branddottedshowchosevalue"
                                        id="branddottedshowchosevalue2" value="2"
                                         {{ config('settings.branddottedshowchosevalue') == 2 || is_null(config('settings.branddottedshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brandchosevalue"
                                    id="brandchosevalue1" value="1"
                                    {{ config('settings.brandchosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="brandchosevalue1">
                                    {{ __f('Brand Style One Title') }} <br>
                                    <span
                                        class="brand-chose-speacal-text">{{ __f('Brand Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 brand-image-show"
                                src="{{ asset('backend/assets/brandimage/brandimage_1.png') }}"
                                alt="brand-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brandchosevalue"
                                    id="brandchosevalue2" value="2"
                                    {{ config('settings.brandchosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="brandchosevalue2">
                                    {{ __f('Brand Style Two Title') }} <br>
                                    <span
                                        class="brand-chose-speacal-text">{{ __f('Brand Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 brand-image-show"
                                src="{{ asset('backend/assets/brandimage/brandimage_2.png') }}"
                                alt="brand-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brandchosevalue"
                                    id="brandchosevalue3" value="3"
                                    {{ config('settings.brandchosevalue') == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="brandchosevalue3">
                                    {{ __f('Brand Style Three Title') }} <br>
                                    <span
                                        class="brand-chose-speacal-text">{{ __f('Brand Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 brand-image-show"
                                src="{{ asset('backend/assets/brandimage/brandimage_3.png') }}"
                                alt="brand-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="brandchosevalue"
                                    id="brandchosevalue4" value="4"
                                    {{ config('settings.brandchosevalue') == 4 || is_null(config('settings.brandchosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="brandchosevalue4">
                                    {{ __f('Brand Style Four Title') }} <br>
                                    <span
                                        class="brand-chose-speacal-text">{{ __f('Brand Style Four Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 brand-image-show"
                                src="{{ asset('backend/assets/brandimage/brandimage_4.png') }}"
                                alt="brand-image">
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
            $('#brandStyleSettingForm').on('submit', function(e) {
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
