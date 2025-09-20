@extends('layouts.app')
@section('title', $title)
@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/image-gallary.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/invoice.css') }}">
    <style>
        #productborder {
            border-bottom: 1px solid #ddd;
        }
    </style>
@endpush
@section('content')
    <section>
        <div class="card">
            <div class="card-heading d-flex justify-content-between align-items-center  purchas-invoice-show">
                <h3 class="p-2">{{ __f("Return Product View Title") }}</h3>
                <div class="d-flex gap-2 mt-3 px-2 no-print">
                    @if (Auth::check() && Auth::user()->role_id == 3)
                        <a target="_blank" href="{{ route('staff.returnproduct.download.print', ['id' => $returnProduct->id]) }}"
                            class="btn btn-primary"><i class="fa-solid fa-print me-2"></i>{{ __f('Print Invoice Title') }}</a>
                        {{-- <a href="{{ route('staff.invoice.download', $invoice->id) }}" class="btn btn-success"><i
                                class="fa-solid fa-download me-2"></i>Download PDF</a> --}}
                    @else
                        <a target="_blank" href="{{ route('admin.returnproduct.download.print', ['id' => $returnProduct->id]) }}"
                            class="btn btn-primary"><i class="fa-solid fa-print me-2"></i>{{ __f('Print Invoice Title') }}</a>
                        {{-- <a href="{{ route('admin.invoice.download', $invoice->id) }}" class="btn btn-success"><i
                                class="fa-solid fa-download me-2"></i>Download PDF</a> --}}
                    @endif

                    {{-- <button type="button" class="btn btn-info text-white" data-bs-toggle="modal"
                        data-bs-target="#emailModal">
                        <i class="fa-solid fa-envelope me-2"></i> Send Email
                    </button> --}}
                </div>
            </div>

            <div class="card-body content-area">
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
                            <span>{{ __f('Invoice Title') }}: {{ '#'.$returnProduct->invoice_id }}</span><br>
                            {{ __f('Created Date Title') }}: <span class="discount">{{ formatDateByLocale(\Carbon\Carbon::parse($returnProduct->create_date)->format('d-m-Y')) }}</span><br>
                            <span id="hr-border"></span>
                            <span>{{ $returnProduct->customer->customer_name ?? '-----' }}</span><br>
                            <span>{{ $returnProduct->customer->phone ?? '-----' }}</span><br>
                            <span>{{ $returnProduct->customer->address ?? '-----' }}
                        </div>
                    </div>
                </div>
                <div class="invoice-box table-responsive">
                    <table cellpadding="0" cellspacing="0">
                        <tr class="heading">
                            <td>{{ __f('Name Title') }}</td>
                            <td>{{ __f('Unit Cost Title') }}</td>
                            <td>{{ __f('Quantity Title') }}</td>
                            <td>{{ __f('Total Price Title') }}</td>
                        </tr>
                        @php
                            $sub_total = 0;
                        @endphp
                        @foreach ($returnProduct->returnproductdetails as $item)
                            <tr id="productborder">
                                <td>{{ Str::limit($item->product->name, 18) }}</td>
                                <td>{{ convertToLocaleNumber($item->cost) }}</td>
                                <td>{{ convertToLocaleNumber($item->qty) }}</td>
                                <td>{{ convertToLocaleNumber($item->cost * $item->qty) }} {{ currency() }} </td>
                                @php
                                    $sub_total += $item->cost * $item->qty;
                                @endphp
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-12 col-md-8">
                    </div>
                    <div class="col-12 col-md-4">
                        {{-- @php
                            $grand_total = $sub_total - $invoice->discount;
                            $due_amount = $grand_total - $invoice->paid_amount;
                        @endphp --}}
                    <div class="card p-2 shadow-sm bg-light">
                        <div class="card-summary">
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>{{ __f('Refunded Amount Title') }} :</strong>
                                    <span class="sub-total"> {{ convertToLocaleNumber($sub_total) ?? 0 }} {{ currency() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>{{ __f('Total Buy Amount Title') }} :</strong>
                                    <span>{{ convertToLocaleNumber($returnProduct->salesinvoice->sub_total ?? 0) }} {{ currency() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>{{ __f('Total Due Amount Title') }} :</strong>
                                    <span class="grand-total"> {{ convertToLocaleNumber($returnProduct->due_amount ?? 0) }} {{ currency() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>{{ __f('Discount Amount Title') }}:</strong>
                                    <span class="grand-total"> {{ convertToLocaleNumber($returnProduct->discount ?? 0) }} {{ currency() }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>{{ __f('Grand Refunded Amount Title') }} :</strong>
                                    @php
                                        $grandrefund =  $sub_total - $returnProduct->due_amount;
                                    @endphp
                                    @if($grandrefund > 0)
                                        <span> {{ convertToLocaleNumber($grandrefund ?? 0) }} {{ currency() }}</span>
                                    @else
                                        <span> {{ convertToLocaleNumber(0) }} {{ currency() }}</span>
                                    @endif
                                </li>
                                <div class="list-group-item d-flex justify-content-between">
                                    <strong>{{ __f('Grand Paid Amount Title') }} :</strong>
                                    @if($grandrefund < 0)
                                        <span> {{ convertToLocaleNumber($grandrefund ?? 0) }} {{ currency() }}</span>
                                    @else
                                        <span> {{ convertToLocaleNumber(0) }} {{ currency() }}</span>
                                    @endif
                                </div>
                            </ul>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
