@extends('layouts.app')

@section('title', 'Invoices')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Invoice Dashboard
            </h2>

            <p class="text-gray-500 mt-1">
                Search, filter and manage your invoices.
            </p>
        </div>

        <a href="{{ route('invoices.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">

            <i class="fas fa-plus mr-2"></i>
            Create Invoice

        </a>

    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- Total --}}
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Total Invoices
                    </p>

                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $totalInvoices }}
                    </p>
                </div>

                <div class="text-blue-500 text-2xl">
                    <i class="fas fa-file-invoice"></i>
                </div>

            </div>

        </div>


        {{-- Paid --}}
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Paid
                    </p>

                    <p class="text-2xl font-bold text-green-600 mt-1">
                        {{ $paidInvoices }}
                    </p>
                </div>

                <div class="text-green-500 text-2xl">
                    <i class="fas fa-check-circle"></i>
                </div>

            </div>

        </div>


        {{-- Pending --}}
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-yellow-500">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Pending
                    </p>

                    <p class="text-2xl font-bold text-yellow-600 mt-1">
                        {{ $pendingInvoices }}
                    </p>
                </div>

                <div class="text-yellow-500 text-2xl">
                    <i class="fas fa-clock"></i>
                </div>

            </div>

        </div>


        {{-- Overdue --}}
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Overdue
                    </p>

                    <p class="text-2xl font-bold text-red-600 mt-1">
                        {{ $overdueInvoices }}
                    </p>
                </div>

                <div class="text-red-500 text-2xl">
                    <i class="fas fa-exclamation-circle"></i>
                </div>

            </div>

        </div>


        {{-- Revenue --}}
        <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Paid Revenue
                    </p>

                    <p class="text-2xl font-bold text-purple-600 mt-1">
                        ${{ number_format($totalRevenue, 2) }}
                    </p>
                </div>

                <div class="text-purple-500 text-2xl">
                    <i class="fas fa-dollar-sign"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow">

        <div class="px-6 py-4 border-b border-gray-200">

            <h3 class="text-lg font-semibold text-gray-800">

                <i class="fas fa-filter mr-2 text-blue-500"></i>
                Invoice Filters

            </h3>

        </div>


        <form action="{{ route('invoices.index') }}"
            method="GET"
            class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                {{-- Search --}}
                <div class="lg:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Invoice number, customer name or email..."
                        class="w-full border-gray-300 rounded-md shadow-sm
                               focus:border-blue-500 focus:ring-blue-500">

                </div>


                {{-- Status --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full border-gray-300 rounded-md shadow-sm
                               focus:border-blue-500 focus:ring-blue-500">

                        <option value="">
                            All Status
                        </option>

                        <option value="draft"
                            {{ request('status') == 'draft' ? 'selected' : '' }}>
                            Draft
                        </option>

                        <option value="sent"
                            {{ request('status') == 'sent' ? 'selected' : '' }}>
                            Sent
                        </option>

                        <option value="paid"
                            {{ request('status') == 'paid' ? 'selected' : '' }}>
                            Paid
                        </option>

                        <option value="overdue"
                            {{ request('status') == 'overdue' ? 'selected' : '' }}>
                            Overdue
                        </option>

                    </select>

                </div>


                {{-- From Date --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        From Date
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm
                               focus:border-blue-500 focus:ring-blue-500">

                </div>


                {{-- To Date --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        To Date
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm
                               focus:border-blue-500 focus:ring-blue-500">

                </div>

            </div>


            {{-- Buttons --}}
            <div class="mt-5 flex flex-wrap gap-2">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white
                           font-bold py-2 px-5 rounded-lg">

                    <i class="fas fa-search mr-2"></i>
                    Search

                </button>


                <a
                    href="{{ route('invoices.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800
                           font-bold py-2 px-5 rounded-lg">

                    <i class="fas fa-redo mr-2"></i>
                    Reset

                </a>

            </div>

        </form>

    </div>


    {{-- Invoice Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200">

            <div class="flex justify-between items-center">

                <div>

                    <h3 class="text-lg font-semibold text-gray-800">
                        Invoices
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ $invoices->total() }} invoice(s) found
                    </p>

                </div>

            </div>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-medium
                                   text-gray-500 uppercase tracking-wider">
                            Invoice #
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium
                                   text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium
                                   text-gray-500 uppercase tracking-wider">
                            Invoice Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium
                                   text-gray-500 uppercase tracking-wider">
                            Due Date
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium
                                   text-gray-500 uppercase tracking-wider">
                            Total
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium
                                   text-gray-500 uppercase tracking-wider">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium
                                   text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="bg-white divide-y divide-gray-200">

                    @forelse($invoices as $invoice)

                    <tr class="hover:bg-gray-50">

                        {{-- Invoice Number --}}
                        <td class="px-6 py-4 whitespace-nowrap">

                            <span class="text-sm font-semibold text-gray-900">
                                {{ $invoice->invoice_number }}
                            </span>

                        </td>


                        {{-- Customer --}}
                        <td class="px-6 py-4">

                            <div class="text-sm font-medium text-gray-900">
                                {{ $invoice->customer_name }}
                            </div>

                            <div class="text-sm text-gray-500">
                                {{ $invoice->customer_email }}
                            </div>

                        </td>


                        {{-- Invoice Date --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                            {{ $invoice->invoice_date->format('M d, Y') }}

                        </td>


                        {{-- Due Date --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">

                            {{ $invoice->due_date->format('M d, Y') }}

                        </td>


                        {{-- Total --}}
                        <td class="px-6 py-4 whitespace-nowrap">

                            <span class="text-sm font-semibold text-gray-900">
                                ${{ number_format($invoice->total, 2) }}
                            </span>

                        </td>


                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">

                            @if($invoice->status == 'paid')

                            <span class="px-3 py-1 inline-flex text-xs
                                                 font-semibold rounded-full
                                                 bg-green-100 text-green-800">

                                <i class="fas fa-check mr-1 mt-0.5"></i>
                                Paid

                            </span>

                            @elseif($invoice->status == 'sent')

                            <span class="px-3 py-1 inline-flex text-xs
                                                 font-semibold rounded-full
                                                 bg-blue-100 text-blue-800">

                                <i class="fas fa-paper-plane mr-1 mt-0.5"></i>
                                Sent

                            </span>

                            @elseif($invoice->status == 'overdue')

                            <span class="px-3 py-1 inline-flex text-xs
                                                 font-semibold rounded-full
                                                 bg-red-100 text-red-800">

                                <i class="fas fa-exclamation mr-1 mt-0.5"></i>
                                Overdue

                            </span>

                            @else

                            <span class="px-3 py-1 inline-flex text-xs
                                                 font-semibold rounded-full
                                                 bg-gray-100 text-gray-800">

                                <i class="fas fa-file mr-1 mt-0.5"></i>
                                Draft

                            </span>

                            @endif

                        </td>


                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm">

                            <div class="flex items-center space-x-3">

                                {{-- View --}}
                                <a
                                    href="{{ route('invoices.show', $invoice) }}"
                                    class="text-blue-600 hover:text-blue-900"
                                    title="View">
                                    <i class="fas fa-eye"></i>
                                </a>


                                {{-- Edit --}}
                                <a
                                    href="{{ route('invoices.edit', $invoice) }}"
                                    class="text-yellow-600 hover:text-yellow-900"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>


                                {{-- PDF --}}
                                <a
                                    href="{{ route('invoices.pdf', $invoice) }}"
                                    class="text-purple-600 hover:text-purple-900"
                                    title="Download PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>


                                {{-- Delete --}}
                                <form
                                    action="{{ route('invoices.destroy', $invoice) }}"
                                    method="POST"
                                    class="inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-red-600 hover:text-red-900"
                                        title="Delete"
                                        onclick="return confirm('Are you sure you want to delete this invoice?')">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7"
                            class="px-6 py-12 text-center">

                            <div class="text-gray-400 text-5xl mb-4">
                                <i class="fas fa-file-invoice"></i>
                            </div>

                            <h3 class="text-lg font-medium text-gray-700">
                                No invoices found
                            </h3>

                            <p class="text-gray-500 mt-1">
                                Try changing your search or filters.
                            </p>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($invoices->hasPages())

        <div class="px-6 py-4 border-t border-gray-200">

            {{ $invoices->onEachSide(1)->links() }}

        </div>

        @endif

    </div>

</div>

@endsection