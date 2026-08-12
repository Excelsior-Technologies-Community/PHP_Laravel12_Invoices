<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Invoice {{ $invoice->invoice_number }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body class="bg-gray-100 min-h-screen py-8">

    <div class="max-w-5xl mx-auto px-4">

        <div class="bg-white rounded-lg shadow overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-200">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>

                        <h1 class="text-2xl font-bold text-gray-800">
                            INVOICE
                        </h1>

                        <p class="text-gray-500">
                            {{ $invoice->invoice_number }}
                        </p>

                    </div>

                    <div>

                        @if($invoice->status == 'paid')

                            <span class="px-3 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Paid
                            </span>

                        @elseif($invoice->status == 'sent')

                            <span class="px-3 py-2 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                <i class="fas fa-paper-plane mr-1"></i>
                                Sent
                            </span>

                        @elseif($invoice->status == 'overdue')

                            <span class="px-3 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Overdue
                            </span>

                        @else

                            <span class="px-3 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                <i class="fas fa-file mr-1"></i>
                                Draft
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            <div class="p-6">

                {{-- Customer / Invoice Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                    <div>

                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">
                            Bill To
                        </h3>

                        <p class="font-semibold text-gray-900">
                            {{ $invoice->customer_name }}
                        </p>

                        <p class="text-gray-600">
                            {{ $invoice->customer_email }}
                        </p>

                        @if($invoice->customer_phone)

                            <p class="text-gray-600">
                                {{ $invoice->customer_phone }}
                            </p>

                        @endif

                    </div>


                    <div>

                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">
                            Invoice Details
                        </h3>

                        <p class="text-gray-600">
                            <strong>Invoice Date:</strong>
                            {{ $invoice->invoice_date->format('M d, Y') }}
                        </p>

                        <p class="text-gray-600">
                            <strong>Due Date:</strong>
                            {{ $invoice->due_date->format('M d, Y') }}
                        </p>

                    </div>

                </div>


                {{-- Items --}}
                <div class="mb-8">

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Invoice Items
                    </h3>

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        Description
                                    </th>

                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        Quantity
                                    </th>

                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        Unit Price
                                    </th>

                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        Total
                                    </th>

                                </tr>

                            </thead>

                            <tbody class="divide-y divide-gray-200">

                                @foreach($invoice->items as $item)

                                    <tr>

                                        <td class="px-4 py-4 text-sm text-gray-900">
                                            {{ $item->description }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600 text-right">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-gray-600 text-right">
                                            ${{ number_format($item->unit_price, 2) }}
                                        </td>

                                        <td class="px-4 py-4 text-sm font-semibold text-gray-900 text-right">
                                            ${{ number_format($item->total, 2) }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- Summary --}}
                <div class="flex justify-end">

                    <div class="w-full md:w-80">

                        <div class="flex justify-between py-2">

                            <span class="text-gray-600">
                                Subtotal
                            </span>

                            <span class="font-medium">
                                ${{ number_format($invoice->subtotal, 2) }}
                            </span>

                        </div>


                        <div class="flex justify-between py-2 border-t">

                            <span class="text-gray-600">
                                Tax ({{ number_format($invoice->tax, 2) }}%)
                            </span>

                            <span class="font-medium">
                                ${{ number_format($invoice->tax_amount, 2) }}
                            </span>

                        </div>


                        <div class="flex justify-between py-3 border-t text-lg font-bold">

                            <span>
                                Total
                            </span>

                            <span>
                                ${{ number_format($invoice->total, 2) }}
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Notes --}}
                @if($invoice->notes)

                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">

                        <h4 class="font-semibold text-gray-700 mb-2">
                            Notes
                        </h4>

                        <p class="text-gray-600">
                            {{ $invoice->notes }}
                        </p>

                    </div>

                @endif

            </div>


            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t text-center text-sm text-gray-500">

                Thank you for your business.

            </div>

        </div>

    </div>

</body>

</html>