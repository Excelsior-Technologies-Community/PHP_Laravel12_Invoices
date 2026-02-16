@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Edit Invoice #{{ $invoice->invoice_number }}</h2>
    </div>

    <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
        @csrf
        @method('PUT')
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Information -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                    <input type="text" name="customer_name" required value="{{ old('customer_name', $invoice->customer_name) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Email *</label>
                    <input type="email" name="customer_email" required value="{{ old('customer_email', $invoice->customer_email) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Phone</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone', $invoice->customer_phone) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="draft" {{ $invoice->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ $invoice->status == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    </select>
                </div>

                <!-- Invoice Dates -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Date *</label>
                    <input type="date" name="invoice_date" required value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date" required value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax (%)</label>
                    <input type="number" name="tax" step="0.01" min="0" value="{{ old('tax', $invoice->tax) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" 
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $invoice->notes) }}</textarea>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h3>
                
                <div id="items-container">
                    @foreach($invoice->items as $index => $item)
                    <div class="item-row grid grid-cols-12 gap-4 mb-4">
                        <div class="col-span-6">
                            <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" placeholder="Description" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" placeholder="Quantity" required min="1"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="col-span-3">
                            <input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" placeholder="Unit Price" required min="0" step="0.01"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="col-span-1">
                            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" onclick="addItem()" class="mt-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i>Add Item
                </button>
            </div>

            <div class="mt-8 flex justify-end space-x-2">
                <a href="{{ route('invoices.show', $invoice) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Invoice
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let itemCount = {{ count($invoice->items) }};

function addItem() {
    const container = document.getElementById('items-container');
    const newRow = document.createElement('div');
    newRow.className = 'item-row grid grid-cols-12 gap-4 mb-4';
    newRow.innerHTML = `
        <div class="col-span-6">
            <input type="text" name="items[${itemCount}][description]" placeholder="Description" required
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${itemCount}][quantity]" placeholder="Quantity" required min="1"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="col-span-3">
            <input type="number" name="items[${itemCount}][unit_price]" placeholder="Unit Price" required min="0" step="0.01"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="col-span-1">
            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-900">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newRow);
    itemCount++;
}

function removeItem(button) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        button.closest('.item-row').remove();
    } else {
        alert('You need at least one item.');
    }
}
</script>
@endsection