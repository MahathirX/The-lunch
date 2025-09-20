@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .header-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }
        .form-check-label{
	        line-height: 17px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Header Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="headerStyleSettingForm" action="{{ route('admin.setting.header.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="headerchosevalue"
                                    id="headerchosevalue1" value="1"
                                    {{ config('settings.headerchosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="headerchosevalue1">
                                    {{ __f('Header Style One Title') }} <br>
                                    <span class="header-chose-speacal-text">{{ __f('Header Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100" src="{{ asset('backend/assets/headerimage/header_1.png') }}"
                                alt="header-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="headerchosevalue"
                                    id="headerchosevalue2" value="2"
                                    {{ config('settings.headerchosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="headerchosevalue2">
                                    {{ __f('Header Style Two Title') }} <br>
                                    <span class="header-chose-speacal-text">{{ __f('Header Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100" src="{{ asset('backend/assets/headerimage/header_2.png') }}"
                                alt="header-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="headerchosevalue"
                                    id="headerchosevalue3" value="3"
                                    {{ config('settings.headerchosevalue') == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="headerchosevalue3">
                                    {{ __f('Header Style Three Title') }}   <br>
                                    <span class="header-chose-speacal-text">{{ __f('Header Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100" src="{{ asset('backend/assets/headerimage/header_3.png') }}"
                                alt="header-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="headerchosevalue"
                                    id="headerchosevalue4" value="4"
                                    {{ config('settings.headerchosevalue') == 4 || is_null(config('settings.headerchosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="headerchosevalue4">
                                    {{ __f('Header Style Four Title') }} <br>
                                    <span class="header-chose-speacal-text">{{ __f('Header Style Four Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100" src="{{ asset('backend/assets/headerimage/header_4.png') }}"
                                alt="header-image">
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
            $('#headerStyleSettingForm').on('submit', function(e) {
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
