<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'public_token',
        'customer_name',
        'customer_email',
        'customer_phone',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Generate a public token automatically.
     */
    protected static function booted()
    {
        static::creating(function ($invoice) {
            if (!$invoice->public_token) {
                $invoice->public_token = Str::random(48);
            }
        });
    }

    /**
     * Calculate invoice subtotal and total.
     */
    public function updateTotals()
    {
        $this->load('items');

        $this->subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $taxPercentage = (float) $this->tax;

        $taxAmount = ($this->subtotal * $taxPercentage) / 100;

        $this->total = $this->subtotal + $taxAmount;

        $this->saveQuietly();
    }

    /**
     * Get calculated tax amount.
     */
    public function getTaxAmountAttribute()
    {
        return ((float) $this->subtotal * (float) $this->tax) / 100;
    }
}