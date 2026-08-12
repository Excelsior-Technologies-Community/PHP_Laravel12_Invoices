<?php

namespace App\Mail;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function build()
    {
        $this->invoice->load('items');

        $pdf = Pdf::loadView('invoices.pdf', [
            'invoice' => $this->invoice,
        ]);

        return $this
            ->subject('Invoice ' . $this->invoice->invoice_number)
            ->view('emails.invoice')
            ->attachData(
                $pdf->output(),
                $this->invoice->invoice_number . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}