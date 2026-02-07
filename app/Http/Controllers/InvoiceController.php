<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\JobCard;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'jobCard']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->latest()->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'jobCard.services.service', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'nullable|in:draft,sent,paid,partial,overdue,cancelled',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        if ($request->has('tax_percentage')) {
            $invoice->tax_percentage = $request->tax_percentage;
        }

        if ($request->has('discount_amount')) {
            $invoice->discount_amount = $request->discount_amount;
        }

        if ($request->has('status')) {
            $invoice->status = $request->status;
        }

        $invoice->calculateTotals();

        ActivityLog::log('invoice_updated', "Invoice {$invoice->invoice_number} updated", $invoice);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['customer', 'jobCard.services.service', 'payments']);
        return view('invoices.print', compact('invoice'));
    }
}
