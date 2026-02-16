# PHP_Laravel12_Invoices

## Overview

Laravel 12 Invoice Management System is a complete CRUD-based billing application built with Laravel. It allows creating, editing, viewing, and deleting invoices with multiple line items, automatic calculations, and status management.

This project is suitable for academic projects, portfolio demonstrations, and real‑world billing systems.

---

## Core Features

* Create invoices with customer details
* Add multiple invoice items dynamically
* Automatic subtotal and total calculations
* Edit and update invoices
* Delete invoices with confirmation
* Invoice status management (Draft, Sent, Paid, Overdue)
* Tailwind CSS responsive UI
* One‑to‑Many relationship between Invoice and Items
* Factory support for testing data

---

## Technology Stack

* PHP 8+
* Laravel 12
* MySQL
* Tailwind CSS
* Faker
* Blade Templates

---

## Installation Guide

### Step 1 – Create Laravel Project

```
composer create-project laravel/laravel laravel-invoices
cd laravel-invoices
```

### Step 2 – Configure Database

Edit `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_invoices
DB_USERNAME=root
DB_PASSWORD=
```

Create the database manually in MySQL or phpMyAdmin.

---

## Database Migrations

### Invoices Table Fields

* id
* invoice_number
* customer_name
* customer_email
* customer_phone
* invoice_date
* due_date
* subtotal
* tax
* total
* status
* notes
* timestamps

### Invoice Items Table Fields

* id
* invoice_id
* description
* quantity
* unit_price
* total
* timestamps

Run migrations:

```
php artisan migrate
```

---

## Models

### Invoice Model

Responsibilities:

* Fillable fields
* Date and decimal casting
* Relationship with InvoiceItem
* Automatic total updates

### InvoiceItem Model

Responsibilities:

* Belongs to Invoice
* Automatic total calculation
* Trigger invoice total update on save/delete

---

## Controllers

### InvoiceController

Main Methods:

* index – List invoices with pagination
* create – Show create form
* store – Save invoice and items
* show – Display invoice details
* edit – Edit invoice and items
* update – Update invoice and sync items
* destroy – Delete invoice
* updateStatus – AJAX status update

---

## Routes

```
Route::get('/', function () {
    return redirect()->route('invoices.index');
});

Route::resource('invoices', InvoiceController::class);
Route::post('/invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');
```
<img width="1767" height="513" alt="image" src="https://github.com/user-attachments/assets/2286496c-5474-4186-a236-e138185494ea" />
<img width="1608" height="944" alt="image" src="https://github.com/user-attachments/assets/62c928cd-5255-477d-bb2b-16f54ba9ab3b" />
<img width="1649" height="959" alt="image" src="https://github.com/user-attachments/assets/281779f7-d68d-42de-b0da-9516da850450" />

---

## Views Structure

```
resources/views/
 ├── layouts/app.blade.php
 └── invoices/
     ├── index.blade.php
     ├── create.blade.php
     ├── edit.blade.php
     └── show.blade.php
```

### UI Features

* Dynamic item rows
* Status badges
* Tailwind design
* Responsive tables
* Confirmation dialogs

---

## Factories (Optional)

Create test data:

```
php artisan make:factory InvoiceFactory --model=Invoice
php artisan make:factory InvoiceItemFactory --model=InvoiceItem
```

Factories generate realistic fake invoice data for testing.

---

## Running the Application

```
php artisan serve
```

Visit:

```
http://localhost:8000
```

---

## Implemented Functionalities

* Create invoices with multiple items
* Automatic subtotal and total calculation
* Edit and update invoice items dynamically
* Delete invoices safely
* Invoice status management
* Tailwind responsive design
* Database relationships

---

## Suggested Enhancements

* PDF Invoice Export
* Email Invoice Sending
* Search and Filters
* User Authentication
* Payment Tracking
* Invoice Templates
* Reports and Analytics

---

## Use Cases

* Freelance Billing
* Small Business Invoicing
* Academic Projects
* Portfolio Demonstration
* Internal Company Billing

---

## Requirements

* PHP 8+
* Composer
* MySQL
* Laravel 12
* Internet Connection

---

## Author

Mihir Mehta

---

## License

MIT License

