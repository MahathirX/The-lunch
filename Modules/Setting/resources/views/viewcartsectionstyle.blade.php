@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .view-cart-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .view-cart-image-show {
            max-height: 200px;
        }

        .view-cart-gap {
            gap: 25px;
        }
        #view-cart-image-dimensions{
            font-size: 11px;
            color: #ff6000c9;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('View Cart Page Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="viewCartStyleSettingForm" action="{{ route('admin.setting.view.cart.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="viewcartpageshowchosevalue"
                                    id="viewcartpageshowchosevalue1" value="1"
                                    {{ config('settings.viewcartpageshowchosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="viewcartpageshowchosevalue1">
                                    {{ __f('View Cart Page Style One Title') }} <br>
                                    <span
                                        class="view-cart-chose-speacal-text">{{ __f('View Cart Page Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 view-cart-image-show"
                                src="{{ asset('backend/assets/viewcartoutpage/viewcartoutpage_1.png') }}"
                                alt="view-cart-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="viewcartpageshowchosevalue"
                                    id="viewcartpageshowchosevalue2" value="2"
                                    {{ config('settings.viewcartpageshowchosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="viewcartpageshowchosevalue2">
                                    {{ __f('View Cart Page Style Two Title') }} <br>
                                    <span
                                        class="view-cart-chose-speacal-text">{{ __f('View Cart Page Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 view-cart-image-show"
                                src="{{ asset('backend/assets/viewcartoutpage/viewcartoutpage_2.png') }}"
                                alt="view-cart-image">
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="viewcartpageshowchosevalue"
                                    id="viewcartpageshowchosevalue3" value="3"
                                    {{ config('settings.viewcartpageshowchosevalue') == 3 || is_null(config('settings.viewcartpageshowchosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="viewcartpageshowchosevalue3">
                                    {{ __f('View Cart Page Style Two Title') }} <br>
                                    <span
                                        class="view-cart-chose-speacal-text">{{ __f('View Cart Page Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 view-cart-image-show"
                                src="{{ asset('backend/assets/viewcartoutpage/viewcartoutpage_3.png') }}"
                                alt="view-cart-image">
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
            $('#viewCartStyleSettingForm').on('submit', function(e) {
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
