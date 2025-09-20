@extends('layouts.app')
@section('title',$title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f("Brand Create From Title") }}</h3>
            </div>
            <div class="card-body">
                <form id="BrandForm" action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Brand Name Label Title') }}" parantClass="col-12 col-md-6" name="name"
                            placeholder="{{ __f('Brand Name Placeholder Title') }}" errorName="name" class="py-2"
                            value="{{ old('name') }}">
                            <span class="text-danger error-text image-error"></span>
                           </x-form.textbox>

                            <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="{{ __f('Status Label Title') }}" errorName="status">
                            <option value="1">{{ __f("Status Publish Title") }}</option>
                            <option value="0">{{ __f("Status Pending Title") }}</option>
                        </x-form.selectbox>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium">{{ __f("Image Label Title") }}</label>
                            <div>
                                <label class="first__picture" for="first__image" tabIndex="0">
                                    <span class="picture__first"></span>
                                </label>
                                <input type="file" name="image" id="first__image" accept="image/*">
                                <span class="text-danger error-text image-error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                            <div class="spinner-border text-light d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f("Submit Title") }}
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
            ImagePriviewInsert('first__image', 'picture__first', '{{ __f("Brand Image Placeholder Title") }}');
        });
        $(document).ready(function() {
            @if(Auth::check() && Auth::user()->role_id == 3)
                var projectRedirectUrl = "{{ route('staff.brand.index') }}";
            @else
                var projectRedirectUrl = "{{ route('admin.brand.index') }}";
            @endif

            $('#BrandForm').on('submit', function(e) {
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
