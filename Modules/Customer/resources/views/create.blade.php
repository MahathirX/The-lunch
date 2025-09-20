@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-gallary.css') }}">
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Customer Create Title') }}</h3>
            </div>

            <div class="card-body">
                <form id="customerForm" action="{{ route('admin.customer.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Customer Name Label Title') }}" parantClass="col-12 col-md-6" name="name"
                        required="required" placeholder="{{ __f('Customer Name Placeholder') }}" errorName="name" class="py-2"
                            value="{{ old('name') }}">
                        </x-form.textbox>

                        <x-form.textbox  labelName="{{ __f('Phone Number Label Title') }}"  required="required" parantClass="col-12 col-md-6"  name="phone" placeholder="{{ __f('Phone Number Placeholder') }}" errorName="phone"  class="py-2" value="{{ old('phone') }}"  type="tel"></x-form.textbox>
                    </div>
                    <div class="row">
                        <x-form.textbox  labelName="{{ __f('Customer Address Label Title') }}" required="required" parantClass="col-12 col-md-6" name="address"  placeholder="{{ __f('Customer Address Placeholder') }}" errorName="address" class="py-2"  value="{{ old('address') }}" type="text">  </x-form.textbox>

                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="{{ __f('Status Label Title') }}" errorName="status">
                            <option value="1">{{ __f('Status Publish Title') }}</option>
                            <option value="0">{{ __f('Status Pending Title') }}</option>
                        </x-form.selectbox>
                    </div>
                    <div class="row">
                        <x-form.textbox  labelName="{{ __f('Previous Due Label Title') }}" parantClass="col-12 col-md-6" name="previous_due"  placeholder="Enter Previous Due!" errorName="previous_due" class="py-2" type="number" value="0"></x-form.textbox>

                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium">{{ __f('Image Title') }}</label>
                            <div>
                                <label class="first__picture" for="first__image" tabIndex="0">
                                    <span class="picture__first"></span>
                                </label>
                                <input type="file" name="customer_photo" id="first__image">
                                <span class="text-danger error-text product_image-error"></span>
                            </div>
                        </div>
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
            ImagePriviewInsert('first__image', 'picture__first', '{{ __f("Choose Customer Image Title") }}');
        });
    </script>
    <script>
        $(document).ready(function() {
            @if(Auth::check() && Auth::user()->role_id == 3)
                const projectRedirectUrl = "{{ route('staff.customer.index') }}";
            @else
                const projectRedirectUrl = "{{ route('admin.customer.index') }}";
            @endif
            $('#customerForm').on('submit', function(e) {
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
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            alert(xhr.responseJSON.message);
                        } else {
                            alert('An unexpected error occurred.');
                        }
                    }
                }

                });
            });
        });
    </script>
@endpush
