@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .category-details-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .category-details-image-show {
            max-height: 200px;
        }

        .category-details-gap {
            gap: 25px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Category Details Page Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="categoryDetailsStyleSettingForm" action="{{ route('admin.setting.category.details.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Category Details Category Fillter Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="categorydetailsfilltershowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailsfilltershowchosevalue"
                                        id="categorydetailsfilltershowchosevalue1" value="1"
                                        {{ config('settings.categorydetailsfilltershowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="categorydetailsfilltershowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailsfilltershowchosevalue"
                                        id="categorydetailsfilltershowchosevalue2" value="2"
                                         {{ config('settings.categorydetailsfilltershowchosevalue') == 2 || is_null(config('settings.categorydetailsfilltershowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Category Details Brand Fillter Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="categorydetailsbrandfilltershowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailsbrandfilltershowchosevalue"
                                        id="categorydetailsbrandfilltershowchosevalue1" value="1"
                                        {{ config('settings.categorydetailsbrandfilltershowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="categorydetailsbrandfilltershowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailsbrandfilltershowchosevalue"
                                        id="categorydetailsbrandfilltershowchosevalue2" value="2"
                                         {{ config('settings.categorydetailsbrandfilltershowchosevalue') == 2 || is_null(config('settings.categorydetailsbrandfilltershowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Category Details Price Fillter Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="categorydetailspricefilltershowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailspricefilltershowchosevalue"
                                        id="categorydetailspricefilltershowchosevalue1" value="1"
                                        {{ config('settings.categorydetailspricefilltershowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="categorydetailspricefilltershowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailspricefilltershowchosevalue"
                                        id="categorydetailspricefilltershowchosevalue2" value="2"
                                         {{ config('settings.categorydetailspricefilltershowchosevalue') == 2 || is_null(config('settings.categorydetailspricefilltershowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Category Details Srot By Fillter Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="categorydetailssortbyfilltershowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailssortbyfilltershowchosevalue"
                                        id="categorydetailssortbyfilltershowchosevalue1" value="1"
                                        {{ config('settings.categorydetailssortbyfilltershowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="categorydetailssortbyfilltershowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailssortbyfilltershowchosevalue"
                                        id="categorydetailssortbyfilltershowchosevalue2" value="2"
                                         {{ config('settings.categorydetailssortbyfilltershowchosevalue') == 2 || is_null(config('settings.categorydetailssortbyfilltershowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Category Details Card Style Show Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="categorydetailscardstyleshowchosevalue1">
                                        {{ __f('No Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailscardstyleshowchosevalue"
                                        id="categorydetailscardstyleshowchosevalue1" value="1"
                                        {{ config('settings.categorydetailscardstyleshowchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="categorydetailscardstyleshowchosevalue2">
                                        {{ __f('Yes Text') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailscardstyleshowchosevalue"
                                        id="categorydetailscardstyleshowchosevalue2" value="2"
                                         {{ config('settings.categorydetailscardstyleshowchosevalue') == 2 || is_null(config('settings.categorydetailscardstyleshowchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Category Details Sidebar Position Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="categorydetailsnavbarposition1">
                                        {{ __f('Right Title') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailsnavbarposition"
                                        id="categorydetailsnavbarposition1" value="1"
                                        {{ config('settings.categorydetailsnavbarposition') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="categorydetailsnavbarposition2">
                                        {{ __f('Left Title') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="categorydetailsnavbarposition"
                                        id="categorydetailsnavbarposition2" value="2"
                                         {{ config('settings.categorydetailsnavbarposition') == 2 || is_null(config('settings.categorydetailsnavbarposition')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="categorydetailspagestylechosevalue"
                                    id="categorydetailspagestylechosevalue1" value="1"
                                    {{ config('settings.categorydetailspagestylechosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="categorydetailspagestylechosevalue1">
                                    {{ __f('Category Details Page Style One Title') }} <br>
                                    <span
                                        class="category-details-chose-speacal-text">{{ __f('Category Details Page Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 category-details-image-show"
                                src="{{ asset('backend/assets/categorydetailspage/categorydetailspage_1.png') }}"
                                alt="category-details-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="categorydetailspagestylechosevalue"
                                    id="categorydetailspagestylechosevalue2" value="2"
                                    {{ config('settings.categorydetailspagestylechosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="categorydetailspagestylechosevalue2">
                                    {{ __f('Category Details Page Style Two Title') }} <br>
                                    <span
                                        class="category-details-chose-speacal-text">{{ __f('Category Details Page Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 category-details-image-show"
                                src="{{ asset('backend/assets/categorydetailspage/categorydetailspage_2.png') }}"
                                alt="category-details-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="categorydetailspagestylechosevalue"
                                    id="categorydetailspagestylechosevalue3" value="3"
                                    {{ config('settings.categorydetailspagestylechosevalue') == 3 || is_null(config('settings.categorydetailspagestylechosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="categorydetailspagestylechosevalue3">
                                    {{ __f('Category Details Page Style Three Title') }} <br>
                                    <span
                                        class="category-details-chose-speacal-text">{{ __f('Category Details Page Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 category-details-image-show"
                                src="{{ asset('backend/assets/categorydetailspage/categorydetailspage_3.png') }}"
                                alt="category-details-image">
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
            $('#categoryDetailsStyleSettingForm').on('submit', function(e) {
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
