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
                <h3 class="p-2">{{  __f("Expense List Create Title") }}</h3>
            </div>
            <div class="card-body">
                <form id="expenseForm" action="{{ route('admin.expenselist.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-12 col-md-6" id="supplierField">
                            <x-form.textbox labelName="{{ __f('Date Title') }}" type="date" required="required" name="create_date" errorName="create_date"
                                class="py-2" value="{{ date('Y-m-d') }}">
                            </x-form.textbox>
                        </div>

                        <x-form.selectbox labelName="{{ __f('Expense Category Select Label Title') }}" parantClass="col-12 col-md-6"
                            name="expense_category_id"  errorName="expense_category_id" class="py-2" required="required">
                            <option value="">{{ __f('Select Expense Category Title') }}</option>
                            @forelse ($expense_category as $expense)
                                <option value="{{ $expense->id }}">{{ $expense->name }}</option>
                            @empty
                                <option value="" class="text-danger" disabled>{{ __f('No data Found Title') }}</option>
                            @endforelse
                        </x-form.selectbox>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 col-md-6" >
                            <x-form.textbox labelName="{{ __f('Amount Title') }}" type="number" required="required" name="amount" errorName="amount"
                                class="py-2" value="{{ old('amount') }}" placeholder="{{ __f('Amount Placeholder') }}">
                            </x-form.textbox>
                        </div>
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="status" required="required"
                            labelName="{{ __f('Status Title') }}">
                            <option value="1">{{ __f("Status Publish Title") }}</option>
                            <option value="0">{{ __f("Status Pending Title") }}</option>
                        </x-form.selectbox>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6" >
                            <x-form.textarea labelName="{{ __f('Note Title') }}"  type="text" name="expense_note" errorName="Expense Note"
                                class="py-2" value="{{ old('expense_note') }}" placeholder="{{ __f('Note Placeholder') }}">
                            </x-form.textarea>
                        </div>
                    </div>



                    <div class="d-flex justify-content-end align-items-center mt-2">
                        <button type="submit" class="btn btn-primary">
                            <div class="spinner-border text-light d-none" role="status">
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
            @if(Auth::check() && Auth::user()->role_id == 3)
                var projectRedirectUrl = "{{ route('staff.expenselist.index') }}";
            @else
                var projectRedirectUrl = "{{ route('admin.expenselist.index') }}";
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
