<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'invoice_number' => 'INV-' . date('Y') . '-' . fake()->unique()->numberBetween(1000, 9999),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->email(),
            'customer_phone' => fake()->phoneNumber(),
            'invoice_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'subtotal' => 0,
            'tax' => fake()->randomFloat(2, 0, 100),
            'total' => 0,
            'status' => fake()->randomElement(['draft', 'sent', 'paid', 'overdue']),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}