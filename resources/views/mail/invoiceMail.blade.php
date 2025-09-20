<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        @page {
            size: A4;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .invoice-container {
            max-width: 60%;
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        .header {
            background: #033e8c;
            color: white;
            padding: 20px;
            font-size: 20px;
            font-weight: bold;
        }
        #invoice_no{
            text-align: end;
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
            text-align: start;
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
            text-align: start;
            width: 50%;
            font-size: 15px;
            font-weight: 400;
            vertical-align: top;
        }

        .total tbody tr th:last-child,
        .total tbody tr td:last-child {
            text-align: end;
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
            text-align: start;
            padding: 8px;
            color: white;
            border-bottom: 1px solid white;
            font-size: 12px;
        }

        .signature {
            width: 100%;
            position: fixed;
            bottom: 80px;
        }

        .signature tbody tr th:first-child,
        .signature tbody tr td:first-child {
            text-align: start;
            width: 20%;
            font-size: 15px;
            font-weight: 400;
            vertical-align: top;
        }
        #note{
            text-align: start;
            width: 70%;
        }
        .signature tbody tr th:last-child,
        .signature tbody tr td:last-child {
            text-align: end;
            vertical-align: top;
            font-weight: 400;
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
        #authority{
            text-align: start;
        }
        #customer{
            text-align: end;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <table class="info-table">
                <tr>
                    <th><img src="{{ asset(config('settings.company_secondary_logo')) }}" />
                    </th>
                    <th id="invoice_no"><span>INVOICE NO : {{ $invoice->invoice_id }}</span></th>
                </tr>
            </table>
        </div>
        <div class="section">
            <table class="info-table">
                <tr>
                    <td><strong>Bill To :</strong> </td>
                    <td  id="invoice_no"><strong>From : </strong></td>
                </tr>
                <tr>
                    <td>{{ config('settings.company_name') ?? '' }}</td>
                    <td id="invoice_no">Name : {{ $invoice->customer_name }} </td>
                </tr>
                <tr>
                    <td>{{ config('settings.company_email') ?? '' }}</td>
                    <td id="invoice_no">Phone : {{ $invoice->customer_phone }} </td>
                </tr>
                <tr>
                    <td>{{ config('settings.company_cell') ?? '' }}</td>
                    <td id="invoice_no">Address : {{ $invoice->customer_address }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="invoice_no">Created Date : {{ $invoice->create_date }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="invoice_no">Due Date : {{ $invoice->due_date }}</td>
                </tr>
            </table>
        </div>
        <table class="table-container">
            <tr>
                <th>Name </th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            @php
                $sub_total = 0;
            @endphp
            @forelse($invoiceDetails as $item)
                <tr>
                    <td>{{ Str::limit($item->product->name, 18) }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->cost }} {{ config('settings.currency') ?? '৳' }}</td>
                    <td>{{ $item->cost * $item->qty }} {{ config('settings.currency') ?? '৳' }}</td>
                    @php $sub_total += $item->cost * $item->qty; @endphp
                </tr>
            @empty
                <tr>
                    <td colspan="4">No Product Found</td>
                </tr>
            @endforelse
        </table>
        <table class="total">
            <tr>
                <th id="note">
                    @if ($invoice->note != null)
                        <p>{{ Str::limit($invoice->note, 200) }}</p>
                    @endif
                    @if (config('settings.sales_invoice_note') != null)
                        <p class="sales_invoice_note">{{ Str::limit(config('settings.sales_invoice_note'), 200) }}</p>
                    @endif
                </th>
                @php
                    $grand_total = $sub_total - $invoice->discount;
                    $due_amount = $grand_total - $invoice->paid_amount;
                @endphp
                <th>
                    <div class="total-calculation">
                        <p>Sub Total : {{ $sub_total }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Discount : {{ $invoice->discount ?? 0 }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Grand Total : {{ $grand_total }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Paid Amount : {{ $invoice->paid_amount }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Due amount : {{ $due_amount }} {{ config('settings.currency') ?? '৳' }}</p>
                    </div>
                </th>
            </tr>
        </table>
        <table class="signature">
            <tr>
                <th id="authority">
                    @if (config('settings.authority_signature_status') == '1')
                        @if (config('settings.authority_signature_image') != null)
                            <p class="authoritysignature">Authority Signature <img
                                    src="{{ asset(config('settings.authority_signature_image')) }}"
                                    ></p>
                        @else
                            <p>Authority Signature ...............</p>
                        @endif
                    @else
                        <p>Authority Signature ...............</p>
                    @endif
                </th>
                <th id="customer">
                    <p>Customer Signature ......................</p>
                </th>
            </tr>
        </table>
        <div class="footerlast">
            @if(config('settings.invoicefootertext') != null)
             {{ Str::limit(config('settings.invoicefootertext'), 100) }}
            @endif
        </div>
    </div>
</body>
</html>
