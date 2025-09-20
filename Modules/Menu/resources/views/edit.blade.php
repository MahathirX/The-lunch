@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <style>
        #menu-image-size-guide {
            font-size: 12px;
            color: #f7560e;
        }
    </style>
@endpush
@section('content')
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card px-3 py-3">
                <div class="bg-white border-bottom-0 pb-4 d-flex justify-content-between align-items-center flex-row">
                    <h2 class="backend-title">{{ $title }}</h2>
                </div>
                <div class="menu-create-form">
                    <form id="menuUpdateForm" method="POST" action="{{ route('admin.menu.updates',['id' => $editMenu->id ]) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <x-form.textbox labelName="{{ __f('Menu Name Label Title') }}" parantClass="col-12 col-md-6" name="name"
                                required="required" placeholder="{{ __f('Menu Name Placeholder') }}" errorName="name" class="py-2"
                                value="{{  $editMenu->name ?? old('name') }}"></x-form.textbox>

                            <x-form.textbox labelName="{{ __f('Menu Title Label Title') }}" parantClass="col-12 col-md-6"
                                name="title" placeholder="{{ __f('Menu Title Placeholder') }}" errorName="title"
                                class="py-2" value="{{ $editMenu->title ?? old('title') }}"></x-form.textbox>
                        </div>
                        <div class="row mt-2">
                            <x-form.textbox labelName="{{ __f('Menu Url Label Title') }}" parantClass="col-12 col-md-6" name="url" required="required"
                                placeholder="{{ __f('Menu Url Placeholder') }}" errorName="url" class="py-2"
                                value="{{ $editMenu->url ?? old('url') }}"></x-form.textbox>

                            <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="parent_id"
                              labelName="{{ __f('Parent Menu Label Title') }}" errorName="parent_id" id="parent_id">
                                <option value="0">{{ __f('Select Parent Menu Title') }}</option>
                                @forelse ($parentMenu as $menu)
                                    <option value="{{ $menu->id }}" {{ $menu->id == $editMenu->parent_id ? 'selected' : '' }}>{{ $menu->name }}</option>
                                @empty
                                @endforelse
                            </x-form.selectbox>
                        </div>
                        <div class="row mt-2">
                            <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="target"
                                required="required" labelName="{{ __f('Target Label Title') }}" errorName="target">
                                <option value="0"  {{ $editMenu->target == '0' ? 'selected' : '' }}>{{ __f('Target Same Page Title') }}</option>
                                <option value="1"  {{ $editMenu->target == '1' ? 'selected' : '' }}>{{ __f('Target New Page Title') }}</option>
                            </x-form.selectbox>

                            <x-form.textbox labelName="{{ __f('Order By Label Title') }}" parantClass="col-12 col-md-6" name="order_by"
                                required="required" errorName="order_by" class="py-2"
                                value="{{ $editMenu->order_by ?? '1' }}"></x-form.textbox>
                        </div>
                        <div class="row mt-2">
                            <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                                required="required" labelName="{{ __f('Status Title') }}" errorName="status">
                                <option value="0" {{ $editMenu->status == '0' ? 'selected' : '' }}>{{ __f('Status Pending Title') }}</option>
                                <option value="1" {{ $editMenu->status == '1' ? 'selected' : '' }}>{{ __f('Status Publish Title') }}</option>
                            </x-form.selectbox>

                            <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="position"
                                required="required" labelName="{{ __f('Position Label Title') }}" errorName="position">
                                <option value="0" {{ $editMenu->position == '0' ? 'selected' : '' }}>{{ __f('Position Primary Menu Title') }}</option>
                                <option value="5" {{ $editMenu->position == '5' ? 'selected' : '' }}>{{ __f('Position Top Menu Title') }}</option>
                                <option value="1" {{ $editMenu->position == '1' ? 'selected' : '' }}>{{ __f('Position Footer 1 Title') }}</option>
                                <option value="2" {{ $editMenu->position == '2' ? 'selected' : '' }}>{{ __f('Position Footer 2 Title') }}</option>
                                <option value="3" {{ $editMenu->position == '3' ? 'selected' : '' }}>{{ __f('Position Footer 3 Title') }}</option>
                                <option value="4" {{ $editMenu->position == '4' ? 'selected' : '' }}>{{ __f('Position Footer 4 Title') }}</option>
                            </x-form.selectbox>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="text-dark font-weight-medium">{{ __f('Menu Icon Title') }} <span
                                        id="menu-image-size-guide">({{ __f('Menu Icon Dimensions') }})</span></label>
                                <div>
                                    <label class="first__picture" for="first__image" tabIndex="0">
                                        <span class="picture__first"></span>
                                    </label>
                                    <input type="file" name="icon" id="first__image" accept="image/*">
                                    <span class="text-danger error-text icon-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center mt-3">
                            <button type="submit" class="btn btn-primary d-flex align-items-center">
                                <div class="spinner-border text-light d-none" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>{{ __f('Submit Title') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            ImagePriviewInsert('first__image', 'picture__first', '{{ __f('Menu Icon Placeholder') }}');
        });

        var icon = "{{ $editMenu->icon ?? '' }}";
        if (icon != '') {
            var myIcon = "{{ asset($editMenu->icon ?? '') }}";
            $(function() {
                ImagePriviewUpdate('first__image', 'picture__first', '{{ __f("Menu Icon Placeholder") }}', myIcon);
            });
        }
        $(document).ready(function() {
            const projectRedirectUrl = "{{ route('admin.menu.index') }}";
            $('#menuUpdateForm').on('submit', function(e) {
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
