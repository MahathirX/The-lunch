@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .subcribe-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .subcribe-image-show {
            height: 200px;
        }

        .subcribe-gap {
            gap: 25px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Section Show Hide Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="subcribeStyleSettingForm" action="{{ route('admin.setting.section.show.hide.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="hedershowchosevalue">
                                    {{ __f('Header Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="hedershowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="hedershowchosevalue" value="1" id="hedershowchosevalue" {{ config('settings.hedershowchosevalue') == 1 || is_null(config('settings.hedershowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="navbarshowchosevalue">
                                    {{ __f('Navbar Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="navbarshowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="navbarshowchosevalue" value="1" id="navbarshowchosevalue" {{ config('settings.navbarshowchosevalue') == 1 || is_null(config('settings.navbarshowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="herosectionshowchosevalue">
                                    {{ __f('Hero Section Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="herosectionshowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="herosectionshowchosevalue" value="1" id="herosectionshowchosevalue" {{ config('settings.herosectionshowchosevalue') == 1 || is_null(config('settings.herosectionshowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="categoriessectionshowchosevalue">
                                    {{ __f('Categories Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="categoriessectionshowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="categoriessectionshowchosevalue" value="1" id="categoriessectionshowchosevalue" {{ config('settings.categoriessectionshowchosevalue') == 1 || is_null(config('settings.categoriessectionshowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="newproductsectionshowchosevalue">
                                    {{ __f('New Product Section Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="newproductsectionshowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="newproductsectionshowchosevalue" value="1" id="newproductsectionshowchosevalue" {{ config('settings.newproductsectionshowchosevalue') == 1 || is_null(config('settings.newproductsectionshowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="dynamicproductsectionshowchosevalue">
                                    {{ __f('Dynamic Product Section Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="dynamicproductsectionshowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="dynamicproductsectionshowchosevalue" value="1" id="dynamicproductsectionshowchosevalue" {{ config('settings.dynamicproductsectionshowchosevalue') == 1 || is_null(config('settings.dynamicproductsectionshowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="brandsectionshowchosevalue">
                                    {{ __f('Brand Section Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="brandsectionshowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="brandsectionshowchosevalue" value="1" id="brandsectionshowchosevalue" {{ config('settings.brandsectionshowchosevalue') == 1 || is_null(config('settings.brandsectionshowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="subscribesectionshowchosevalue">
                                    {{ __f('Subcribe Section Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="subscribesectionshowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="subscribesectionshowchosevalue" value="1" id="subscribesectionshowchosevalue" {{ config('settings.subscribesectionshowchosevalue') == 1 || is_null(config('settings.subscribesectionshowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="footershowchosevalue">
                                    {{ __f('Footer Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="footershowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="footershowchosevalue" value="1" id="footershowchosevalue" {{ config('settings.footershowchosevalue') == 1 || is_null(config('settings.footershowchosevalue')) ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-4">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="breadcrumbshowchosevalue">
                                    {{ __f('Breadcrumb Show Hide Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-check form-switch">
                                <input type="hidden" name="breadcrumbshowchosevalue" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" name="breadcrumbshowchosevalue" value="1" id="breadcrumbshowchosevalue" {{ config('settings.breadcrumbshowchosevalue') == 1 || is_null(config('settings.breadcrumbshowchosevalue')) ? 'checked' : '' }}>
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
        $(document).ready(function() {
            $('#subcribeStyleSettingForm').on('submit', function(e) {
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
