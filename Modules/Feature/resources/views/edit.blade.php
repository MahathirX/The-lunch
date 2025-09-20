@extends('layouts.app')
@section('title',$title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Feature Edit Form</h3>
            </div>
            <div class="card-body">
                <form id="featureEditForm" action="{{ route('admin.feature.update',$feature->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <input type="hidden" name="update_id" value="{{ $feature->id }}">
                        <x-form.textbox labelName="Icon Title" parantClass="col-12 col-md-6" name="title"
                            required="required" placeholder="Enter Icon Title..!" errorName="title" class="py-2"
                            value="{{ old('title') ?? $feature->title }}"></x-form.textbox>

                            <x-form.textbox labelName="Icon" parantClass="col-12 col-md-6" name="icon"
                            required="required" placeholder="Enter Icon..!" errorName="icon" class="py-2"
                            value="{{ old('icon') ?? $feature->icon}}"></x-form.textbox>
                    </div>
                    <div class="row">
                        <x-form.textbox labelName="Description" parantClass="col-12 col-md-6" name="description"
                            required="required" placeholder="Enter Icon Description..!" errorName="description" class="py-2"
                            value="{{ old('description') ?? $feature->description}}"></x-form.textbox>

                            <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="Status" errorName="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </x-form.selectbox>

                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                            <div class="spinner-border text-light d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>Submit
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
            const projectRedirectUrl = "{{ route('admin.feature.index') }}";
            $('#featureEditForm').on('submit', function(e) {
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
