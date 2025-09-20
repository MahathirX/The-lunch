@extends('layouts.app')
@section('title',$title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <style>
        #category_image_dimensions{
            font-size: 11px;
            color: #ff6000c9;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f('Category Create Title') }}</h3>
            </div>
            <div class="card-body">
                <form id="categoryForm" action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Category Name Label Title') }}" parantClass="col-12 col-md-6" name="name"
                            required="required" placeholder="{{ __f('Category Name Placeholder Title') }}" errorName="name" class="py-2"
                            value="{{ old('name') }}"></x-form.textbox>

                            <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="{{ __f('Status Label Title') }}" errorName="status">
                            <option value="1">{{ __f('Status Publish Title') }}</option>
                            <option value="0">{{ __f('Status Pending Title') }}</option>
                        </x-form.selectbox>
                    </div>
                    <div class="row">
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="parent_id"
                            labelName="{{ __f('Parent Category Label Title') }}" errorName="parent_id" id="parent_id">
                            <option value="">{{ __f('Select Parent Category Text') }}</option>
                            @forelse ($categories as $categorie)
                                <option value="{{ $categorie->id ?? '' }}">{{ $categorie->name ?? '' }}</option>
                            @empty
                                <option value="" disabled class="text-danger">{{ __f('No Category Found Text') }}</option>
                            @endforelse
                        </x-form.selectbox>

                        {{-- <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="submenu_id"
                            labelName="{{ __f('Sub Category Label Title') }}" errorName="submenu_id" id="submenu_id">
                            <option value="">{{ __f('Select Sub Category Text') }}</option>
                        </x-form.selectbox> --}}

                         <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="home_page_show"
                            labelName="{{ __f('Home Page Show Title') }}" errorName="home_page_show" id="home_page_show" required="required">
                            <option value="0">{{ __f('No Text') }}</option>
                            <option value="1">{{ __f('Yes Text') }}</option>
                        </x-form.selectbox>
                    </div>
                    <div class="row">
                        <x-form.textbox labelName="{{ __f('Order By Label Title') }}" parantClass="col-12 col-md-6" name="order_by"
                            required="required" placeholder="{{ __f('Order By Label Title') }}" errorName="order_by" class="py-2"
                            value="{{ $totalcategory ? $totalcategory + 1 : old('order_by') }}"></x-form.textbox>

                             <x-form.textbox labelName="{{ __f('Category Tag Title') }}" parantClass="col-12 col-md-6" name="tag"
                             placeholder="{{ __f('Category Tag Placeholder') }}" errorName="tag" class="py-2"
                            value="{{ old('tag') }}"></x-form.textbox>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-dark font-weight-medium">{{ __f('Image Label Title') }} <span id="category_image_dimensions">({{ __f('Category Image Dimensions') }})</span></label>
                            <div>
                                <label class="first__picture" for="first__image" tabIndex="0">
                                    <span class="picture__first"></span>
                                </label>
                                <input type="file" name="image" id="first__image" accept="image/*">
                                <span class="text-danger error-text image-error"></span>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <button type="submit" class="btn btn-primary d-flex align-items-center">
                            <div class="spinner-border text-light d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>{{ __f("Submit Title") }}
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
            ImagePriviewInsert('first__image', 'picture__first', '{{ __f("Category Image Placeholder") }}');
        });
        $(document).ready(function() {
            @if (Auth::check() && Auth::user()->role_id == 3)
                var projectRedirectUrl = "{{ route('staff.category.index') }}";
            @else
                var projectRedirectUrl = "{{ route('admin.category.index') }}";
            @endif
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $('#categoryForm').on('submit', function(e) {
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

            $('#parent_id').on('change',function(){
                var categoryId = $(this).val();
                $.ajax({
                    url: "{{ route('admin.category.sub.menu') }}",
                    method: 'POST',
                    headers: {
                    'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        categoryId: categoryId,
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $('#submenu_id').html('');
                            $('#submenu_id').html(res.data);
                        }
                    },
                    error: function(xhr) {
                        console.log('Something went wrong. Please try again.');
                    }
                });
            })
        });
    </script>
@endpush
