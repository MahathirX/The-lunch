@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
@endpush
@section('content')
    <div class="dashboard">
        <div class="card p-4">
            <div>
                <h3 class="pb-1">{{ __f('Mail Update Title') }}</h3>
                <form id="mailUpdate" action="{{ route('admin.dashboard.mail.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="" class="required">{{ __f('Mail App Email Title') }}</label>
                                <input type="email" placeholder="{{ __f('Mail App Email Placeholder') }}" name="email"
                                    class="form-control" value="{{ config('settings.app_mail_email') ?? old('email') }}"
                                    >
                                <p class="text-danger email-error mb-0"></p>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="" class="required">{{ __f('Mail App Password Title') }}</label>
                                <input placeholder="{{ __f('Mail App Password Placeholder') }}" type="text" name="password"
                                    class="form-control" value="{{ config('settings.app_mail_password') ?? old('password') }}">
                                <p class="text-danger password-error mb-0"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary d-flex align-items-center mt-2">
                                    <div class="spinner-border text-light me-2 d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>{{ __f('Submit Title') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#mailUpdate').on('submit', function(e) {
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
                        $('.spinner-border').addClass('d-none');
                    } else {
                        flashMessage(res.status, res.message);
                        $('.spinner-border').addClass('d-none');
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
    </script>
@endpush
