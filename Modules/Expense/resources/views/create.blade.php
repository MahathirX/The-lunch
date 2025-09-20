@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-gallary.css') }}">
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">{{ __f("Expense Create Title") }}</h3>
            </div>
            <div class="card-body">
                <form id="expenseForm" action="{{ route('admin.expense.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-12 col-md-6" id="supplierField">
                            <x-form.textbox labelName="{{ __f('Expense Name Label Title') }}" required="required" name="name"
                                placeholder="{{ __f('Expense Name Placeholder') }}" errorName="name" class="py-2"
                                value="{{ old('name') }}">
                            </x-form.textbox>
                        </div>

                        <x-form.textbox labelName="{{ __f('Expense code Label Title') }}" parantClass="col-12 col-md-6"
                            name="code" placeholder="{{ __f('Expense code Placeholder') }}" errorName="code" class="py-2"
                            value="{{ old('code') }}">
                        </x-form.textbox>
                    </div>
                    <div class="row ">
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status"
                            required="required" labelName="{{ __f('Status Title') }}">
                            <option value="1">{{ __f("Status Publish Title") }}</option>
                            <option value="0">{{ __f("Status Pending Title") }}</option>
                        </x-form.selectbox>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mt-2">
                        <button type="submit" class="btn btn-primary">
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
        $(document).ready(function() {
            @if(Auth::check() && Auth::user()->role_id == 3)
                var projectRedirectUrl = "{{ route('staff.expense.index') }}";
            @else
                var projectRedirectUrl = "{{ route('admin.expense.index') }}";
            @endif
            $('#expenseForm').on('submit', function(e) {
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
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                alert(xhr.responseJSON.message);
                            } else {
                                alert('An unexpected error occurred.');
                            }
                        }
                    }

                });
            });
        });
    </script>
@endpush
