
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __f('Invoice Title') }}</title>
    <style>
        @page {
            size: A4;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .invoice-container {
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            bottom: 80px;
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
            text-align: right;
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
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <table class="info-table">
                <tr>
                    <th><img src="{{ public_path(config('settings.company_secondary_logo')) }}"
                            style="width:100%; max-width:200px;">
                    </th>
                    <th><span>{{ __f('Invoice No Title') }} : {{ $purchase->invoice_id }}</span></th>
                </tr>
            </table>
        </div>
        <div class="section">
            <table class="info-table">
                <tr>
                    <th>{{ __f('Bill To Title') }} : </th>
                    <th>{{ __f('From Title') }} : </th>
                </tr>
                <tr>
                    <td>{{ config('settings.company_name') ?? '' }}</td>
                    <td> {{ __f('Supplier Name Title') }} : {{ $purchase->supplier->name ?? '' }} </td>
                </tr>
                <tr>
                    <td>{{ config('settings.company_email') ?? '' }}</td>
                    <td>{{ __f('Company Name Title') }} : {{ $purchase->supplier->company_name ?? '' }} </td>
                </tr>
                <tr>
                    <td>{{ config('settings.company_cell') ?? '' }}</td>
                    <td>{{ __f('Supplier Email Title') }} : {{ $purchase->supplier->email ?? '' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{ __f('Supplier Phone Title') }} : {{ $purchase->supplier->phone ?? '' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{ __f('Invoice Date Title') }} : {{ $purchase->invoice_date }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{ __f('Created Date Title') }} : {{ $purchase->created_at }}</td>
                </tr>
            </table>
        </div>
        <table class="table-container">
            <tr>
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
            @forelse ($purchase->purchaseinvoicedetails as $invoice)
                <tr>
                    <td>{{ $invoice->product->name ?? '' }}</td>
                    <td>{{ $invoice->qty ?? 0 }}</td>
                    <td>{{ $invoice->admin_buy_price ?? 0 }} {{ currency() }}</td>
                    <td>{{ $invoice->buy_price ?? 0 }} {{ currency() }}</td>
                    <td>{{ $invoice->admin_buy_price * $invoice->qty }} {{ currency() }}</td>
                    <td>{{ $invoice->buy_price * $invoice->qty }} {{ currency() }}</td>
                    @php
                        $admin_sub_total += $invoice->admin_buy_price * $invoice->qty;
                        $sub_total += $invoice->buy_price * $invoice->qty;
                    @endphp
                </tr>
            @empty
                <tr>
                    <td colspan="4">{{ __f('No product Found Title') }}</td>
                </tr>
            @endforelse
        </table>

        <table class="total">
            <tr>
                <th>
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
                        <p>{{ __f('Original Sub Total Title') }} :   {{ $admin_grand_total }} {{ currency() }}</p>
                        <p>{{ __f('Sub Total Title') }} :  {{ $grand_total }} {{ currency() }}</p>
                        <p>{{ __f('Discount Title') }} : {{ $purchase->discount }} {{ currency() }}</p>
                        <p>{{ __f('Original Grand Total Title') }} : {{ $admin_grand_total }} {{ currency() }}</p>
                        <p>{{ __f('Grand Total Title') }} : {{ $grand_total }} {{ currency() }}</p>
                        <p>{{ __f('Paid amount Title') }} : {{ $purchase->paid_amount }} {{ currency() }}</p>
                        <p>{{ __f('Due amount Title') }} : {{ $purchase->due_amount ?? 0 }} {{ currency() }}</p>
                    </div>
                </th>
            </tr>
        </table>
        <table class="signature">
            <tr>
                <th>
                    @if (config('settings.authority_signature_status') == '1')
                        @if (config('settings.authority_signature_image') != null)
                            <p class="authoritysignature">{{ __f('Authority Signature Title') }} <img
                                    src="{{ public_path(config('settings.authority_signature_image')) }}"
                                    alt="image"></p>
                        @else
                            <p>{{ __f('Authority Signature Title') }} ...............</p>
                        @endif
                    @else
                        <p>{{ __f('Authority Signature Title') }} ...............</p>
                    @endif
                </th>
                <th>
                    <p>{{ __f('Supplier Signature Title') }} ......................</p>
                </th>
            </tr>
        </table>
        <div class="footerlast">
            @if (config('settings.invoicefootertext') != null)
                {{ Str::limit(config('settings.invoicefootertext'), 100) }}
            @endif
        </div>
    </div>
</body>

</html>
