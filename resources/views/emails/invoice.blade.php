<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <title>Invoice {{ $invoice->invoice_number }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 30px;">

    <div style="
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 10px;
    ">

        <h2 style="color: #2563eb;">
            Invoice {{ $invoice->invoice_number }}
        </h2>

        <p>
            Hello {{ $invoice->customer_name }},
        </p>

        <p>
            Please find your invoice attached to this email.
        </p>

        <div style="
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        ">

            <p>
                <strong>Invoice Number:</strong>
                {{ $invoice->invoice_number }}
            </p>

            <p>
                <strong>Invoice Date:</strong>
                {{ $invoice->invoice_date->format('M d, Y') }}
            </p>

            <p>
                <strong>Due Date:</strong>
                {{ $invoice->due_date->format('M d, Y') }}
            </p>

            <p>
                <strong>Total:</strong>
                ${{ number_format($invoice->total, 2) }}
            </p>

        </div>

        <p>
            You can also view your invoice online:
        </p>

        <p>
            <a
                href="{{ route('invoices.public', $invoice->public_token) }}"
                style="
                    display: inline-block;
                    background: #2563eb;
                    color: white;
                    padding: 12px 20px;
                    text-decoration: none;
                    border-radius: 6px;
                ">
                View Invoice Online
            </a>
        </p>

        <p style="margin-top: 30px;">
            Thank you for your business.
        </p>

        <p style="color: #777;">
            Invoice System
        </p>

    </div>

</body>

</html>