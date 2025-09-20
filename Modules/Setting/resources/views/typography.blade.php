@extends('layouts.app')
@section('title', $title)
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Typography Setting Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="typographyFromSetting" action="{{ route('admin.setting.typography.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-3 form-group px-3">
                        <div class="col-md-6">
                            <label for="">{{ __f('Theme Background Title') }}</label>
                            <div class="form-check col-12 col-md-6">
                                @if (config('settings.backgroundtype') == '1')
                                    <input class="form-check-input" type="radio" name="bacroundtype"
                                    id="singlecolor" checked value="1">
                                @else
                                    <input class="form-check-input" type="radio" name="bacroundtype"
                                    id="singlecolor" value="1">
                                @endif
                                <label class="form-check-label" for="singlecolor">
                                    {{ __f('Background Color Title') }}
                                </label>
                            </div>
                            <div class="form-check">
                                @if (config('settings.backgroundtype') == '2')
                                    <input class="form-check-input" type="radio" name="bacroundtype"
                                    id="gradientcolor" checked value="2">
                                @else
                                    <input class="form-check-input" type="radio" name="bacroundtype"
                                    id="gradientcolor" value="2">
                                @endif
                                <label class="form-check-label" for="gradientcolor">
                                    {{ __f('Gradient Background Title') }} 
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="colorInputs" class="mt-3">

                            </div>
                        </div>
                    </div>

                    <div class="row mt-3 form-group px-3">
                        <div class="col-md-6">
                            <label for="primarycolor">{{ __f('Primary Color Title') }}</label>
                            <input type="color" id="primarycolor" name="primarycolor" class="form-control mt-2" value="{{ config('settings.primarycolor') ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <label for="primarywhitecolor">{{ __f('White Color Title') }}</label>
                            <input type="color" id="primarywhitecolor" name="primarywhitecolor" class="form-control mt-2" value="{{ config('settings.primarywhitecolor') ?? '' }}">
                        </div>
                    </div>
                    <div class="row mt-3 form-group px-3">
                        <div class="col-md-6">
                            <label for="secondarycolor">{{ __f('Secondary Color Title') }}</label>
                            <input type="color" id="secondarycolor" name="secondarycolor" class="form-control mt-2" value="{{ config('settings.secondarycolor') ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <label for="gadientcolor">{{ __f('Gadient Color Title') }}</label>
                            <input type="color" id="gadientcolor" name="gadientcolor" class="form-control mt-2" value="{{ config('settings.gadientcolor') ?? '' }}">
                        </div>
                    </div>
                    <div class="row mt-3 form-group px-3">
                        <div class="col-md-6">
                            <label for="bordercolor">{{ __f('Border Color Title') }}</label>
                            <input type="color" id="bordercolor" name="bordercolor" class="form-control mt-2" value="{{ config('settings.bordercolor') ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <label for="hovercolor">{{ __f('Hover Color Title') }}</label>
                            <input type="color" id="hovercolor" name="hovercolor" class="form-control mt-2" value="{{ config('settings.hovercolor') ?? '' }}">
                        </div>
                    </div>
                    <div class="row mt-3 form-group px-3">
                        <div class="col-md-6">
                            <label for="primaryredcolor">{{ __f('Primary Red Color Title') }}</label>
                            <input type="color" id="primaryredcolor" name="primaryredcolor" class="form-control mt-2" value="{{ config('settings.primaryredcolor') ?? '' }}">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none typography_setting" role="status">
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
            $('#typographyFromSetting').on('submit', function(e) {
                e.preventDefault();
                $('.typography_setting').removeClass('d-none');
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
                            $('.typography_setting').addClass('d-none');
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '-error').text(value[0]);
                            });
                        } else {
                            $('.typography_setting').addClass('d-none');
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });





        });
        $(document).ready(function() {
            function updateColorInputs() {
                const colorInputsContainer = $('#colorInputs');
                colorInputsContainer.empty();
                var singlebackround = "{{ config('settings.singlebackround') }}";
                var gradientone = "{{ config('settings.gradientone') }}";
                var gradienttwo = "{{ config('settings.gradienttwo') }}";

                if ($('#singlecolor').is(':checked')) {
                    colorInputsContainer.append(`
                    <label for="singlebackround">{{ __f('Choose Color Title') }}:</label>
                    <input type="color" id="singlebackround" name="singlebackround" class="form-control mt-2" value="${singlebackround ?? ''}">`);
                } else if ($('#gradientcolor').is(':checked')) {
                    colorInputsContainer.append(`
                    <label for="gradientone">{{ __f('Choose First Color Title') }}:</label>
                    <input type="color" id="gradientone" name="gradientone" class="form-control mt-2" value="${gradientone ?? ''}">
                    <label for="gradienttwo">{{ __f('Choose Second Color Title') }}:</label>
                    <input type="color" id="gradienttwo" name="gradienttwo" class="form-control mt-2" value="${gradienttwo ?? ''}">`);
                }
            }
            updateColorInputs();
            $('input[name="bacroundtype"]').on('change', updateColorInputs);
        });
    </script>
@endpush
