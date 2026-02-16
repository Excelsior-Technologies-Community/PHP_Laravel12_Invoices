@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Create New Invoice</h2>
    </div>

    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
        @csrf
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Information -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                    <input type="text" name="customer_name" required 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Email *</label>
                    <input type="email" name="customer_email" required 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Phone</label>
                    <input type="text" name="customer_phone" 
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div></div>

                <!-- Invoice Dates -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Date *</label>
                    <input type="date" name="invoice_date" required value="{{ date('Y-m-d') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date *</label>
                    <input type="date" name="due_date" required value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tax (%)</label>
                    <input type="number" name="tax" step="0.01" min="0" value="0"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" 
                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h3>
                
                <div id="items-container">
                    <div class="item-row grid grid-cols-12 gap-4 mb-4">
                        <div class="col-span-6">
                            <input type="text" name="items[0][description]" placeholder="Description" required
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="col-span-2">
                            <input type="number" name="items[0][quantity]" placeholder="Quantity" required min="1"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="col-span-3">
                            <input type="number" name="items[0][unit_price]" placeholder="Unit Price" required min="0" step="0.01"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="col-span-1">
                            <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addItem()" class="mt-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i>Add Item
                </button>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Invoice
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let itemCount = 1;

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