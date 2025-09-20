@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        .category-chose-speacal-text {
            font-size: 12px;
            color: #f7560e;
        }
        .form-check-label{
	        line-height: 17px;
        }
        .category-image-show{
            height : 200px;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Category Chose Form Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="categoryStyleSettingForm" action="{{ route('admin.setting.category.section.style.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="categorychosevalue"
                                    id="categorychosevalue1" value="1"
                                    {{ config('settings.categorychosevalue') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="categorychosevalue1">
                                    {{ __f('Category Style One Title') }} <br>
                                    <span class="category-chose-speacal-text">{{ __f('Category Style One Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 category-image-show" src="{{ asset('backend/assets/categoryimage/category_1.png') }}"
                                alt="category-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="categorychosevalue"
                                    id="categorychosevalue2" value="2"
                                    {{ config('settings.categorychosevalue') == 2 ? 'checked' : '' }}>
                                <label class="form-check-label" for="categorychosevalue2">
                                    {{ __f('Category Style Two Title') }} <br>
                                    <span class="category-chose-speacal-text">{{ __f('Category Style Two Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 category-image-show" src="{{ asset('backend/assets/categoryimage/category_2.png') }}"
                                alt="category-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="categorychosevalue"
                                    id="categorychosevalue3" value="3"
                                    {{ config('settings.categorychosevalue') == 3 ? 'checked' : '' }}>
                                <label class="form-check-label" for="categorychosevalue3">
                                    {{ __f('Category Style Three Title') }}   <br>
                                    <span class="category-chose-speacal-text">{{ __f('Category Style Three Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 category-image-show" src="{{ asset('backend/assets/categoryimage/category_3.png') }}"
                                alt="category-image">
                        </div>
                    </div>

                    <div class="row g-5 mb-5">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="categorychosevalue"
                                    id="categorychosevalue4" value="4"
                                    {{ config('settings.categorychosevalue') == 4 || is_null(config('settings.categorychosevalue')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="categorychosevalue4">
                                    {{ __f('Category Style Four Title') }} <br>
                                    <span class="category-chose-speacal-text">{{ __f('Category Style Four Optional Title') }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <img class="w-100 category-image-show" src="{{ asset('backend/assets/categoryimage/category_4.png') }}"
                                alt="category-image">
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
            $('#categoryStyleSettingForm').on('submit', function(e) {
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
