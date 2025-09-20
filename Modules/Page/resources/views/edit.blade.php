@extends('layouts.app')
@section('title', $title)
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Page Edit Form</h3>
            </div>

            <div class="card-body">
                <form id="pageUpdateForm" action="{{ route('admin.page.updates',['id' => $editPage->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="Page Name" parantClass="col-12 col-md-6" name="page_name"
                            required="required" placeholder="Enter Page Name..!" errorName="page_name" class="py-2"
                            value="{{ $editPage->page_name ?? old('page_name') }}" type="text" step="1"
                            min="0"></x-form.textbox>

                        <x-form.textbox labelName="Page Heading" parantClass="col-12 col-md-6" name="page_heading"
                            required="required" placeholder="Enter Page Heading..!" errorName="page_heading" class="py-2"
                            value="{{ $editPage->page_heading ?? old('page_heading') }}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="Vedio Embed Code" parantClass="col-md-12" name="page_link"
                            required="required" errorName="page_link"
                            value="{{ $editPage->page_link ?? old('page_link') }}"></x-form.textarea>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="Product Overview" class="summernote" parantClass="col-md-12"
                            id="summernote" name="product_overview" required="required" errorName="product_overview"
                            value="{{ $editPage->product_overview ?? old('product_overview') }}"></x-form.textarea>
                    </div>

                    <div class="row mt-3">
                        <x-form.textbox labelName="Slider Title" parantClass="col-12 col-md-6" name="slider_title"
                            required="required" placeholder="Enter Slider Title..!" errorName="slider_title" class="py-2"
                            value="{{ $editPage->slider_title ?? old('slider_title') }}" type="text" step="1"
                            min="0"></x-form.textbox>

                        <x-form.textbox labelName="Phone Number" parantClass="col-12 col-md-6" name="phone"
                            required="required" placeholder="Enter Phone Number..!" errorName="phone" class="py-2"
                            value="{{ $editPage->phone ?? old('phone') }}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textbox labelName="Old Price" parantClass="col-12 col-md-6" name="old_price"
                            required="required" placeholder="Enter Old Price..!" errorName="old_price" class="py-2"
                            value="{{ $editPage->old_price ?? old('old_price') }}" type="text" step="1"
                            min="0"></x-form.textbox>

                        <x-form.textbox labelName="New Price" parantClass="col-12 col-md-6" name="new_price"
                            placeholder="Enter New Number..!" errorName="new_price" class="py-2"
                            value="{{ $editPage->new_price ?? old('new_price') }}"></x-form.textbox>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="Product Feature" class="summernote" parantClass="col-md-12"
                            id="summernote" name="features" required="required" errorName="features"
                            value="{{ $editPage->features ?? old('features') }}"></x-form.textarea>
                    </div>

                    <div class="row mt-3">
                        <x-form.textarea labelName="Product Extra Content" class="summernote" parantClass="col-md-12"
                            id="summernote" name="extra_content" errorName="extra_content"
                            value="{{ $editPage->extra_content ?? old('extra_content') }}"></x-form.textarea>
                    </div>
                    <div class="row mt-3">
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="Page Status" errorName="status">
                            <option value="1" {{ $editPage->status == '1' ? 'selected' : '' }}>Publish</option>
                            <option value="0" {{ $editPage->status == '0' ? 'selected' : '' }}>Pending</option>
                        </x-form.selectbox>

                        <div class="upload__box col-12 col-md-6">
                            <div class="upload__btn-box">
                                <label class="upload__btn">
                                    <p class="mb-0">Slider Images</p>
                                    <input type="file" multiple="" name="sliderimage[]" data-max_length="20"
                                        class="upload__inputfile" accept=".jpg,.jpeg,.png">
                                </label>
                            </div>
                            <!-- Dynamic Preview Section -->
                            <div class="upload__img-wrap d-flex flex-wrap gap-3">
                                @isset($editPage->image)
                                    @forelse ($editPage->image as $image)
                                        <div class="position-relative shadow-lg"
                                            style="width: 100px; height: 100px; margin-right: 10px;">
                                            <img src="{{ asset($image->image) }}" width="100px" height="100px"
                                                style="border-radius: 5px;" alt="image">
                                            <span class="removeImage" width="100px" height="100px"
                                                onclick="deleteImage({{ $image->id }})"
                                                style="border-radius: 5px; position: absolute; top: -30px; right: -8px;cursor: pointer; font-size: 30px; color: #3b7ddd;">×
                                            </span>
                                        </div>
                                    @empty
                                    @endforelse
                                @endisset
                            </div>
                            <span class="text-danger error-text sliderimage-error"></span>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="product_id" class="required">Select Product</label>
                        <select id="product_id" name="product_id" class="form-select">
                            <option value="">Select Product</option>
                            @forelse ($products as $product)
                                <option value="{{ $product->id ?? '' }}">{{ $product->name ?? '' }}</option>
                            @empty
                                <option class="text-danger" disabled>No Product Found !</option>
                            @endforelse
                        </select>
                        <span class="text-danger error-text product_id-error"></span>
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
            $('#product_id').select2({
                tags: true,
                theme: 'bootstrap'
            });
            const preselectedValue = "{{ $editPage->product_id }}";
            if (preselectedValue) {
                $('#product_id').val(preselectedValue).trigger('change');
            }
        });

        $(document).ready(function() {
            const projectRedirectUrl = "{{ route('admin.page.index') }}";
            $('#pageUpdateForm').on('submit', function(e) {
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

        function deleteImage(imageId) {
            $.ajax({
                url: "/admin/page-image-delete/"+imageId,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    if (res.status === 'success') {
                        flashMessage(res.status, res.message);
                        location.reload();
                    } else {
                        console.log("Failed to delete the image. Try again.");
                    }
                },
                error: function(err) {
                    console.log("Something went wrong!");
                }
            });

        }
    </script>
@endpush
