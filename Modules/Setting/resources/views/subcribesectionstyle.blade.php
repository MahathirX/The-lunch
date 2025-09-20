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
                <h3 class="p-2">{{ __f('Subcribe Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="subcribeStyleSettingForm" action="{{ route('admin.setting.subcribe.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="subcribechosevalue"
                                    id="subcribechosevalue1" value="1"
                                    {{ config('settings.subcribechosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="subcribechosevalue1">
                                    {{ __f('Subcribe Style One Title') }} <br>
                                    <span
                                        class="subcribe-chose-speacal-text">{{ __f('Subcribe Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 subcribe-image-show"
                                src="{{ asset('backend/assets/subcribeimage/subcribeimage_1.png') }}"
                                alt="subcribe-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="subcribechosevalue"
                                    id="subcribechosevalue2" value="2"
                                    {{ config('settings.subcribechosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="subcribechosevalue2">
                                    {{ __f('Subcribe Style Two Title') }} <br>
                                    <span
                                        class="subcribe-chose-speacal-text">{{ __f('Subcribe Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 subcribe-image-show"
                                src="{{ asset('backend/assets/subcribeimage/subcribeimage_2.png') }}"
                                alt="subcribe-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="subcribechosevalue"
                                    id="subcribechosevalue3" value="3"
                                    {{ config('settings.subcribechosevalue') == 3 || is_null(config('settings.subcribechosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="subcribechosevalue3">
                                    {{ __f('Subcribe Style Three Title') }} <br>
                                    <span
                                        class="subcribe-chose-speacal-text">{{ __f('Subcribe Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 subcribe-image-show"
                                src="{{ asset('backend/assets/subcribeimage/subcribeimage_3.png') }}"
                                alt="subcribe-image">
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
