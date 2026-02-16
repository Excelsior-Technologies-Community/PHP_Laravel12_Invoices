@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Invoice #{{ $invoice->invoice_number }}</h2>
        <div class="flex space-x-2">
            <a href="{{ route('invoices.edit', $invoice) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure?')">
                    <i class="fas fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>

    <div class="p-6">
        <!-- Status Badge -->
        <div class="mb-6">
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                @if($invoice->status == 'paid') bg-green-100 text-green-800
                @elseif($invoice->status == 'sent') bg-blue-100 text-blue-800
                @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                @else bg-gray-100 text-gray-800 @endif">
                Status: {{ ucfirst($invoice->status) }}
            </span>
        </div>

        <!-- Customer and Invoice Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Bill To:</h3>
                <p class="text-gray-600">{{ $invoice->customer_name }}</p>
                <p class="text-gray-600">{{ $invoice->customer_email }}</p>
                @if($invoice->customer_phone)
                    <p class="text-gray-600">{{ $invoice->customer_phone }}</p>
                @endif
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Invoice Details:</h3>
                <p class="text-gray-600"><span class="font-medium">Invoice Date:</span> {{ $invoice->invoice_date->format('M d, Y') }}</p>
                <p class="text-gray-600"><span class="font-medium">Due Date:</span> {{ $invoice->due_date->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Invoice Items -->
        <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h3>
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Invoice Summary -->
        <div class="flex justify-end">
            <div class="w-64">
                <div class="flex justify-between py-2">
                    <span class="font-medium text-gray-700">Subtotal:</span>
                    <span class="text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-t border-gray-200">
                    <span class="font-medium text-gray-700">Tax:</span>
                    <span class="text-gray-900">${{ number_format($invoice->tax, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-t border-gray-200 text-lg font-bold">
                    <span class="text-gray-900">Total:</span>
                    <span class="text-gray-900">${{ number_format($invoice->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="mt-8 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-medium text-gray-700 mb-2">Notes:</h4>
            <p class="text-gray-600">{{ $invoice->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection