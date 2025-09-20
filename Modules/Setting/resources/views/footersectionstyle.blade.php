@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .footer-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .footer-image-show {
            height: 200px;
        }

        .footer-gap {
            gap: 25px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Footer Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="footerStyleSettingForm" action="{{ route('admin.setting.footer.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="footerchosevalue"
                                    id="footerchosevalue1" value="1"
                                    {{ config('settings.footerchosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="footerchosevalue1">
                                    {{ __f('Footer Style One Title') }} <br>
                                    <span
                                        class="footer-chose-speacal-text">{{ __f('Footer Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 footer-image-show"
                                src="{{ asset('backend/assets/footerimage/footerimage_1.png') }}"
                                alt="footer-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="footerchosevalue"
                                    id="footerchosevalue2" value="2"
                                    {{ config('settings.footerchosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="footerchosevalue2">
                                    {{ __f('Footer Style Two Title') }} <br>
                                    <span
                                        class="footer-chose-speacal-text">{{ __f('Footer Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 footer-image-show"
                                src="{{ asset('backend/assets/footerimage/footerimage_2.png') }}"
                                alt="footer-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="footerchosevalue"
                                    id="footerchosevalue3" value="3"
                                    {{ config('settings.footerchosevalue') == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="footerchosevalue3">
                                    {{ __f('Footer Style Three Title') }} <br>
                                    <span
                                        class="footer-chose-speacal-text">{{ __f('Footer Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 footer-image-show"
                                src="{{ asset('backend/assets/footerimage/footerimage_3.png') }}"
                                alt="footer-image">
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="footerchosevalue"
                                    id="footerchosevalue4" value="4"
                                    {{ config('settings.footerchosevalue') == 4 ? 'checked' : '' }}>
                                <label class="form-check-label" for="footerchosevalue4">
                                    {{ __f('Footer Style Four Title') }} <br>
                                    <span
                                        class="footer-chose-speacal-text">{{ __f('Footer Style Four Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 footer-image-show"
                                src="{{ asset('backend/assets/footerimage/footerimage_4.png') }}"
                                alt="footer-image">
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="footerchosevalue"
                                    id="footerchosevalue5" value="5"
                                    {{ config('settings.footerchosevalue') == 5 || is_null(config('settings.footerchosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="footerchosevalue5">
                                    {{ __f('Footer Style Five Title') }} <br>
                                    <span
                                        class="footer-chose-speacal-text">{{ __f('Footer Style Five Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 footer-image-show"
                                src="{{ asset('backend/assets/footerimage/footerimage_5.png') }}"
                                alt="footer-image">
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
            $('#footerStyleSettingForm').on('submit', function(e) {
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
