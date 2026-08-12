<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{
    /**
     * Display invoices with search, filters, statistics and pagination.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search & Filters
        |--------------------------------------------------------------------------
        */

        $query = Invoice::query();

        // Search by invoice number, customer name or email
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // From date
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        // To date
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $invoices = $query
            ->oldest()
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalInvoices = Invoice::count();

        $paidInvoices = Invoice::where('status', 'paid')->count();

        $pendingInvoices = Invoice::whereIn('status', ['draft', 'sent'])->count();

        $overdueInvoices = Invoice::where('status', 'overdue')->count();

        $totalRevenue = Invoice::where('status', 'paid')->sum('total');

        return view('invoices.index', compact(
            'invoices',
            'totalInvoices',
            'paidInvoices',
            'pendingInvoices',
            'overdueInvoices',
            'totalRevenue'
        ));
    }

    /**
     * Show create invoice form.
     */
    public function create()
    {
        return view('invoices.create');
    }

    /**
     * Store invoice.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'tax' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:draft,sent,paid,overdue',
            'notes' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);
        // Generate invoice number
        $invoiceNumber = 'INV-' . date('Y') . '-' .
            Str::padLeft(Invoice::count() + 1, 5, '0');

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'public_token' => Str::random(64),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'] ?? null,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'tax' => $validated['tax'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'subtotal' => 0,
            'total' => 0,
            'status' => $validated['status'] ?? 'draft',
        ]);

        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        $invoice->load('items');
        $invoice->updateTotals();

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('items');

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show edit form.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load('items');

        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update invoice.
     */
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
            'customer_phone' => $validated['customer_phone'] ?? null,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'tax' => $validated['tax'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'],
        ]);

        // Current item IDs
        $currentItemIds = $invoice->items->pluck('id')->toArray();

        $updatedItemIds = [];

        foreach ($validated['items'] as $itemData) {

            if (
                isset($itemData['id']) &&
                in_array($itemData['id'], $currentItemIds)
            ) {
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
        $itemsToDelete = array_diff(
            $currentItemIds,
            $updatedItemIds
        );

        if (!empty($itemsToDelete)) {
            InvoiceItem::whereIn('id', $itemsToDelete)->delete();
        }

        $invoice->load('items');
        $invoice->updateTotals();

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Delete invoice.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Update invoice status.
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,paid,overdue',
        ]);

        $invoice->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Download invoice PDF.
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load('items');

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download(
            $invoice->invoice_number . '.pdf'
        );
    }

    /**
     * Send invoice via email.
     */
    public function sendEmail(Invoice $invoice)
    {
        $invoice->load('items');

        Mail::to($invoice->customer_email)
            ->send(new InvoiceMail($invoice));

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice sent successfully to ' . $invoice->customer_email);
    }

    /**
     * Display invoice publicly.
     */
    public function publicInvoice(string $token)
    {
        $invoice = Invoice::where('public_token', $token)
            ->with('items')
            ->firstOrFail();

        return view('invoices.public', compact('invoice'));
    }
}
