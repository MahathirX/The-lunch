@extends('layouts.app')
@section('title', 'Edit Attribute')

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
@endpush

@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Edit Attribute Form</h3>
            </div>
            <div class="card-body">
                <form id="attributeEditForm" action="{{ route('admin.attribute.update', $attribute->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <x-form.textbox labelName="Attribute Name" parantClass="col-12 col-md-6" name="name"
                            required="required" placeholder="Enter  Attribute Name..!" errorName="name" class="py-2"
                            value="{!! $attribute->name ?? old('name') !!}"></x-form.textbox>

                        <div class="mb-3 col-12 col-md-6">
                            <label for="attributeOption" class="form-label mb-0 required">Attribute Option</label>
                            <input type="text" class="form-control" id="tag-input1" name="attribute_option[]"
                                value="{{ $attribute->options->pluck('attribute_option')->implode(',') }}"
                                placeholder="Enter Attribute Option">
                            <span class="text-danger error-text attribute_option.0-error"></span>
                        </div>
                    </div>

                    <div class="row">
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="Status" errorName="status">
                            <option value="1" {{ $attribute->status == '1' ? 'selected' : '' }}>Publish</option>
                            <option value="0" {{ $attribute->status == '0' ? 'selected' : '' }}>Pending</option>
                        </x-form.selectbox>
                    </div>

                    <div class="d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            Update
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
        tagInput1.addData(@json($attribute->options->pluck('attribute_option')));
    </script>

    <script>
        $(document).on('submit', '#attributeEditForm', function(e) {
            e.preventDefault();
            const projectRedirectUrl = "{{ route('admin.attribute.index') }}";
            const formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status === 'success') {
                        flashMessage('success', res.message);
                        window.location.href = projectRedirectUrl;
                    }
                },
                error: function(xhr) {
                    $('.spinner-border').addClass('d-none');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            if (key.startsWith('attribute_option')) {
                                let index = key.split('.')[1];
                                $('.attribute_option\\.' + index + '-error').text(value[0]);
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
    </script>
@endpush
