@extends('layouts.app')
@section('title', $title)
@push('styles')
<link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
@endpush
@section('content')
    <div class="dashboard">
        <div class="card p-4">
            <div>
                <h3 class="pb-1">{{ __f('Admin Profile My Profile Title') }}</h3>
                <form id="userProfileUpdate" action="{{ route('user.dashboard.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="" class="required">{{ __f('Admin Profile First Name Title') }}</label>
                                <input type="text" placeholder="{{ __f('Admin Profile First Name Placeholder') }}" name="fname"
                                    class="form-control" value="{{ Auth::check() ? Auth::user()->fname ?? '' : '' }}">
                                <p class="text-danger fname-error mb-0"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="" class="required">{{ __f('Admin Profile Last Name Title') }}</label>
                                <input type="text" placeholder="{{ __f('Admin Profile Last Name Placeholder') }}" name="lname" class="form-control"
                                    value="{{ Auth::check() ? Auth::user()->lname ?? '' : '' }}">
                                <p class="text-danger lname-error mb-0"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="" class="required">{{ __f('Admin Profile Email Title') }}</label>
                                <input type="text" readonly disabled placeholder="{{ __f('Admin Profile Email Placeholder') }}" name="email"
                                    class="form-control" value="{{ Auth::check() ? Auth::user()->email ?? '' : '' }}">
                                <p class="text-danger email-error mb-0"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="" class="required">{{ __f('Admin Profile Phone Number Title') }}</label>
                                <input type="text" placeholder="{{ __f('Admin Profile Phone Number Placeholder') }}" name="phone"
                                    class="form-control" value="{{ Auth::check() ? Auth::user()->phone ?? '' : '' }}">
                                <p class="text-danger phone-error mb-0"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="">{{ __f('Admin Profile House Number Title') }}</label>
                                <input type="text" placeholder="{{ __f('Admin Profile House Number Placeholder') }}" name="house_number"
                                    class="form-control"
                                    value="{{ Auth::check() ? Auth::user()->house_number ?? '' : '' }}">
                                <p class="text-danger house_number-error mb-0"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">{{ __f('Admin Profile City Title') }}</label>
                                <input type="text" placeholder="{{ __f('Admin Profile City Placeholder') }}" name="city" class="form-control"
                                    value="{{ Auth::check() ? Auth::user()->city ?? '' : '' }}">
                                <p class="text-danger city-error mb-0"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="">{{ __f('Admin Profile State Title') }}</label>
                                <input type="text" placeholder="{{ __f('Admin Profile State Placeholder') }}" name="state" class="form-control"
                                    value="{{ Auth::check() ? Auth::user()->state ?? '' : '' }}">
                                <p class="text-danger state-error mb-0"></p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="">{{ __f('Admin Profile Zip Title') }}</label>
                                <input type="text" placeholder="{{ __f('Admin Profile Zip Placeholder') }}" name="zip" class="form-control"
                                    value="{{ Auth::check() ? Auth::user()->zip ?? '' : '' }}">
                                <p class="text-danger zip-error mb-0"></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="text-dark font-weight-medium">{{ __f('Admin Profile Avater Title') }}</label>
                                <div>
                                    <label class="first__picture" for="first__image" tabIndex="0">
                                        <span class="picture__first"></span>
                                    </label>
                                    <input type="file" name="avater" id="first__image" accept="image/*">
                                    <span class="text-danger error-text avater-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary d-flex align-items-center">
                                    <div class="spinner-border text-light me-2 d-none  update-user-info" role="status">
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
    $(function() {
        ImagePriviewInsert('first__image', 'picture__first', '{{ __f('Admin Profile Avater Placeholder') }}');
    });

    var userImage = "{{ Auth::user()->avater ?? '' }}";

    if (userImage != '') {
        var myUserImage = "{{ asset(Auth::user()->avater ?? '') }}";
        $(function() {
            ImagePriviewUpdate('first__image', 'picture__first', '{{ __f('Admin Profile Avater Placeholder') }}', myUserImage);
        });
    }

    $('#userProfileUpdate').on('submit', function(e) {
        e.preventDefault();
        $('.update-user-info').removeClass('d-none');
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
                        location.reload();
                    }, 500);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $('.update-user-info').addClass('d-none');
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key + '-error').text(value[0]);
                    });
                } else {
                    $('.update-user-info').addClass('d-none');
                    console.log('Something went wrong. Please try again.');
                }
            }
        });
    });
</script>
@endpush
