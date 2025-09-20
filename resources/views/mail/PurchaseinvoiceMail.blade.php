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
                    <th id="invoice_no"><span>INVOICE NO : {{ $purchase->invoice_id }}</span></th>
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
                    <td id="invoice_no">Supplier Name : {{ $purchase->supplier->name ?? '' }} </td>
                </tr>
                <tr>
                    <td>{{ config('settings.company_email') ?? '' }}</td>
                    <td id="invoice_no">Company Name : {{ $purchase->supplier->company_name ?? '' }}  </td>
                </tr>
                <tr>
                    <td>{{ config('settings.company_cell') ?? '' }}</td>
                    <td id="invoice_no">Supplier Email : {{ $purchase->supplier->email ?? '' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="invoice_no">Supplier Phone : {{ $purchase->supplier->phone ?? '' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="invoice_no">Invoice Date : {{ $purchase->invoice_date }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td id="invoice_no">Created Date : {{ $purchase->created_at }}</td>
                </tr>
            </table>
        </div>
        <table class="table-container">
            <tr>
                <th>Name </th>
                <th>Qty</th>
                <th>Original Buy Price</th>
                <th>Buy Price</th>
                <th>Original Sub Total</th>
                <th>Sub Total</th>
            </tr>
            @php
                 $admin_sub_total = 0;
                 $sub_total = 0;
            @endphp
             @forelse ($purchase->purchaseinvoicedetails as $invoice)
             <tr>
                 <td>{{ $invoice->product->name ?? '' }}</td>
                 <td>{{ $invoice->qty ?? 0 }}</td>
                 <td>{{ $invoice->admin_buy_price ?? 0 }} {{ config('settings.currency') ?? '৳' }}</td>
                 <td>{{ $invoice->buy_price ?? 0 }} {{ config('settings.currency') ?? '৳' }}</td>
                 <td>{{ $invoice->admin_buy_price * $invoice->qty }} {{ config('settings.currency') ?? '৳' }}</td>
                 <td>{{ $invoice->buy_price * $invoice->qty }} {{ config('settings.currency') ?? '৳' }}</td>
                 @php
                     $admin_sub_total += $invoice->admin_buy_price * $invoice->qty;
                     $sub_total += $invoice->buy_price * $invoice->qty;
                 @endphp
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
                    @if ($purchase->note != null)
                        <p>{{ Str::limit($purchase->note, 200) }}</p>
                    @endif
                    @if (config('settings.purchase_invoice_note') != null)
                        <p class="sales_invoice_note">{{ Str::limit(config('settings.purchase_invoice_note'), 200) }}</p>
                    @endif
                </th>
                @php
                    $admin_grand_total = $admin_sub_total - (int) $purchase->discount;
                    $grand_total = $sub_total - (int) $purchase->discount;
                @endphp
                <th>
                    <div class="total-calculation">
                        <p>Original Sub Total :   {{ $admin_grand_total }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Sub Total :  {{ $grand_total }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Discount : {{ $purchase->discount }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Original Grand Total : {{ $admin_grand_total }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Grand Total : {{ $grand_total }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Paid Amount : {{ $purchase->paid_amount }} {{ config('settings.currency') ?? '৳' }}</p>
                        <p>Due amount : {{ $purchase->due_amount ?? 0 }} {{ config('settings.currency') ?? '৳' }}</p>
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
                    <p>Supplier Signature ......................</p>
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
