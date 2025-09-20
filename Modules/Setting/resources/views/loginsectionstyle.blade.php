@extends('layouts.app')
@section('title', $title)
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <style>
        .login-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .login-image-show {
            max-height: 200px;
        }

        .login-gap {
            gap: 25px;
        }
        #login-image-dimensions{
            font-size: 11px;
            color: #ff6000c9;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Login And Register Page Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="loginStyleSettingForm" action="{{ route('admin.setting.login.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Login And Register Page Header Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="loginheadershowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="loginheadershowchosevalue"
                                        id="loginheadershowchosevalue1" value="1"
                                        {{ config('settings.loginheadershowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="loginheadershowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="loginheadershowchosevalue"
                                        id="loginheadershowchosevalue2" value="2"
                                         {{ config('settings.loginheadershowchosevalue') == 2 || is_null(config('settings.loginheadershowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Login And Register Page Breadcrumb Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="loginbreadcrumbshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="loginbreadcrumbshowchosevalue"
                                        id="loginbreadcrumbshowchosevalue1" value="1"
                                        {{ config('settings.loginbreadcrumbshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="loginbreadcrumbshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="loginbreadcrumbshowchosevalue"
                                        id="loginbreadcrumbshowchosevalue2" value="2"
                                         {{ config('settings.loginbreadcrumbshowchosevalue') == 2 || is_null(config('settings.loginbreadcrumbshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Login And Register Page Footer Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="loginfootershowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="loginfootershowchosevalue"
                                        id="loginfootershowchosevalue1" value="1"
                                        {{ config('settings.loginfootershowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="loginfootershowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="loginfootershowchosevalue"
                                        id="loginfootershowchosevalue2" value="2"
                                         {{ config('settings.loginfootershowchosevalue') == 2 || is_null(config('settings.loginfootershowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="loginpagestyleshowchosevalue"
                                    id="loginpagestyleshowchosevalue1" value="1"
                                    {{ config('settings.loginpagestyleshowchosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="loginpagestyleshowchosevalue1">
                                    {{ __f('Login And Register Page Style One Title') }} <br>
                                    <span
                                        class="login-chose-speacal-text">{{ __f('Login And Register Page Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 login-image-show"
                                src="{{ asset('backend/assets/loginpage/loginpage_1.png') }}"
                                alt="login-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="loginpagestyleshowchosevalue"
                                    id="loginpagestyleshowchosevalue2" value="2"
                                    {{ config('settings.loginpagestyleshowchosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="loginpagestyleshowchosevalue2">
                                    {{ __f('Login And Register Page Style Two Title') }} <br>
                                    <span
                                        class="login-chose-speacal-text">{{ __f('Login And Register Page Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 login-image-show"
                                src="{{ asset('backend/assets/loginpage/loginpage_2.png') }}"
                                alt="login-image">
                        </div>
                    </div>
                    <div class="row my-3 {{ config('settings.loginpagestyleshowchosevalue') == 2 ? '' : 'd-none' }}" id="secondimageshowhide">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium required">{{ __f('Login And Register Page Style Two Background Image Title') }}
                                <span id="login-image-dimensions">
                                        ({{ __f('Login And Register Page Style Two Background Image Dimensions') }})
                                </span>
                            </label>
                            <div>
                                <label class="third_picture" for="third__image" tabIndex="0">
                                    <span class="picture_third_image"></span>
                                </label>
                                <input type="file" name="login_two_bg_image" id="third__image" accept="image/*">
                                <span class="text-danger error-text login_two_bg_image-error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="loginpagestyleshowchosevalue"
                                    id="loginpagestyleshowchosevalue3" value="3"
                                    {{ config('settings.loginpagestyleshowchosevalue') == 3 || is_null(config('settings.loginpagestyleshowchosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="loginpagestyleshowchosevalue3">
                                    {{ __f('Login And Register Page Style Three Title') }} <br>
                                    <span
                                        class="login-chose-speacal-text">{{ __f('Login And Register Page Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 login-image-show"
                                src="{{ asset('backend/assets/loginpage/loginpage_3.png') }}"
                                alt="login-image">
                        </div>
                    </div>
                    <div class="row my-3 {{ config('settings.loginpagestyleshowchosevalue') == 3 ? '' : 'd-none' }}" id="thiredimageshowhide">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-5">
                            <label class="text-dark font-weight-medium required">{{ __f('Login And Register Page Style Three Background Image Title') }}
                                <span id="login-image-dimensions">
                                        ({{ __f('Login And Register Page Style Three Background Image Dimensions') }})
                                </span>
                            </label>
                            <div>
                                <label class="fourth_picture" for="fourth__image" tabIndex="0">
                                    <span class="picture_fourth_image"></span>
                                </label>
                                <input type="file" name="login_page_three_bg_image" id="fourth__image" accept="image/*">
                                <span class="text-danger error-text login_page_three_bg_image-error"></span>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="text-dark font-weight-medium required">{{ __f('Login And Register Page Style Three Right Image Title') }}
                                <span id="login-image-dimensions">
                                        ({{ __f('Login And Register Page Style Three Image Image Dimensions') }})
                                </span>
                            </label>
                            <div>
                                <label class="fivth_picture" for="fivth__image" tabIndex="0">
                                    <span class="picture_fivth_image"></span>
                                </label>
                                <input type="file" name="login_page_three_right_image" id="fivth__image" accept="image/*">
                                <span class="text-danger error-text login_page_three_right_image-error"></span>
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
    </section>
@endsection
@push('scripts')
    <script>
        $(function() {
            ImagePriviewInsert('third__image', 'picture_third_image','{{ __f('Login And Register Page Style Two Background Image Title') }}');
            ImagePriviewInsert('fourth__image', 'picture_fourth_image', '{{ __f('Login And Register Page Style Three Background Image Title') }}');
            ImagePriviewInsert('fivth__image', 'picture_fivth_image', '{{ __f('Login And Register Page Style Three Right Image Title') }}');
        });


        var First = "{{ config('settings.login_two_bg_image') ?? '' }}";
        var Second = "{{ config('settings.login_page_three_bg_image') ?? '' }}";
        var Thired = "{{ config('settings.login_page_three_right_image') ?? '' }}";

        if (First != '') {
            var FirstData = "{{ asset(config('settings.login_two_bg_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('third__image', 'picture_third_image','{{ __f('Login And Register Page Style Two Background Image Title') }}', FirstData);
            });
        }

        if (Second != '') {
            var SecondData = "{{ asset(config('settings.login_page_three_bg_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('fourth__image', 'picture_fourth_image','{{ __f('Login And Register Page Style Three Background Image Title') }}', SecondData);
            });
        }

        if (Thired != '') {
            var ThiredData = "{{ asset(config('settings.login_page_three_right_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('fivth__image', 'picture_fivth_image','{{ __f('Login And Register Page Style Three Right Image Title') }}', ThiredData);
            });
        }

     
        $(document).ready(function() {
            $('#loginStyleSettingForm').on('submit', function(e) {
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

            $('input[name="loginpagestyleshowchosevalue"]').on('change', function () {
               var value = $(this).val();
               if(value == 2){
                    $('#secondimageshowhide').removeClass('d-none');
                    $('#thiredimageshowhide').addClass('d-none');
               }else if(value == 3){
                    $('#secondimageshowhide').addClass('d-none');
                    $('#thiredimageshowhide').removeClass('d-none');
               }else{
                    $('#secondimageshowhide').addClass('d-none');
                    $('#thiredimageshowhide').addClass('d-none');
               }
            });
        });
    </script>
@endpush
