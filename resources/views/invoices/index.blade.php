@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', 'Invoices')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoices</h1>
            <p class="mt-1 text-sm text-gray-500">Manage customer invoices and payments</p>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('invoices.index') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-12 sm:gap-4">
            <div class="sm:col-span-5">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search by invoice number or customer..."
                       class="input-modern">
            </div>
            <div class="sm:col-span-3">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="input-modern">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
                    <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <div class="sm:col-span-4 flex items-end gap-2">
                <button type="submit" class="btn-primary-modern flex-1">
                    <i class="bi bi-search"></i>
                    Search
                </button>
                <a href="{{ route('invoices.index') }}" class="btn-secondary-modern">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td>
                            <span class="font-semibold text-gray-900">{{ $invoice->invoice_number }}</span>
                        </td>
                        <td>
                            <p class="font-medium text-gray-900">{{ $invoice->customer->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $invoice->customer->phone }}</p>
                        </td>
                        <td>
                            <span class="text-gray-900">{{ $invoice->invoice_date->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <span class="font-semibold text-gray-900">₹{{ number_format($invoice->total_amount, 2) }}</span>
                        </td>
                        <td>
                            <span class="text-gray-900">₹{{ number_format($invoice->paid_amount, 2) }}</span>
                        </td>
                        <td>
                            <span class="font-medium {{ $invoice->balance_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                ₹{{ number_format($invoice->balance_amount, 2) }}
                            </span>
                        </td>
                        <td>
                            <x-badge :variant="$invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : ($invoice->status === 'partial' ? 'warning' : 'default'))">
                                {{ ucfirst($invoice->status) }}
                            </x-badge>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('invoices.show', $invoice) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('invoices.print', $invoice) }}" 
                                   target="_blank"
                                   class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors"
                                   title="Print">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <i class="bi bi-receipt text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-500">No invoices found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
