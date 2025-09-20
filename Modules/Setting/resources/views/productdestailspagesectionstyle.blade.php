@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .product-details-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .product-details-image-show {
            max-height: 200px;
        }

        .product-details-gap {
            gap: 25px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Product Details Page Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="productDetailsStyleSettingForm" action="{{ route('admin.setting.product.details.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Product Details Previous Next Product Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="productpreviousnextshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productpreviousnextshowchosevalue"
                                        id="productpreviousnextshowchosevalue1" value="1"
                                        {{ config('settings.productpreviousnextshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="productpreviousnextshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productpreviousnextshowchosevalue"
                                        id="productpreviousnextshowchosevalue2" value="2"
                                         {{ config('settings.productpreviousnextshowchosevalue') == 2 || is_null(config('settings.productpreviousnextshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Product Details Category Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="productdetailscategoryshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productdetailscategoryshowchosevalue"
                                        id="productdetailscategoryshowchosevalue1" value="1"
                                        {{ config('settings.productdetailscategoryshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="productdetailscategoryshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productdetailscategoryshowchosevalue"
                                        id="productdetailscategoryshowchosevalue2" value="2"
                                         {{ config('settings.productdetailscategoryshowchosevalue') == 2 || is_null(config('settings.productdetailscategoryshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Product Details Brand Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="productdetailsbrandshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productdetailsbrandshowchosevalue"
                                        id="productdetailsbrandshowchosevalue1" value="1"
                                        {{ config('settings.productdetailsbrandshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="productdetailsbrandshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productdetailsbrandshowchosevalue"
                                        id="productdetailsbrandshowchosevalue2" value="2"
                                         {{ config('settings.productdetailsbrandshowchosevalue') == 2 || is_null(config('settings.productdetailsbrandshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Product Details Modal Image Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="productdetailsmpdalimageshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productdetailsmpdalimageshowchosevalue"
                                        id="productdetailsmpdalimageshowchosevalue1" value="1"
                                        {{ config('settings.productdetailsmpdalimageshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="productdetailsmpdalimageshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="productdetailsmpdalimageshowchosevalue"
                                        id="productdetailsmpdalimageshowchosevalue2" value="2"
                                         {{ config('settings.productdetailsmpdalimageshowchosevalue') == 2 || is_null(config('settings.productdetailsmpdalimageshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productdetailspagechosevalue"
                                    id="productdetailspagechosevalue1" value="1"
                                    {{ config('settings.productdetailspagechosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="productdetailspagechosevalue1">
                                    {{ __f('Product Details Page Style One Title') }} <br>
                                    <span
                                        class="product-details-chose-speacal-text">{{ __f('Product Details Page Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 product-details-image-show"
                                src="{{ asset('backend/assets/productdetailspage/productdetailspage_1.png') }}"
                                alt="product-details-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productdetailspagechosevalue"
                                    id="productdetailspagechosevalue2" value="2"
                                    {{ config('settings.productdetailspagechosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="productdetailspagechosevalue2">
                                    {{ __f('Product Details Page Style Two Title') }} <br>
                                    <span
                                        class="product-details-chose-speacal-text">{{ __f('Product Details Page Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 product-details-image-show"
                                src="{{ asset('backend/assets/productdetailspage/productdetailspage_2.png') }}"
                                alt="product-details-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="productdetailspagechosevalue"
                                    id="productdetailspagechosevalue3" value="3"
                                    {{ config('settings.productdetailspagechosevalue') == 3 || is_null(config('settings.productdetailspagechosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="productdetailspagechosevalue3">
                                    {{ __f('Product Details Page Style Three Title') }} <br>
                                    <span
                                        class="product-details-chose-speacal-text">{{ __f('Product Details Page Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 product-details-image-show"
                                src="{{ asset('backend/assets/productdetailspage/productdetailspage_3.png') }}"
                                alt="product-details-image">
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
            $('#productDetailsStyleSettingForm').on('submit', function(e) {
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
