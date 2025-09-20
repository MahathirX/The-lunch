@extends('layouts.app')
@section('title', $title)
@push('styles')
    <style>
        html,
        body {
            height: auto;
            overflow: visible;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .invoice-container {
            background: #fff;
            border-radius: 4px;
            margin: auto;
            -webkit-print-color-adjust: exact;
            page-break-before: avoid;
            page-break-after: auto;
        }

        .table-container th,
        .table-container td {
            border: 1px solid black !important;
            padding: 8px 12px;
            text-align: left;
        }

        .table-container td {
            word-wrap: break-word;
            white-space: normal;
        }

        .header {
            background: #033e8c;
            color: white;
            padding: 20px;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
            -webkit-print-color-adjust: exact;
        }

        .section {
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
        }

        .section p {
            margin: 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table tbody tr th:first-child,
        .info-table tbody tr td:first-child {
            text-align: left;
        }

        .info-table tbody tr th:last-child,
        .info-table tbody tr td:last-child {
            text-align: right;
        }

        .table-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-container th,
        .table-container td {
            border: 1px solid #ddd;
            padding: 6px 12px;
            text-align: left;
        }

        .table-container th {
            background-color: #033e8c;
            color: white;
        }

        .total {
            width: 100%;
            margin-top: 20px;
        }

        .total tbody tr th:first-child,
        .total tbody tr td:first-child {
            text-align: left;
            width: 70%;
            font-size: 15px;
            font-weight: 400;
            vertical-align: top;
        }

        .total tbody tr th:last-child,
        .total tbody tr td:last-child {
            text-align: right;
            vertical-align: top;
        }

        .total tbody tr th p {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .sales_invoice_note {
            padding-top: 10px;
        }

        .total-calculation {
            background: #033e8c;
        }

        .total-calculation p {
            text-align: left;
            padding: 8px;
            color: white;
            border-bottom: 1px solid white;
            font-size: 12px;
        }

        .signature {
            width: 100%;
            position: fixed;
            bottom: 20px;
        }

        .signature tbody tr th:first-child,
        .signature tbody tr td:first-child {
            text-align: left;
            width: 50%;
            font-size: 15px;
            font-weight: 400;
            vertical-align: top;
        }

        .signature tbody tr th:last-child,
        .signature tbody tr td:last-child {
            /* text-align: right; */
            vertical-align: top;
            font-weight: 400;
            padding-left: 60px;
        }

        .authoritysignature img {
            width: 80px;
            height: 40px;
        }

        .footerlast {
            text-align: center;
            font-size: 8px;
            position: fixed;
            bottom: 10px;
            width: 100%;
        }

        .navbar.navbar-expand.navbar-light.navbar-bg.px-3 {
            display: none;
        }

        .footer-copyright {
            display: none;
        }

        .main {
            background: #fff !important;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .table-container th,
            .table-container td {
                border: 1px solid black !important;
            }

            .print-visible {
                display: block !important;
            }
        }

        .customersing {
            overflow: hidden !important;
            margin-right: 30px;
        }
    </style>
@endpush

@section('content')
    <section>
        <div class="invoice-container">
            <div class="header">
                <table class="info-table">
                    <tr>
                        <th><img src="{{ asset(config('settings.company_secondary_logo')) }}"
                                style="width:100%; max-width:200px;">
                        </th>
                        <th><span>{{ __f('Invoice No Title') }} : {{ '#' . $returnProduct->invoice_id }}</span></th>
                    </tr>
                </table>
            </div>
            <div class="section">
                <table class="info-table">
                    <tr>
                        <th>{{ __f('Return To Title') }} : </th>
                        <th>{{ __f('From Title') }} : </th>
                    </tr>
                    <tr>
                        <td>{{ config('settings.company_name') ?? '' }}</td>
                        <td>{{ __f('Name Title') }} : {{ $returnProduct->customer->customer_name ?? '-----' }} </td>
                    </tr>
                    <tr>
                        <td>{{ config('settings.company_email') ?? '' }}</td>
                        <td>{{ __f('Phone Title') }} : {{ $returnProduct->customer->phone ?? '-----' }} </td>
                    </tr>
                    <tr>
                        <td>{{ config('settings.company_cell') ?? '' }}</td>
                        <td>{{ __f('Address Title') }} : {{ $returnProduct->customer->address ?? '-----' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>{{ __f('Created Date Title') }} : {{ formatDateByLocale(\Carbon\Carbon::parse($returnProduct->create_date)->format('d-m-Y')) ?? '-----' }}</td>
                    </tr>
                </table>
            </div>
            <table class="table-container">
                <tr>
                    <th>{{ __f("Name Title") }}</th>
                    <th>{{ __f("Qty Title") }}</th>
                    <th>{{ __f("Price Title") }}</th>
                    <th>{{ __f("Total Title") }}</th>
                </tr>
                @php
                    $sub_total = 0;
                @endphp
                @forelse($returnProduct->returnproductdetails as $item)
                    <tr>
                        <td>{{ Str::limit($item->product->name, 18) }}</td>
                        <td>{{ convertToLocaleNumber($item->qty) }}</td>
                        <td>{{ convertToLocaleNumber($item->cost) }} {{ currency() }}</td>
                        <td>{{ convertToLocaleNumber($item->cost * $item->qty) }} {{ currency() }}</td>
                        @php $sub_total += $item->cost * $item->qty; @endphp
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">{{ __f("No Product Found Title") }}</td>
                    </tr>
                @endforelse
            </table>
            <table class="total">
                <tr>
                    <th>
                        @if ($returnProduct->note != null)
                            <p>{{ Str::limit($returnProduct->note, 200) }}</p>
                        @endif
                        @if (config('settings.sales_invoice_note') != null)
                            <p class="sales_invoice_note">{{ Str::limit(config('settings.sales_invoice_note'), 200) }}</p>
                        @endif
                    </th>
                    <th>
                        <div class="total-calculation">
                            <p class="d-flex justify-content-between"><span>{{ __f('Refunded Amount Title') }} :</span> <span>{{ convertToLocaleNumber($sub_total) }} {{ currency() }}</span> </p>
                            <p class="d-flex justify-content-between"><span>{{ __f('Total Buy Amount Title') }} :</span> <span> {{ convertToLocaleNumber($returnProduct->salesinvoice->sub_total ?? 0) }}
                                    {{ currency() }}</span></p>
                            <p class="d-flex justify-content-between"><span>{{ __f('Total Due Amount Title') }} :</span> <span> {{ convertToLocaleNumber($returnProduct->due_amount ?? 0) }}
                                    {{ currency() }}</span></p>
                            <p class="d-flex justify-content-between"><span>{{ __f('Discount Amount Title') }} : </span> <span> {{ convertToLocaleNumber($returnProduct->discount ?? 0) }}
                                    {{ currency() }}</span></p>
                            <p class="d-flex justify-content-between"><span>{{ __f('Grand Refunded Amount Title') }} :</span> <span>
                                    @php
                                        $grandrefund = $sub_total - $returnProduct->due_amount;
                                    @endphp
                                    @if ($grandrefund > 0)
                                        {{ convertToLocaleNumber($grandrefund ?? 0) }} {{ currency() }}
                                    @else
                                        {{ convertToLocaleNumber(0) }} {{ currency() }}
                                    @endif
                                </span>
                            </p>
                            <p class="d-flex justify-content-between"><span>{{ __f('Grand Paid Amount Title') }} :</span> <span>
                                    @if ($grandrefund < 0)
                                        {{ convertToLocaleNumber($grandrefund ?? 0) }} {{ currency() }}
                                    @else
                                        {{ convertToLocaleNumber(0) }} {{ currency() }}
                                    @endif
                                </span>
                            </p>
                        </div>
                    </th>
                </tr>
            </table>
            <table class="signature">
                <tr>
                    <th>
                        @if (config('settings.authority_signature_status') == '1')
                            @if (config('settings.authority_signature_image') != null)
                                <p class="authoritysignature">{{ __f('Authority Signature Title') }}
                                    <img src="{{ asset(config('settings.authority_signature_image')) }}"
                                        style="width:100%; max-width:80px;">
                                </p>
                            @else
                                <p>{{ __f('Authority Signature Title') }} ...............</p>
                            @endif
                        @else
                            <p>{{ __f('Authority Signature Title') }} ...............</p>
                        @endif
                    </th>
                    <th class="customersing">
                        <p>{{ __f('Customer Signature Title') }} ......................</p>
                    </th>
                </tr>
            </table>
            <div class="footerlast">
                @if (config('settings.invoicefootertext') != null)
                    {{ Str::limit(config('settings.invoicefootertext'), 100) }}
                @endif
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            window.print();
        })
    </script>
@endpush
