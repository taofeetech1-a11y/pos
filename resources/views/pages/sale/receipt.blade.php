
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $sale->receipt_number }}</title>

    <style>
        /*
        |--------------------------------------------------------------------------
        | RESET
        |--------------------------------------------------------------------------
        */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            background: #f2f2f2;
            font-family: "Courier New", Courier, monospace;
            font-size: 13px;
            line-height: 1.35;
            color: #000;
        }


        /*
        |--------------------------------------------------------------------------
        | RECEIPT CONTAINER
        |--------------------------------------------------------------------------
        */

        .receipt-container {
            width: 80mm;
            max-width: 80mm;
            margin: 20px auto;
            padding: 5mm 4mm;
            background: #fff;
            color: #000;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .receipt-header {
            width: 100%;
            text-align: center;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 2px dashed #000;
        }

        .store-name {
            font-size: 21px;
            line-height: 1.2;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 5px;
            word-wrap: break-word;
        }

        .store-info {
            font-size: 11px;
            line-height: 1.35;
            font-weight: 700;
            color: #000;
            margin: 2px 0;
            word-wrap: break-word;
        }


        /*
        |--------------------------------------------------------------------------
        | RECEIPT DETAILS
        |--------------------------------------------------------------------------
        */

        .receipt-details {
            width: 100%;
            margin: 10px 0;
            font-size: 11px;
            font-weight: 600;
        }

        .receipt-details .row {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin: 4px 0;
        }

        .receipt-details .row span {
            display: table-cell;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .receipt-details .row span:first-child {
            width: 42%;
            font-weight: 900;
        }

        .receipt-details .row span:last-child {
            width: 58%;
            text-align: right;
        }

        .receipt-details .label {
            font-weight: 900;
        }


        /*
        |--------------------------------------------------------------------------
        | BADGES
        |--------------------------------------------------------------------------
        */

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border: 1px solid #000;
            border-radius: 2px;
            font-size: 10px;
            line-height: 1.2;
            font-weight: 900;
            color: #000;
            background: #fff;
        }

        .badge-success,
        .badge-warning,
        .badge-danger {
            color: #000;
            background: #fff;
        }


        /*
        |--------------------------------------------------------------------------
        | DIVIDERS
        |--------------------------------------------------------------------------
        */

        .divider {
            width: 100%;
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .divider-bold {
            width: 100%;
            border-top: 2px solid #000;
            margin: 10px 0;
        }


        /*
        |--------------------------------------------------------------------------
        | ITEMS
        |--------------------------------------------------------------------------
        */

        .items-table {
            width: 100%;
            margin: 10px 0;
        }

        .items-header {
            width: 100%;
            display: table;
            table-layout: fixed;
            padding-bottom: 5px;
            margin-bottom: 7px;
            border-bottom: 1px solid #000;
            font-size: 11px;
            font-weight: 900;
        }

        .items-header span {
            display: table-cell;
        }

        .items-header span:first-child {
            width: 65%;
            text-align: left;
        }

        .items-header span:last-child {
            width: 35%;
            text-align: right;
        }

        .item-row {
            width: 100%;
            margin: 8px 0;
            page-break-inside: avoid;
        }

        .item-name {
            width: 100%;
            font-size: 12px;
            line-height: 1.3;
            font-weight: 900;
            color: #000;
            margin-bottom: 3px;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        .item-details {
            width: 100%;
            display: table;
            table-layout: fixed;
            font-size: 11px;
            line-height: 1.3;
            font-weight: 700;
            color: #000;
        }

        .item-details span {
            display: table-cell;
            vertical-align: top;
        }

        .item-details span:first-child {
            width: 65%;
            text-align: left;
        }

        .item-details span:last-child {
            width: 35%;
            text-align: right;
            font-weight: 900;
            white-space: nowrap;
        }


        /*
        |--------------------------------------------------------------------------
        | TOTALS
        |--------------------------------------------------------------------------
        */

        .totals-section {
            width: 100%;
            margin: 10px 0;
        }

        .total-row {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin: 5px 0;
            font-size: 12px;
            line-height: 1.3;
            font-weight: 700;
        }

        .total-row span {
            display: table-cell;
            vertical-align: top;
            word-wrap: break-word;
        }

        .total-row span:first-child {
            width: 55%;
            text-align: left;
        }

        .total-row span:last-child {
            width: 45%;
            text-align: right;
            white-space: nowrap;
        }

        .total-row.grand-total {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 2px solid #000;
            font-size: 16px;
            line-height: 1.3;
            font-weight: 900;
        }


        /*
        |--------------------------------------------------------------------------
        | PAYMENT INFORMATION
        |--------------------------------------------------------------------------
        */

        .payment-info {
            width: 100%;
            margin: 10px 0;
            padding: 7px;
            border: 1px dashed #000;
            background: #fff;
        }

        .payment-row {
            width: 100%;
            display: table;
            table-layout: fixed;
            margin: 4px 0;
            font-size: 11px;
            line-height: 1.3;
            font-weight: 700;
        }

        .payment-row span {
            display: table-cell;
        }

        .payment-row span:first-child {
            width: 55%;
            text-align: left;
        }

        .payment-row span:last-child {
            width: 45%;
            text-align: right;
            white-space: nowrap;
        }


        /*
        |--------------------------------------------------------------------------
        | BARCODE
        |--------------------------------------------------------------------------
        */

        .barcode-container {
            width: 100%;
            max-width: 100%;
            margin: 10px 0;
            padding: 0;
            text-align: center;
            overflow: hidden;
        }

        .barcode-container svg {
            display: block;
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            margin: 0 auto;
        }

        .barcode-container small {
            display: block;
            width: 100%;
            margin-top: 3px;
            text-align: center;
            font-family: "Courier New", Courier, monospace;
            font-size: 10px;
            font-weight: 700;
            color: #000;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .receipt-footer {
            width: 100%;
            text-align: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 10px;
            line-height: 1.4;
            font-weight: 600;
        }

        .thank-you {
            font-size: 15px;
            line-height: 1.3;
            font-weight: 900;
            margin: 7px 0;
        }

        .receipt-footer p {
            margin: 7px 0;
            font-weight: 600;
            color: #000 !important;
        }


        /*
        |--------------------------------------------------------------------------
        | PRINT BUTTON
        |--------------------------------------------------------------------------
        */

        .no-print {
            text-align: center;
            margin-top: 20px;
        }

        .print-button {
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            background: #007bff;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }


        /*
        |--------------------------------------------------------------------------
        | PRINT STYLES
        |--------------------------------------------------------------------------
        */

        @media print {

            @page {
                size: 80mm auto;
                margin: 0;
            }

            html,
            body {
                width: 80mm;
                min-width: 80mm;
                max-width: 80mm;
                margin: 0;
                padding: 0;
                background: #fff;
            }

            body {
                font-family: "Courier New", Courier, monospace;
                font-size: 13px;
                line-height: 1.35;
                font-weight: 600;
                color: #000;
            }

            .receipt-container {
                width: 80mm;
                max-width: 80mm;
                min-width: 80mm;
                margin: 0;
                padding: 4mm;
                background: #fff;
                box-shadow: none;
                overflow: hidden;
            }

            .no-print {
                display: none !important;
            }

            /*
             * Force black text during printing.
             * Thermal printers reproduce black much better
             * than gray text.
             */

            .store-info,
            .item-details,
            .total-row,
            .payment-row,
            .receipt-footer p,
            .barcode-container small {
                color: #000 !important;
            }

            /*
             * Prevent elements from creating extra width.
             */

            .receipt-header,
            .receipt-details,
            .items-table,
            .totals-section,
            .payment-info,
            .barcode-container,
            .receipt-footer {
                width: 100%;
                max-width: 100%;
            }

            /*
             * Prevent individual items from being split
             * between printed sections.
             */

            .item-row {
                page-break-inside: avoid;
            }
        }
    </style>

    @php
        $generator = new \Picqer\Barcode\BarcodeGeneratorSVG();
    @endphp

</head>

<body>

    <div class="receipt-container">

        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <div class="receipt-header">

            <div class="store-name">
                {{ config('app.name', 'Your Store') }}
            </div>

            <div class="store-info">
                16, Owo Avenue, Ijapo Estate Akure
            </div>

            <div class="store-info">
                Tel: +234 803 061 1244
            </div>

            <div class="store-info">
                Email: info@yourstore.com
            </div>

        </div>


        {{-- =========================================================
             RECEIPT DETAILS
        ========================================================== --}}

        <div class="receipt-details">

            <div class="row">
                <span class="label">Receipt No:</span>
                <span>{{ $sale->receipt_number }}</span>
            </div>

            <div class="row">
                <span class="label">Date:</span>
                <span>{{ $sale->created_at->format('d M Y, h:i A') }}</span>
            </div>

            <div class="row">
                <span class="label">Cashier:</span>
                <span>{{ $sale->cashier->name ?? 'N/A' }}</span>
            </div>

            <div class="row">
                <span class="label">Payment:</span>
                <span>
                    {{ strtoupper($sale->payment_method) }}
                </span>
            </div>

            <div class="row">
                <span class="label">Status:</span>

                <span>
                    <span class="badge">
                        {{ strtoupper($sale->payment_status) }}
                    </span>
                </span>
            </div>

        </div>


        <div class="divider-bold"></div>


        {{-- =========================================================
             ITEMS
        ========================================================== --}}

        <div class="items-table">

            <div class="items-header">
                <span>ITEMS</span>
                <span>TOTAL</span>
            </div>


            @foreach ($sale->saleItems as $item)
                <div class="item-row">

                    <div class="item-name">
                        {{ $item->product->name ?? 'Product' }}
                    </div>

                    <div class="item-details">

                        <span>
                            {{ $item->qty }} × ₦{{ number_format($item->unit_price, 2) }}
                        </span>

                        <span>
                            ₦{{ number_format($item->total_price, 2) }}
                        </span>

                    </div>

                </div>
            @endforeach

        </div>


        <div class="divider"></div>


        {{-- =========================================================
             TOTALS
        ========================================================== --}}

        <div class="totals-section">

            <div class="total-row">
                <span>Subtotal:</span>

                <span>
                    ₦{{ number_format($sale->sub_total, 2) }}
                </span>
            </div>


            @if ($sale->discount > 0)

                <div class="total-row">

                    <span>
                        Discount

                        @if ($sale->discount_type === 'percentage')
                            ({{ $sale->discount }}%)
                        @endif:

                    </span>

                    <span>

                        @php
                            $discountAmount =
                                $sale->discount_type === 'percentage'
                                    ? ($sale->sub_total * $sale->discount) / 100
                                    : $sale->discount;
                        @endphp

                        -₦{{ number_format($discountAmount, 2) }}

                    </span>

                </div>

            @endif


            @if ($sale->shipping > 0)
                <div class="total-row">

                    <span>
                        Shipping:
                    </span>

                    <span>
                        ₦{{ number_format($sale->shipping, 2) }}
                    </span>

                </div>
            @endif


            <div class="total-row grand-total">

                <span>
                    GRAND TOTAL:
                </span>

                <span>
                    ₦{{ number_format($sale->grand_total, 2) }}
                </span>

            </div>

        </div>


        {{-- =========================================================
             PAYMENT INFORMATION
        ========================================================== --}}

        @if ($sale->payment_status === 'paid')
            <div class="payment-info">

                <div class="payment-row">

                    <span>
                        <strong>Amount Paid:</strong>
                    </span>

                    <span>
                        <strong>
                            ₦{{ number_format($sale->grand_total, 2) }}
                        </strong>
                    </span>

                </div>


                <div class="payment-row">

                    <span>
                        Change:
                    </span>

                    <span>
                        ₦0.00
                    </span>

                </div>

            </div>
        @endif


        {{-- =========================================================
             BARCODE
        ========================================================== --}}

        <div class="barcode-container">

            {!! $generator->getBarcode($sale->receipt_number, $generator::TYPE_CODE_128) !!}

            <small>
                {{ $sale->receipt_number }}
            </small>

        </div>


        <div class="divider"></div>


        {{-- =========================================================
             FOOTER
        ========================================================== --}}

        <div class="receipt-footer">

            <div class="thank-you">
                THANK YOU!
            </div>

            <p>
                Items sold cannot be returned or exchanged<br>
                without a valid receipt
            </p>

            <p>
                Total Items:
                {{ $sale->saleItems->sum('qty') }}
            </p>

            <p>
                Powered by Wealthtechservice.com POS System
            </p>

        </div>


        {{-- =========================================================
             PRINT BUTTON
        ========================================================== --}}

        <div class="no-print">

            <button onclick="window.print()" class="print-button">

                <span style="margin-right: 8px;">🖨️</span>
                Print Receipt

            </button>

        </div>

    </div>


    <script>
        /*
            |--------------------------------------------------------------------------
            | OPTIONAL AUTO PRINT
            |--------------------------------------------------------------------------
            |
            | Uncomment this if you want the receipt to automatically
            | open the browser print dialog when the page loads.
            |
            */

        window.onload = function () {
            window.print();
        };
    </script>

</body>

</html>
