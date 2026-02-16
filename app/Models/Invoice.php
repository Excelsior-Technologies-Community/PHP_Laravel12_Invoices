<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function updateTotals()
    {
        $this->subtotal = $this->items->sum('total');
        $this->total = $this->subtotal + $this->tax;
        $this->saveQuietly();
    }
}