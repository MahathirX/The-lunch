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
                <h3 class="p-2">{{ __f('Supplier Edit Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="supplierEditForm" action="{{ route('admin.supplier.update',$supplier->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method("PUT")
                    <div class="row ">
                        <input type="hidden" name="update_id" value="{{ $supplier->id }}">
                        <div class="col-12 col-md-4 hidden-field mb-3" id="supplierField">
                            <x-form.textbox labelName="{{ __f('Supplier Name Label Title') }}" name="name" required="required"
                                placeholder="{{ __f('Supplier Name Placeholder') }}" errorName="name" class="py-2"
                                value="{{ $supplier->name ?? old('name')}}">
                            </x-form.textbox>
                        </div>

                        <x-form.textbox labelName="{{ __f('Company Name Label Title') }}" parantClass="col-12 col-md-4 mb-3" name="company_name"
                           placeholder="{{ __f('Company Name Placeholder') }}" errorName="company_name" class="py-2" required="required"
                            value="{{ $supplier->company_name ?? old('company_name')}}">
                        </x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Phone Number Label Title') }}"  parantClass="col-12 col-md-4 mb-3" required="required" name="phone"  placeholder="{{ __f('Phone Number Placeholder') }}"
                            errorName="phone"  class="py-2"  value="{{ $supplier->phone ?? old('phone')}}" >
                        </x-form.textbox>

                        <x-form.textbox labelName="{{ __f('Email Label Title') }}" parantClass="col-12 col-md-4 mb-3" name="email"
                            placeholder="{{ __f('Email Placeholder') }}" errorName="email" class="py-2"
                            value="{{ $supplier->email ?? old('email')}}">
                        </x-form.textbox>

                        <x-form.textbox  labelName="{{ __f('City Label Title') }}"  parantClass="col-12 col-md-4 mb-3"  name="city"  placeholder="{{ __f('City Placeholder') }}"  errorName="city"
                            class="py-2"  value="{{ $supplier->city ?? old('city')}}" >
                        </x-form.textbox>

                        <x-form.textbox required="required"  labelName="{{ __f('Supplier Address Label Title') }}"
                            parantClass="col-12 col-md-4 mb-3"  name="address" placeholder="{{ __f('Supplier Address Placeholder') }}"   errorName="address"  class="py-2"
                            value="{{ $supplier->address ?? old('address')}}">
                        </x-form.textbox>

                        <div class="col-12 col-md-4 mb-3">
                            <x-form.textbox labelName="{{ __f('Vat Number Label Title') }}" name="vat"
                                placeholder="{{ __f('Vat Number Placeholder') }}" errorName="vat" class="py-2"
                                value="{{ $supplier->vat ?? old('vat')}}">
                            </x-form.textbox>
                        </div>

                        <x-form.textbox  labelName="{{ __f('Country Label Title') }}"  parantClass="col-12 col-md-4 mb-3"  name="country"   placeholder="{{ __f('Country Placeholder') }}"  errorName="country"
                            class="py-2"  value="{{ $supplier->country ?? old('country')}}">
                        </x-form.textbox>

                        <x-form.textbox  labelName="{{ __f('State Label Title') }}"  parantClass="col-12 col-md-4 mb-3"   name="state" placeholder="{{ __f('State Placeholder') }}"  errorName="state"
                            class="py-2"  value="{{ $supplier->state ?? old('state')}}">
                        </x-form.textbox>

                        <x-form.textbox
                            labelName="{{ __f('Postal Code Label Title') }}" parantClass="col-12 col-md-4 mb-3" name="postal_code"
                            placeholder="{{ __f('Postal Code Placeholder') }}" errorName="postal_code" class="py-2"
                            value="{{ $supplier->postal_code ?? old('postal_code')}}">
                        </x-form.textbox>

                        <div class="col-12 col-md-4">
                            <x-form.selectbox parentClass="col-12 col-md-6 mb-3" name="status" labelName="{{ __f('Status Title') }}" errorName="status" required="required">
                                <option value="1" {{ $supplier->status == 1 ? 'selected' : '' }}>{{ __f('Status Publish Title') }}</option>
                                <option value="0" {{ $supplier->status == 0 ? 'selected' : '' }}>{{ __f('Status Pending Title') }}</option>
                            </x-form.selectbox>
                        </div>
                        <x-form.textbox  labelName="{{ __f('Previous Due Title') }}"  parantClass="col-12 col-md-4 mb-3"  name="previous_due" placeholder="{{ __f('Previous Due Placeholder') }}" errorName="previous_due" type="number" class="py-2" value="{{ convertToLocaleNumber($supplier->previous_due ?? old('previous_due')) }}">
                        </x-form.textbox>
                        <div class="col-12 col-md-4">
                              <label class="text-dark font-weight-medium">{{ __f('Image Title') }}</label>
                              <div>
                                  <label class="first__picture" for="first__image" tabindex="0">
                                      <span class="picture__first"></span>
                                  </label>
                                  <input type="file" name="photo" id="first__image">
                                  <span class="text-danger error-text photo-error"></span>
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
            ImagePriviewInsert('first__image', 'picture__first', '{{ __f("Choose Supplier Image Title") }}');
        });

        var productimage = "{{ $supplier->photo ?? '' }}";
        if (productimage != '') {
            var myFData = "{{ asset($supplier->photo ?? '') }}";
            $(function() {
                ImagePriviewUpdate('first__image', 'picture__first', '{{ __f("Choose Supplier Image Title") }}', myFData);
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            @if(Auth::check() && Auth::user()->role_id == 3)
                const projectRedirectUrl = "{{ route('staff.supplier.index') }}";
            @else
                const projectRedirectUrl = "{{ route('admin.supplier.index') }}";
            @endif
            $('#supplierEditForm').on('submit', function(e) {
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
