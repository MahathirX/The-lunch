@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-gallary.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/invoice.css') }}">
    <style>
        .purchasetr{
	        border-bottom: 1px solid #ddd;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading d-flex justify-content-between align-items-center purchas-invoice-show">
                <h3 class="p-2">{{ __f('Invoice Details Title') }}</h3>
                <div class="d-flex gap-2 mt-3 px-2 no-print">
                    @if(Auth::check() && Auth::user()->role_id == 3)
                    <a target="_blank" href="{{ route('staff.purchase.download.print', ['invoiceId' => $purchase->id]) }}"
                        class="btn btn-primary"><i class="fa-solid fa-print me-2"></i>{{ __f('Print Invoice Title') }}</a>
                    @else
                    <a target="_blank" href="{{ route('admin.purchase.download.print', ['invoiceId' => $purchase->id]) }}"
                        class="btn btn-primary"><i class="fa-solid fa-print me-2"></i>{{ __f('Print Invoice Title') }}</a>
                    @endif

                    {{-- <a href="{{ route('admin.purchase.invoice.download', $purchase->id) }}" class="btn btn-success"><i
                            class="fa-solid fa-download me-2"></i>Download PDF</a>
                    <a href="{{ route('admin.purchase.invoice.mail.send', $purchase->id) }}" class="btn btn-info text-white"> <i class="fa-solid fa-envelope me-2"></i> Send Email</a> --}}
                </div>
            </div>

            <div class="card-body content-area table-responsive">
                <div class="invoice-box p-4">
                    <div class="row">
                        <div id="text-center" class="col-12 col-md-6">
                            <img src="{{ asset(config('settings.company_secondary_logo')) }}"
                                style="width:100%; max-width:200px;"><br>
                            <span>{{ config('settings.company_name') ?? '' }}</span> <br>
                            <span>{{ config('settings.company_email') ?? '' }}</span>
                            <span id="hr-border"></span>
                        </div>
                        <div id="text-center" class="col-12 col-md-6 text-end">
                            <span>{{ __f('Invoice Title') }} : {{'#'. $purchase->invoice_id }}</span><br>
                            {{ __f('Invoice Date Title') }} : <span class="discount text-end">{{ formatDateByLocale(\Carbon\Carbon::parse($purchase->invoice_date)->format('d-m-Y')) }}</span><br>
                            {{ __f('Created Date Title') }} : <span class="discount text-end">{{ formatDateByLocale(\Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y')) }}</span><br>
                            <span id="hr-border"></span>
                            {{ __f('Supplier Name Title') }} :  <span class="discount text-end">{{ $purchase->supplier->name ?? '' }}</span><br>
                            {{ __f('Company Name Title') }} : <span class="discount text-end">{{ $purchase->supplier->company_name }}</span><br>
                            {{ __f('Supplier Email Title') }}  : <span class="discount text-end">{{ $purchase->supplier->email }}</span><br>
                            {{ __f('Supplier Phone Title') }}  : <span class="discount text-end">{{ $purchase->supplier->phone }}</span>
                        </div>
                    </div>
                </div>
                <div class="invoice-box">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0">
                            <tr class="heading">
                                <td>{{ __f('Name Title') }}</td>
                                <td>{{ __f('Quantity Title') }}</td>
                                <td>{{ __f('Original Buy Price Title') }}</td>
                                <td>{{ __f('Buy Price Title') }}</td>
                                <td>{{ __f('Original Sub Total Title') }}</td>
                                <td>{{ __f('Sub Total Title') }}</td>
                            </tr>
                            @php
                                $admin_sub_total = 0;
                                $sub_total = 0;
                            @endphp
                            @foreach ($purchase->purchaseinvoicedetails as $invoice)
                                <tr class="purchasetr">
                                    <td>{{ Str::limit($invoice->product->name ?? '' ,30) }}</td>
                                    <td>{{ convertToLocaleNumber($invoice->qty ?? 0) }}</td>
                                    <td>{{ convertToLocaleNumber($invoice->admin_buy_price ?? 0 )}} {{ currency() }}</td>
                                    <td>{{ convertToLocaleNumber($invoice->buy_price ?? 0) }} {{ currency() }}</td>
                                    <td>{{ convertToLocaleNumber($invoice->admin_buy_price * $invoice->qty) }} {{ currency() }}</td>
                                    <td>{{ convertToLocaleNumber($invoice->buy_price * $invoice->qty) }} {{ currency() }}</td>
                                    @php
                                        $admin_sub_total += $invoice->admin_buy_price * $invoice->qty;
                                        $sub_total += $invoice->buy_price * $invoice->qty;
                                    @endphp
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-8">

                        </div>
                        <div class="col-12 col-md-4 mt-3">
                            @php
                            $admin_grand_total = $admin_sub_total - (int) $purchase->discount;
                            $grand_total = $sub_total - (int) $purchase->discount;
                        @endphp
                        <div class="card p-2 shadow-sm bg-light">
                            <div class="card-summary">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>{{ __f('Original Sub Total Title') }} : </strong>
                                        <span class="sub-total">
                                            {{ convertToLocaleNumber($admin_grand_total) }} {{ currency() }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>{{ __f('Sub Total Title') }} : </strong>
                                        <span class="sub-total">
                                            {{ convertToLocaleNumber($grand_total) }} {{ currency() }}</span>
                                    </li>
                                     <li class="list-group-item d-flex justify-content-between">
                                        <strong>{{ __f('Discount Title') }} : </strong>
                                        <span>
                                            {{ convertToLocaleNumber($purchase->discount) }} {{ currency() }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>{{ __f('Original Grand Total Title') }} : </strong>
                                        <span class="grand-total">
                                            {{ convertToLocaleNumber($admin_grand_total) }} {{ currency() }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>{{ __f('Grand Total Title') }} : </strong>
                                        <span class="grand-total">
                                            {{ convertToLocaleNumber($grand_total) }} {{ currency() }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>{{ __f('Paid amount Title') }} : </strong>
                                        <span>
                                            {{ convertToLocaleNumber($purchase->paid_amount) }} {{ currency() }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <strong>{{ __f('Due amount Title') }} : </strong>
                                        <span>
                                            {{ convertToLocaleNumber($purchase->due_amount) ?? 0 }} {{ currency() }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- @include('components.models.sendinvoicemain') --}}
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#sendInvoiceMail').on('submit', function(e) {
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
                            $('#emailModal').modal('hide');
                            $('.spinner-border').addClass('d-none');
                            $('#sendInvoiceMail').trigger('reset');
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
