@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <style>
        .slider-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }
        .slider-showing-image{
            height: 200px;
        }
        #slider-image-dimensions{
            font-size: 11px;
            color: #ff6000c9;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Slider Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="sliderStyleSettingForm" action="{{ route('admin.setting.slider.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="slider_show_value_in_home">
                                    {{ __f('Home Page Showing Silder Number') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                           <input class="form-control" type="number" name="slider_show_value_in_home" id="slider_show_value_in_home" value="{{ config('settings.slider_show_value_in_home')  ?? 4 }}" placeholder="{{ __f('Home Page Showing Silder Number Placeholder') }}">
                        </div>
                    </div>

                    <div class="row g-5 my-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sliderchosevalue"
                                    id="sliderchosevalue1" value="1"
                                    {{ config('settings.sliderchosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="sliderchosevalue1">
                                    {{ __f('Slider Style One Title') }} <br>
                                    <span class="slider-chose-speacal-text">{{ __f('Slider Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 slider-showing-image" src="{{ asset('backend/assets/sliderimage/slider_1.png') }}"
                                alt="slider-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sliderchosevalue"
                                    id="sliderchosevalue2" value="2"
                                    {{ config('settings.sliderchosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="sliderchosevalue2">
                                    {{ __f('Slider Style Two Title') }} <br>
                                    <span class="slider-chose-speacal-text">{{ __f('Slider Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 slider-showing-image" src="{{ asset('backend/assets/sliderimage/slider_2.png') }}"
                                alt="slider-image">
                        </div>
                    </div>
                    <div class="row my-3 {{ config('settings.sliderchosevalue') == 2 ? '' : 'd-none' }}" id="secondimageshowhide">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-5">
                            <label class="text-dark font-weight-medium required">{{ __f('Slider Style Two Right First Image Title') }}
                                <span id="slider-image-dimensions">
                                        ({{ __f('Slider Style Two Right First Image Dimensions') }})
                                </span>
                            </label>
                            <div>
                                <label class="third_picture" for="third__image" tabIndex="0">
                                    <span class="picture_third_image"></span>
                                </label>
                                <input type="file" name="slider_style_two_right_first_image" id="third__image" accept="image/*">
                                <span class="text-danger error-text slider_style_two_right_first_image-error"></span>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="text-dark font-weight-medium required">{{ __f('Slider Style Two Right Second Image Title') }}
                                <span id="slider-image-dimensions">
                                        ({{ __f('Slider Style Two Right Second Image Dimensions') }})
                                </span>
                            </label>
                            <div>
                                <label class="fourth_picture" for="fourth__image" tabIndex="0">
                                    <span class="picture_fourth_image"></span>
                                </label>
                                <input type="file" name="slider_style_two_right_second_image" id="fourth__image" accept="image/*">
                                <span class="text-danger error-text slider_style_two_right_second_image-error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sliderchosevalue"
                                    id="sliderchosevalue3" value="3"
                                    {{ config('settings.sliderchosevalue') == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="sliderchosevalue3">
                                    {{ __f('Slider Style Three Title') }}<br>
                                    <span class="slider-chose-speacal-text">{{ __f('Slider Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 slider-showing-image" src="{{ asset('backend/assets/sliderimage/slider_3.png') }}"
                                alt="slider-image">
                        </div>
                    </div>
                    <div class="row my-3 {{ config('settings.sliderchosevalue') == 3 ? '' : 'd-none' }}" id="thirdimageshowhide">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-5">
                            <label class="text-dark font-weight-medium required">{{ __f('Slider Style Three Right Image Title') }}
                                <span id="slider-image-dimensions">
                                        ({{ __f('Slider Style Three Right Image Dimensions') }})
                                </span>
                            </label>
                            <div>
                                <label class="fivth_picture" for="fivth__image" tabIndex="0">
                                    <span class="picture_fivth_image"></span>
                                </label>
                                <input type="file" name="slider_style_three_right_image" id="fivth__image" accept="image/*">
                                <span class="text-danger error-text slider_style_three_right_image-error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sliderchosevalue"
                                    id="sliderchosevalue4" value="4"
                                    {{ config('settings.sliderchosevalue') == 4 ? 'checked' : '' }}>
                                <label class="form-check-label" for="sliderchosevalue4">
                                    {{ __f('Slider Style Four Title') }} <br>
                                    <span class="slider-chose-speacal-text">{{ __f('Slider Style Four Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 slider-showing-image" src="{{ asset('backend/assets/sliderimage/slider_4.png') }}"
                                alt="slider-image">
                        </div>
                    </div>
                    <div class="row my-3 {{ config('settings.sliderchosevalue') == 4 ? '' : 'd-none' }}" id="fourthimageshowhide">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-5">
                            <label class="text-dark font-weight-medium required">{{ __f('Slider Style Four Right First Image Title') }}
                                <span id="slider-image-dimensions">
                                        ({{ __f('Slider Style Four Right First Image Dimensions') }})
                                </span>
                            </label>
                            <div>
                                <label class="sixth_picture" for="sixth__image" tabIndex="0">
                                    <span class="picture_sixth_image"></span>
                                </label>
                                <input type="file" name="slider_style_four_right_first_image" id="sixth__image" accept="image/*">
                                <span class="text-danger error-text slider_style_four_right_first_image-error"></span>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="text-dark font-weight-medium required">{{ __f('Slider Style Four Right Second Image Title') }}
                                <span id="slider-image-dimensions">
                                        ({{ __f('Slider Style Four Right Second Image Dimensions') }})
                                </span>
                            </label>
                            <div>
                                <label class="seventh_picture" for="seventh__image" tabIndex="0">
                                    <span class="seventh_picture"></span>
                                </label>
                                <input type="file" name="slider_style_four_right_second_image" id="seventh__image" accept="image/*">
                                <span class="text-danger error-text slider_style_four_right_second_image-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="sliderchosevalue"
                                    id="sliderchosevalue5" value="5"
                                      {{ config('settings.sliderchosevalue') == 5 || is_null(config('settings.sliderchosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="sliderchosevalue5">
                                    {{ __f('Slider Style Five Title') }} <br>
                                    <span class="slider-chose-speacal-text">{{ __f('Slider Style Five Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 slider-showing-image" src="{{ asset('backend/assets/sliderimage/slider_5.png') }}"
                                alt="slider-image">
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
            ImagePriviewInsert('third__image', 'picture_third_image','{{ __f('Slider Style Two Right First Image Title') }}');
            ImagePriviewInsert('fourth__image', 'picture_fourth_image', '{{ __f('Slider Style Two Right Second Image Title') }}');
            ImagePriviewInsert('fivth__image', 'picture_fivth_image', '{{ __f('Slider Style Three Right Image Title') }}');
            ImagePriviewInsert('sixth__image', 'picture_sixth_image', '{{ __f('Slider Style Four Right First Image Title') }}');
            ImagePriviewInsert('seventh__image', 'seventh_picture', '{{ __f('Slider Style Four Right Second Image Title') }}');
        });


        var First = "{{ config('settings.slider_style_two_right_first_image') ?? '' }}";
        var Second = "{{ config('settings.slider_style_two_right_second_image') ?? '' }}";
        var Thired = "{{ config('settings.slider_style_three_right_image') ?? '' }}";
        var Four = "{{ config('settings.slider_style_four_right_first_image') ?? '' }}";
        var Five = "{{ config('settings.slider_style_four_right_second_image') ?? '' }}";

        if (First != '') {
            var FirstData = "{{ asset(config('settings.slider_style_two_right_first_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('third__image', 'picture_third_image','{{ __f('Slider Style Two Right First Image Title') }}', FirstData);
            });
        }

        if (Second != '') {
            var SecondData = "{{ asset(config('settings.slider_style_two_right_second_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('fourth__image', 'picture_fourth_image','{{ __f('Slider Style Two Right Second Image Title') }}', SecondData);
            });
        }

        if (Thired != '') {
            var ThiredData = "{{ asset(config('settings.slider_style_three_right_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('fivth__image', 'picture_fivth_image','{{ __f('Slider Style Three Right Image Title') }}', ThiredData);
            });
        }

        if (Four != '') {
            var FourData = "{{ asset(config('settings.slider_style_four_right_first_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('sixth__image', 'picture_sixth_image','{{ __f('Slider Style Four Right First Image Title') }}', FourData);
            });
        }

        if (Five != '') {
            var FiveData = "{{ asset(config('settings.slider_style_four_right_second_image') ?? '') }}";
            $(function() {
                ImagePriviewUpdate('seventh__image', 'seventh_picture','{{ __f('Slider Style Four Right Second Image Title') }}', FiveData);
            });
        }

        $(document).ready(function() {
            $('#sliderStyleSettingForm').on('submit', function(e) {
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

            $('input[name="sliderchosevalue"]').on('change', function () {
               var value = $(this).val();
               if(value == 1){
                    $('#secondimageshowhide').addClass('d-none');
                    $('#thirdimageshowhide').addClass('d-none');
                    $('#fourthimageshowhide').addClass('d-none');
               }else if(value == 2){
                    $('#secondimageshowhide').removeClass('d-none');
                    $('#thirdimageshowhide').addClass('d-none');
                    $('#fourthimageshowhide').addClass('d-none');
               }else if(value == 3){
                    $('#secondimageshowhide').addClass('d-none');
                    $('#thirdimageshowhide').removeClass('d-none');
                    $('#fourthimageshowhide').addClass('d-none');
               }else if(value == 4){
                    $('#secondimageshowhide').addClass('d-none');
                    $('#thirdimageshowhide').addClass('d-none');
                    $('#fourthimageshowhide').removeClass('d-none');
               }
            });

        });
    </script>
@endpush
