@extends('layouts.app')
@section('title', $title)
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Tag Edit Form</h3>
            </div>
            <div class="card-body">
                <form id="productTagCreateForm" action="{{ route('admin.producttag.update', $editTag->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="update_id" value="{{ $editTag->id ?? '' }}">
                    <div class="row">
                        <x-form.textbox labelName="Tag Name" parantClass="col-12 col-md-6" name="name"
                            required="required" placeholder="Enter Tag Name..!" errorName="name" class="py-2"
                            value="{{ $editTag->tag_name ?? old('name') }}"></x-form.textbox>

                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="Tag Status" errorName="status">
                            <option value="1" {{ $editTag->status == '1' ? 'selected' : '' }}>Publish</option>
                            <option value="0" {{ $editTag->status == '0' ? 'selected' : '' }}>Pending</option>
                        </x-form.selectbox>
                    </div>


                    <div class="d-flex justify-content-end align-items-center mt-2">
                        <button type="submit" class="btn btn-primary">
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
            const projectRedirectUrl = "{{ route('admin.producttag.index') }}";
            $('#productTagCreateForm').on('submit', function(e) {
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
