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
                <h3 class="p-2">{{ __f('Customer Edit Title') }}</h3>
            </div>

            <div class="card-body">
                <form id="customerEditForm" action="{{ route('admin.customer.update',$customer->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <input type="hidden" name="update_id" value="{{ $customer->id }}">
                        <x-form.textbox labelName="{{ __f('Customer Name Label Title') }}" parantClass="col-12 col-md-6" name="name"
                           placeholder="{{ __f('Customer Name Placeholder') }}" errorName="name" class="py-2" required="required"
                            value="{{ $customer->customer_name ?? old('name')  }}">
                        </x-form.textbox>

                        <x-form.textbox  labelName="{{ __f('Phone Number Label Title') }}" parantClass="col-12 col-md-6"  name="phone"   placeholder="{{ __f('Phone Number Placeholder') }}" required="required" errorName="phone" class="py-2" value="{{   $customer->phone ?? old('phone') }}" type="tel"></x-form.textbox>
                    </div >
                    <div class="row">
                        <x-form.textbox  labelName="{{ __f('Customer Address Label Title') }}" parantClass="col-12 col-md-6"  name="address"  placeholder="{{ __f('Customer Address Placeholder') }}" required="required" errorName="address"  class="py-2"  value="{{ $customer->address ?? old('address') }}">
                        </x-form.textbox>
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="{{ __f('Status Label Title') }}" errorName="status">
                            <option {{ $customer->status == '1' ? 'selected': '' }} value="1" >{{ __f('Status Publish Title') }}</option>
                            <option {{ $customer->status == '0' ? 'selected': '' }} value="0" >{{ __f('Status Pending Title') }}</option>
                        </x-form.selectbox>
                    </div>
                    <div class="row">
                        <x-form.textbox  labelName="Previous Due" parantClass="col-12 col-md-6" name="previous_due"  placeholder="Enter Previous Due!" errorName="previous_due" class="py-2"  value="{{ $customer->previous_due ?? 0 }}" type="number">  </x-form.textbox>
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
    var productimage = "{{ $customer->photo ?? '' }}";
    if (productimage != '') {
        var myFData = "{{ asset($customer->photo ?? '') }}";
        $(function() {
            ImagePriviewUpdate('first__image', 'picture__first', '{{ __f("Choose Customer Image Title") }}', myFData);
        });
    }
</script>
<script>
    $(document).ready(function() {
        @if(Auth::check() && Auth::user()->role_id == 3)
            const projectRedirectUrl = "{{ route('staff.customer.index') }}";
        @else
            const projectRedirectUrl = "{{ route('admin.customer.index') }}";
        @endif

        $('#customerEditForm').on('submit', function(e) {
            e.preventDefault();
            $('.spinner-border').removeClass('d-none');
            $('.error-text').text('');
            let formData = new FormData(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
                    console.log(res)
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
