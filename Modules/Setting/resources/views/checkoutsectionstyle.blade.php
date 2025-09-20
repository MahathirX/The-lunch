@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .check-out-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }

        .form-check-label {
            line-height: 17px;
        }

        .check-out-image-show {
            max-height: 200px;
        }

        .check-out-gap {
            gap: 25px;
        }
        #check-out-image-dimensions{
            font-size: 11px;
            color: #ff6000c9;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Check Out Page Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="checkOutStyleSettingForm" action="{{ route('admin.setting.check.out.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <label class="form-check-label" for="">
                                    {{ __f('Check Out Page Form Position Title') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="d-flex align-items-center brand-gap">
                                <div>
                                    <label class="form-check-label" for="checkoutpagefrompositionchosevalue1">
                                        {{ __f('Right Title') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="checkoutpagefrompositionchosevalue"
                                        id="checkoutpagefrompositionchosevalue1" value="1"
                                        {{ config('settings.checkoutpagefrompositionchosevalue') == 1 ? 'checked' : '' }}>
                                </div>
                                <div class="ms-2">
                                    <label class="form-check-label" for="checkoutpagefrompositionchosevalue2">
                                        {{ __f('Left Title') }}
                                    </label>
                                    <input class="form-check-input" type="radio" name="checkoutpagefrompositionchosevalue"
                                        id="checkoutpagefrompositionchosevalue2" value="2"
                                         {{ config('settings.checkoutpagefrompositionchosevalue') == 2 || is_null(config('settings.checkoutpagefrompositionchosevalue')) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkoutpageshowchosevalue"
                                    id="checkoutpageshowchosevalue1" value="1"
                                    {{ config('settings.checkoutpageshowchosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="checkoutpageshowchosevalue1">
                                    {{ __f('Check Out Page Style One Title') }} <br>
                                    <span
                                        class="check-out-chose-speacal-text">{{ __f('Check Out Page Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 check-out-image-show"
                                src="{{ asset('backend/assets/checkoutpage/checkoutpage_1.png') }}"
                                alt="check-out-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkoutpageshowchosevalue"
                                    id="checkoutpageshowchosevalue2" value="2"
                                    {{ config('settings.checkoutpageshowchosevalue') == 2  ? 'checked' : '' }}>
                                <label class="form-check-label" for="checkoutpageshowchosevalue2">
                                    {{ __f('Check Out Page Style Two Title') }} <br>
                                    <span
                                        class="check-out-chose-speacal-text">{{ __f('Check Out Page Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 check-out-image-show"
                                src="{{ asset('backend/assets/checkoutpage/checkoutpage_2.png') }}"
                                alt="check-out-image">
                        </div>
                    </div>
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="checkoutpageshowchosevalue"
                                    id="checkoutpageshowchosevalue2" value="3"
                                    {{ config('settings.checkoutpageshowchosevalue') == 3 || is_null(config('settings.checkoutpageshowchosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checkoutpageshowchosevalue3">
                                    {{ __f('Check Out Page Style Three Title') }} <br>
                                    <span
                                        class="check-out-chose-speacal-text">{{ __f('Check Out Page Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 check-out-image-show"
                                src="{{ asset('backend/assets/checkoutpage/checkoutpage_3.png') }}"
                                alt="check-out-image">
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
            $('#checkOutStyleSettingForm').on('submit', function(e) {
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
