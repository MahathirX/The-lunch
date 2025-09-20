<!DOCTYPE html>

<html>

<head>

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
        }

        #maindivs {
            margin-bottom: 30px !important;
        }

        .maindiv {
            border: 1px solid black;
            margin: 10px;
        }

        p {
            margin-top: 2px !important;
            margin-bottom: 2px !important;
        }
    </style>

</head>

<body>
    @php
        use SimpleSoftwareIO\QrCode\Facades\QrCode;
        use Milon\Barcode\DNS1D;
        $dns1d = new DNS1D();
    @endphp
    <div id="maindivs">
        <div class="maindiv">
            <div class="orderlastdivs" style="border-bottom:1px solid black;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%; padding: 10px;">
                            <div class="orderlastdivFirst">
                                <p class="firstp">Name : {{ $customer->customer_name ?? '' }}</p>
                                <p>Phone Number : {{ $customer->phone ?? '' }}</p>
                                <p>Address : {{ $customer->address ?? '' }}</p>
                            </div>
                        </td>
                        @php
                            $qrCodes = null;
                            $barcode = null;
                            try {
                                $qrCodes = base64_encode(
                                    QrCode::format('svg')
                                        ->size(100)
                                        ->errorCorrection('H')
                                        ->generate($customer->phone),
                                );
                            } catch (\Exception $e) {
                                $qrCodes = null;
                            }

                            try {
                                $barcode = $dns1d->getBarcodePNG($customer->phone, 'C39');
                            } catch (\Exception $e) {
                                $barcode = null;
                            }
                        @endphp
                        <td style="width: 50%;padding: 10px; text-align:right;">
                            @if ($qrCodes)
                                <img style="width: 70px;" src="data:image/svg+xml;base64,{{ $qrCodes }}"
                                    alt="QR Code">
                            @else
                                <p>No QR Code available</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="orderlastdivs">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%; border-right:1px solid black;padding: 10px;">
                            <div class="orderlastdivFirst">
                                @isset($invoicedue)
                                    <p class="firstp">Previous Due : {{ (int) $previuscustomer->previous_due + (int) $invoicedue  }}  {{ currency() }}</p>
                                @else
                                    <p class="firstp">Previous Due : {{ (int) $previuscustomer->previous_due  }}  {{ currency() }}</p>
                                @endisset
                                    <p>Paid Amount  : {{ $paidamount ?? '' }}  {{ currency() }}</p>
                                @isset($invoicenowdue)
                                    <p>Due Amount : {{ (int) $customer->previous_due + (int) $invoicenowdue  }}  {{ currency() }}</p>
                                @else
                                    <p>Due Amount : {{ (int) $customer->previous_due  }}  {{ currency() }}</p>
                                @endisset
                                <p>Date : {{ $paiddate ?? '' }}</p>
                            </div>
                        </td>
                        <td style="width: 50%;padding: 10px;">
                            @if ($barcode)
                                <p class="firstp"><img style="width: 300px;"
                                        src="data:image/png;base64,{{ $barcode }}" alt="Barcode"></p>
                            @else
                                <p>No Barcode available</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
