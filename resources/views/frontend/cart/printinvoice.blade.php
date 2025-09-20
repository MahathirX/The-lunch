<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __f('Invoice Print Page Invoice Title') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family:  sans-serif;
            background: #f5f5f5;
            padding: 20px;
            color: #333;
        }

        #invoice-container .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        #invoice-container .invoice-header {
            padding: 30px;
            position: relative;
        }

        #invoice-container .invoice-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        #invoice-container .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            position: relative;
            z-index: 1;
        }

        #invoice-container .company-info h1 {
            font-size: 28px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        #invoice-container .company-info p {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 3px;
        }

        #invoice-container .invoice-details {
            text-align: right;
        }

        #invoice-container .invoice-details h2 {
            font-size: 36px;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        #invoice-container .invoice-details p {
            font-size: 14px;
            opacity: 0.9;
        }

        #invoice-container .invoice-body {
            padding: 30px;
        }

        #invoice-container .billing-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        #invoice-container .billing-section h3 {
            font-size: 16px;
            margin-bottom: 15px;
            border-bottom: 2px solid black;
            padding-bottom: 5px;
        }

        #invoice-container .billing-section p {
            margin-bottom: 5px;
            font-size: 14px;
            line-height: 1.5;
        }

        #invoice-container .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }


        #invoice-container .items-table th {
            padding: 15px;
            text-align: left;
            font-weight: bold;
            font-size: 14px;
        }

        #invoice-container .items-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        #invoice-container .items-table tbody tr:hover {
            background: #f8f9ff;
            transition: all 0.3s ease;
        }

        #invoice-container .items-table .text-right {
            text-align: right;
        }

        #invoice-container .total-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        #invoice-container .total-section-two {
            display: flex;
            justify-content: flex-end;
        }

        #invoice-container .total-box {
            padding: 20px;
            min-width: 300px;
        }

        #invoice-container .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        #invoice-container .total-row.final {
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 15px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        #invoice-container .total-final-two {
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 15px;
            margin-top: 15px;
            font-size: 18px;
            font-weight: bold;
            width: 100%;
        }

        #invoice-container .invoice-footer {
            padding: 30px;
            text-align: center;
        }

        #invoice-container .footer-note {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        #invoice-container .text-capitalize {
            text-transform: capitalize;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            #invoice-container .invoice-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <section id="invoice-container">
        <div class="invoice-container">
            <header class="invoice-header">
                <div class="header-content">
                    <div class="company-info">
                        <h1>{{ config('settings.company_name') ?? 'Mamurjor Store' }}</h1>
                        <p>{{ config('settings.company_location') ?? '' }}</p>
                        <p> {{ __f('Invoice Print Page Phone Title') }} : {{ config('settings.company_cell') ?? '01746686868' }}</p>
                        <p> {{ __f('Invoice Print Page Email Title') }} : {{ config('settings.company_email') ?? 'mamurjorbd@gmail.com' }}</p>
                        <p> {{ __f('Invoice Print Page Web Title') }} : {{ route('index') ?? '' }}</p>
                    </div>
                    @php
                        $startDate = now()->addDays(3)->format('F d,');
                        $endDate = now()->addDays(5)->format('d - Y');
                        $estimatedDelivery = $startDate . ' ' . $endDate;
                    @endphp
                    <div class="invoice-details">
                        <h2> {{ __f('Invoice Print Page Invoice Title') }}</h2>
                        <p> {{ __f('Invoice Print Page Invoice No Title') }} : {{ $order->invoice_id }}</p>
                        <p> {{ __f('Invoice Print Page Date Title') }} : {{  $order->created_at->format('F d, Y \a\t g:i A') }}</p>
                        <p> {{ __f('Invoice Print Page Estimated Delivery Title') }}  : {{ $estimatedDelivery ?? '' }}</p>
                    </div>
                </div>
            </header>
            <main class="invoice-body">
                <div class="billing-info">
                    <div class="billing-section">
                        <h3> {{ __f('Invoice Print Page Customer Info Title') }} </h3>
                        <p><strong> {{ __f('Invoice Print Page Customer Name Title') }} </strong> : {{ $order?->user?->fname ?? '' }} {{ $order?->user?->lname ?? '' }}</p>
                        <p> {{ __f('Invoice Print Page Customer Address Title') }} : {{ $order?->user?->house_number ?? $order?->adress }}, {{ $order?->user?->apartment ?? '' }}</p>
                        {{-- <p>{{ $order?->user?->state ?? '' }}, {{ $order?->user?->country ?? '' }}</p> --}}
                        <p> {{ __f('Invoice Print Page Phone Title') }} : {{ $order?->user?->phone ?? '' }}</p>
                        <p> {{ __f('Invoice Print Page Email Title') }} : {{ $order?->user?->email ?? '' }}</p>
                    </div>

                    <div class="billing-section">
                        <h3> {{ __f('Invoice Print Page Payment Info Title') }} </h3>
                        <p><strong> {{ __f('Invoice Print Page Payment Type Title') }} :</strong>  {{ __f('Invoice Print Page Payment COD Title') }}</p>
                    </div>
                </div>

                <table class="items-table">
                    <thead>
                        <tr>
                            <th> {{ __f('Invoice Print Page Product Name Title') }}</th>
                            <th class="text-right"> {{ __f('Invoice Print Page Product Quntity Title') }}</th>
                            <th class="text-right"> {{ __f('Invoice Print Page Product Price Title') }} </th>
                            <th class="text-right"> {{ __f('Invoice Print Page Product Total Title') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                        @endphp
                        @if($order->orderdetails && count($order->orderdetails) > 0)
                            @forelse ($order->orderdetails as $details)
                                @php
                                    $subtotal += $details->grandtotal;
                                @endphp
                                <tr>
                                    <td>{{ Str::limit($details?->product?->name, 25, '...') }}</td>
                                    <td class="text-right">{{ convertToLocaleNumber($details->quantity ?? 0) }}</td>
                                    <td class="text-right">{{ currency() }} {{ convertToLocaleNumber($details->amount ?? 0) }}</td>
                                    <td class="text-right">{{ currency() }} {{ convertToLocaleNumber($details->grandtotal ?? 0) }}</td>
                                </tr>
                            @empty
                                <p class="text-danger text-center"> {{ __f('Invoice Print Page No Product Found Title') }} </p>
                            @endforelse
                        @endif
                    </tbody>
                </table>
                <div class="total-section">
                    <div class="total-box">
                        <div class="total-row">
                            <span> {{ __f('Invoice Print Page Subtotal Title') }} :</span>
                            <span>{{ currency() }} {{  convertToLocaleNumber($subtotal ?? 0) }}</span>
                        </div>
                        <div class="total-row">
                            <span> {{ __f('Invoice Print Page Charge Title') }} :</span>
                            <span>{{ currency() }} {{  convertToLocaleNumber($order->charge ?? 0) }}</span>
                        </div>
                        <div class="total-row">
                            <span> {{ __f('Invoice Print Page Discount Title') }} :</span>
                            <span>{{ currency() }} {{  convertToLocaleNumber($order->discount ?? 0) }}</span>
                        </div>
                    </div>
                </div>
                <div class="total-section-two">
                    <div class="total-row final total-final-two">
                        <div>
                            {{ __f('Invoice Print Page In Word Title') }} : <span class="text-capitalize">{{ numberToWords($order->amount + $order->charge) }}</span> {{ __f('Invoice Print Page Only Title') }}
                        </div>
                        <div>
                            <span> {{ __f('Invoice Print Page Total Title') }}:</span>
                            <span>{{ currency() }} {{  convertToLocaleNumber($order->amount + $order->charge) }}</span>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="invoice-footer">
                <div class="footer-note">
                    <p><strong> {{ __f('Invoice Print Page Footer Title') }}</strong></p>
                    <p> {{ __f('Invoice Print Page Footer Description Title') }} </p>
                </div>
            </footer>
        </div>
    </section>

     <script>
    window.onload = function () {
        window.print();
        setTimeout(function () {
            window.close();
        }, 1000);
    };
</script>
</body>
</html>
