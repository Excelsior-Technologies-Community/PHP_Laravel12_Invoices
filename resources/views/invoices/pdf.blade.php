<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $invoice->invoice_number }}
    </title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 30px;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
        }

        .header-left {
            float: left;
            width: 60%;
        }

        .header-right {
            float: right;
            width: 40%;
            text-align: right;
        }

        .clearfix {
            clear: both;
        }

        h1 {
            margin: 0;
            font-size: 28px;
            color: #2563eb;
        }

        h2 {
            margin: 5px 0;
            font-size: 18px;
        }

        .invoice-number {
            font-size: 14px;
            color: #555;
        }

        .section {
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #111827;
        }

        .customer-box {
            border: 1px solid #ddd;
            padding: 15px;
        }

        .customer-table {
            width: 100%;
        }

        .customer-table td {
            width: 50%;
            vertical-align: top;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.items th {
            background: #f3f4f6;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table.items td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }

        .summary-row {
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-label {
            float: left;
        }

        .summary-value {
            float: right;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #111827;
            padding-top: 10px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-sent {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-draft {
            background: #f3f4f6;
            color: #374151;
        }

        .notes {
            margin-top: 30px;
            padding: 15px;
            background: #f9fafb;
            border: 1px solid #eee;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 10px;
        }
    </style>

</head>

<body>

    <div class="container">

        {{-- Header --}}
        <div class="header">

            <div class="header-left">

                <h1>INVOICE</h1>

                <div class="invoice-number">
                    {{ $invoice->invoice_number }}
                </div>

            </div>


            <div class="header-right">

                <strong>Invoice Date:</strong>
                {{ $invoice->invoice_date->format('M d, Y') }}

                <br>

                <strong>Due Date:</strong>
                {{ $invoice->due_date->format('M d, Y') }}

                <br><br>

                <span class="status
                @if($invoice->status == 'paid')
                    status-paid
                @elseif($invoice->status == 'sent')
                    status-sent
                @elseif($invoice->status == 'overdue')
                    status-overdue
                @else
                    status-draft
                @endif
            ">
                    {{ ucfirst($invoice->status) }}
                </span>

            </div>

            <div class="clearfix"></div>

        </div>


        {{-- Customer Information --}}
        <div class="section">

            <div class="section-title">
                BILL TO
            </div>

            <div class="customer-box">

                <table class="customer-table">

                    <tr>

                        <td>

                            <strong>
                                {{ $invoice->customer_name }}
                            </strong>

                            <br>

                            {{ $invoice->customer_email }}

                            @if($invoice->customer_phone)

                            <br>

                            {{ $invoice->customer_phone }}

                            @endif

                        </td>


                        <td>

                            <strong>
                                Invoice Number:
                            </strong>

                            {{ $invoice->invoice_number }}

                            <br>

                            <strong>
                                Invoice Date:
                            </strong>

                            {{ $invoice->invoice_date->format('M d, Y') }}

                            <br>

                            <strong>
                                Due Date:
                            </strong>

                            {{ $invoice->due_date->format('M d, Y') }}

                        </td>

                    </tr>

                </table>

            </div>

        </div>


        {{-- Items --}}
        <div class="section">

            <div class="section-title">
                INVOICE ITEMS
            </div>

            <table class="items">

                <thead>

                    <tr>

                        <th>
                            Description
                        </th>

                        <th>
                            Quantity
                        </th>

                        <th>
                            Unit Price
                        </th>

                        <th>
                            Total
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($invoice->items as $item)

                    <tr>

                        <td>
                            {{ $item->description }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td>
                            ${{ number_format($item->unit_price, 2) }}
                        </td>

                        <td>
                            ${{ number_format($item->total, 2) }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- Summary --}}
        <div class="summary">

            <div class="summary-row">

                <span class="summary-label">
                    Subtotal:
                </span>

                <span class="summary-value">
                    ${{ number_format($invoice->subtotal, 2) }}
                </span>

                <div class="clearfix"></div>

            </div>


            <div class="summary-row">

                <span class="summary-label">
                    Tax:
                </span>

                <span class="summary-value">
                    ${{ number_format($invoice->tax, 2) }}
                </span>

                <div class="clearfix"></div>

            </div>


            <div class="summary-row total">

                <span class="summary-label">
                    Total:
                </span>

                <span class="summary-value">
                    ${{ number_format($invoice->total, 2) }}
                </span>

                <div class="clearfix"></div>

            </div>

        </div>


        {{-- Notes --}}
        @if($invoice->notes)

        <div class="notes">

            <strong>
                Notes:
            </strong>

            <br><br>

            {{ $invoice->notes }}

        </div>

        @endif


        {{-- Footer --}}
        <div class="footer">

            Thank you for your business.

            <br>

            Generated by Invoice System

        </div>

    </div>

</body>

</html>