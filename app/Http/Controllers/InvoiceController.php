<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Y') . '-' . Str::padLeft(Invoice::count() + 1, 5, '0');

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'tax' => $validated['tax'] ?? 0,
            'notes' => $validated['notes'],
            'subtotal' => 0,
            'total' => 0,
        ]);

        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price']
            ]);
        }

        $invoice->updateTotals();

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,sent,paid,overdue',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:invoice_items,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'tax' => $validated['tax'] ?? 0,
            'notes' => $validated['notes'],
            'status' => $validated['status'],
        ]);

        // Get current item IDs
        $currentItemIds = $invoice->items->pluck('id')->toArray();
        $updatedItemIds = [];

        foreach ($validated['items'] as $itemData) {
            if (isset($itemData['id']) && in_array($itemData['id'], $currentItemIds)) {
                // Update existing item
                $item = InvoiceItem::find($itemData['id']);
                $item->update([
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                ]);
                $updatedItemIds[] = $itemData['id'];
            } else {
                // Create new item
                $newItem = $invoice->items()->create([
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                ]);
                $updatedItemIds[] = $newItem->id;
            }
        }

        // Delete removed items
        $itemsToDelete = array_diff($currentItemIds, $updatedItemIds);
        InvoiceItem::whereIn('id', $itemsToDelete)->delete();

        $invoice->updateTotals();

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,paid,overdue'
        ]);

        $invoice->update(['status' => $validated['status']]);

        return response()->json(['success' => true]);
    }
}