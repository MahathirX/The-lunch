@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <style>
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
                <h3 class="p-2">{{ __f('Slider Create Title') }}</h3>
            </div>

            <div class="card-body">
                <form id="sliderCreateForm" action="{{ route('admin.home.slider.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    {{-- <div class="row">
                        <x-form.textbox labelName="{{ __f('Slider Title Label Title') }}" parantClass="col-12 col-md-6" name="title" required="required"
                            placeholder="{{ __f('Slider Title Placeholder') }}" errorName="title" class="py-2"
                            value="{{ old('title') }}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Slider Sub Title Label Title') }}" parantClass="col-12 col-md-6" name="sub_title"
                            required="required" placeholder="{{ __f('Slider Sub Title Placeholder') }}" errorName="sub_title" class="py-2"
                            value="{{ old('sub_title') }}"></x-form.textbox>
                    </div> --}}
                    {{-- <div class="row">
                        <x-form.textbox labelName="{{ __f('Regular Price Label Title') }}" parantClass="col-12 col-md-6" name="regular_price"
                            required="required" type="number" placeholder="{{ __f('Regular Price Placeholder') }}" errorName="regular_price"
                            class="py-2" value="{{ old('regular_price') }}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Discount Price Label Title') }}" parantClass="col-12 col-md-6" name="discount_price"
                            required="required" type="number" placeholder="{{ __f('Discount Price Placeholder') }}" errorName="discount_price"
                            class="py-2" value="{{ old('discount_price') }}"></x-form.textbox>
                    </div> --}}
                    {{-- <div class="row">
                        <x-form.textbox labelName="{{ __f('Discount Price Sub Price Label Title') }} " parantClass="col-12 col-md-6" name="discount_price_sub"
                        required="required" type="number" placeholder="{{ __f('Discount Price Sub Price Placeholder') }}" errorName="discount_price_sub"
                        class="py-2" value="{{ old('discount_price_sub') }}"></x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Button Text Label Title') }}" parantClass="col-12 col-md-6" name="btn_text"
                            required="required" placeholder="{{ __f('Button Text Placeholder') }}" errorName="btn_text"
                            class="py-2" value="{{ old('btn_text') }}"></x-form.textbox>
                    </div> --}}
                    {{-- <div class="row">
                        <x-form.textbox labelName="{{ __f('Button Url Label Title') }}" parantClass="col-12 col-md-6" name="btn_url"
                        required="required" placeholder="{{ __f('Button Url Placeholder') }}" errorName="btn_url" class="py-2"
                        value="{{ old('btn_url') }}"></x-form.textbox>

                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="btn_target"
                            required="required" labelName="{{ __f('Target Title') }}" errorName="btn_target">
                            <option value="0">{{ __f('Target Same Page Title') }}</option>
                            <option value="1">{{ __f('Target New Page Title') }}</option>
                        </x-form.selectbox>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium required">{{ __f('Image Title') }} 
                                <span id="slider-image-dimensions">
                                    @if(config('settings.sliderchosevalue') == 1)
                                        ({{ __f('Slider Style One Image Dimensions') }})
                                    @elseif(config('settings.sliderchosevalue') == 2)
                                        ({{ __f('Slider Style Two Image Dimensions') }})
                                    @elseif(config('settings.sliderchosevalue') == 3)
                                        ({{ __f('Slider Style Three Image Dimensions') }})
                                    @elseif(config('settings.sliderchosevalue') == 4)
                                        ({{ __f('Slider Style Four Image Dimensions') }})
                                    @else
                                        ({{ __f('Slider Style Five Image Dimensions') }})
                                    @endif
                                </span>
                            </label>
                            <div>
                                <label class="first__picture" for="first__image" tabIndex="0">
                                    <span class="picture__first"></span>
                                </label>
                                <input type="file" name="slider_image" id="first__image" accept="image/*">
                                <span class="text-danger error-text slider_image-error"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium required">{{ __f('Image Mobile Device Title') }} 
                                <span id="slider-image-dimensions">
                                    @if(config('settings.sliderchosevalue') == 1)
                                        ({{ __f('Slider Style One Mobile Image Dimensions') }})
                                    @elseif(config('settings.sliderchosevalue') == 2)
                                        ({{ __f('Slider Style Two Mobile Image Dimensions') }})
                                    @elseif(config('settings.sliderchosevalue') == 3)
                                        ({{ __f('Slider Style Three Mobile Image Dimensions') }})
                                    @elseif(config('settings.sliderchosevalue') == 4)
                                        ({{ __f('Slider Style Four Mobile Image Dimensions') }})
                                    @else
                                        ({{ __f('Slider Style Five Mobile Image Dimensions') }})
                                    @endif
                                </span>
                            </label>
                            <div>
                                <label class="second_picture" for="second__image" tabIndex="0">
                                    <span class="picture__second"></span>
                                </label>
                                <input type="file" name="slider_m_image" id="second__image" accept="image/*">
                                <span class="text-danger error-text slider_m_image-error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="{{ __f('Status Title') }}" errorName="status">
                            <option value="1">{{ __f('Status Publish Title') }}</option>
                            <option value="0">{{ __f('Status Pending Title') }}</option>
                        </x-form.selectbox>

                        <x-form.textbox labelName="{{ __f('Order By Title') }}" parantClass="col-12 col-md-6" name="order_by"
                        required="required" placeholder="Enter Slider Order By..!" errorName="order_by" class="py-2"
                        value="{{ $countSlider ? $countSlider->count() + 1 : old('order_by') }}"></x-form.textbox>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-2">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none" role="status">
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
            ImagePriviewInsert('first__image', 'picture__first', '{{ __f("Slider Image Placeholder") }}');
            ImagePriviewInsert('second__image', 'picture__second', '{{ __f("Slider Mobile Image Placeholder") }}');
        });
    </script>
    <script>
        $(document).ready(function() {
            const projectRedirectUrl = "{{ route('admin.home.slider') }}";
            $('#sliderCreateForm').on('submit', function(e) {
                e.preventDefault();
                $('.spinner-border').removeClass('d-none');
                $('.error-text').text('');
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
                                window.location.href = projectRedirectUrl;
                            }, 100);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.spinner-border').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.spinner-border').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
