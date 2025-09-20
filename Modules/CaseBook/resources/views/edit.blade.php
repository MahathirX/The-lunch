@extends('layouts.app')
@section('title', $title)
@section('content')
    <section>
        <div class="card">
            <div class="card-heading">
                <h3 class="p-2">Cashbook Create Form</h3>
            </div>
            <div class="card-body">
                <form id="cashBookForm" action="{{ route('staff.cashbook.update',['cashbook' => $editData->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <x-form.textbox labelName="Payment Date" parantClass="col-12 col-md-6" name="payment_date"
                        required="required"  type="date" errorName="payment_date" class="py-2"
                        value="{{ $editData->payment_date ?? '' }}">
                        </x-form.textbox>
                        <x-form.textbox labelName="Amount" parantClass="col-12 col-md-6" name="amount"
                        required="required" placeholder="Enter Amount..!" type="number" errorName="amount" class="py-2"
                            value="{{ $editData->amount ?? old('amount') }}">
                        </x-form.textbox>
                    </div>
                    <div class="row mt-2">
                        <x-form.selectbox parantClass="col-12 col-md-6" class="form-control py-2" name="payment_type"
                            required="required" labelName="Payment Type" errorName="payment_type">
                            <option value="1" {{ $editData->payment_type == '1' ? 'selected' : '' }}>Bank</option>
                            <option value="0" {{ $editData->payment_type == '0' ? 'selected' : '' }}>Mobile Banking</option>
                        </x-form.selectbox>

                        <x-form.textarea  labelName="Note" parantClass="col-12 col-md-6" name="note"  placeholder="Enter Note!" errorName="note" class="py-2"  value="{{ $editData->note ?? old('note') }}" type="text">  </x-form.textarea>
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
            const projectRedirectUrl = "{{ route('staff.cashbook.index') }}";
            $('#cashBookForm').on('submit', function(e) {
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
