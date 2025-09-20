@extends('layouts.app')
@section('title', $title)
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Attribute Create Form</h3>
            </div>
            <div class="card-body">
                <form id="projectForm" action="{{ route('admin.attribute.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="Attribute Name" parantClass="col-12 col-md-6" name="name"
                            required="required" placeholder="Enter  Attribute Name..!" errorName="name" class="py-2"
                            value="{{ old('name') }}"></x-form.textbox>

                        <div class="col-12 col-md-6">
                            <label for="attributeOption" class="form-label mb-0 required"> Attribute Option <span
                                    class="optinaltext">(Enter tag and press Enter)</span></label>
                            <input type="text" class="form-control" id="tag-input1" name="attribute_option[]"
                                placeholder="Enter tag and press Enter">
                            <span class="text-danger error-text attribute_option.0-error"></span>
                        </div>
                    </div>
                    <div class="row">
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="Status" errorName="status">
                            <option value="1">Publish</option>
                            <option value="0">Pending</option>
                        </x-form.selectbox>
                    </div>
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Submit
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('backend/assets/js/tag.js') }}"></script>
    <script>
        $(document).ready(function() {
            const projectRedirectUrl = "{{ route('admin.attribute.index') }}";
            $('#projectForm').on('submit', function(e) {
                e.preventDefault();
                $('.spinner-border').removeClass('d-none');
                $('.text-danger').text('');

                let formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        if (res.status === 'success') {
                            flashMessage('success', res.message);
                            setTimeout(() => {
                                window.location.href = projectRedirectUrl;
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        $('.spinner-border').addClass('d-none');
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                if (key.startsWith('attribute_option')) {
                                    let index = key.split('.')[1];
                                    $('.attribute_option\\.' + index + '-error').text(
                                        value[0]);
                                } else {
                                    $('.' + key + '-error').text(value[0]);
                                }
                            });
                        } else {
                            console.log('Something went wrong. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
