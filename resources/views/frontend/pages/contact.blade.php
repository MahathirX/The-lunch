@extends('layouts.frontend')
@section('title', $title)
@section('metatitle', $metatitle)
@section('metakeywords', $metakeywords)
@section('metadescription', $metadescription)
@section('content')
    <div class="page-content">
        <div class="container">
            <div class="product-details-top">
                {{-- <div class="row">
                    {!! config('settings.contactcontent') ?? '' !!}

                </div> --}}
                <div class="row">
                    <h2 class="title text-center my-4"> Contact Form</h2>
                    <div class="contact-form">
                        <form id="contactFromSubmit" action="{{ route('contact.form.submit') }}" method="POST">
                            @csrf
                            <div class="row">
                                <x-form.textbox labelName="Name" parantClass="col-12 col-md-6" name="name" type="text"
                                    required="required" placeholder="Enter Name..!" errorName="name" class="py-2"
                                    value="{{ old('name') }}"></x-form.textbox>

                                <x-form.textbox labelName="Email" parantClass="col-12 col-md-6" name="email"
                                    type="email" required="required" placeholder="Enter Email..!" errorName="email"
                                    class="py-2" value="{{ old('email') }}"></x-form.textbox>
                            </div>
                            <div class="row">
                                <x-form.textbox labelName="Phone Number" parantClass="col-12 col-md-12" name="phone"
                                    required="required" type="tel" placeholder="Enter Phone Number..!" errorName="phone"
                                    class="py-2" value="{{ old('phone') }}"></x-form.textbox>
                            </div>
                            <div class="row">
                                <x-form.textarea labelName="Comment" parantClass="col-12 col-md-12" required="required"
                                    name="comment" type="text" placeholder="Comment" errorName="comment" class="py-2"
                                    value="{{ old('comment') }}"></x-form.textarea>
                            </div>
                            <div class="d-flex justify-content-end align-items-center mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <div class="spinner-border text-light d-none contact_loader me-2" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#contactFromSubmit').on('submit', function(e) {
                e.preventDefault();
                $('.contact_loader').removeClass('d-none');
                let formData = new FormData(this);
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('.contact_loader').addClass('d-none');
                        if (res.status === 'success') {
                            flashMessage(res.status, res.message);
                            $('#contactFromSubmit')[0].reset();
                            $('.error-message').text('');
                            $('.is-invalid').removeClass('is-invalid');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('.contact_loader').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.contact_loader').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
