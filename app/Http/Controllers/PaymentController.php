<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice', 'customer']);

        if ($request->has('invoice_id')) {
            $query->where('invoice_id', $request->invoice_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest()->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $invoices = Invoice::whereIn('status', ['draft', 'sent', 'partial', 'overdue'])
            ->with('customer')
            ->get();

        return view('payments.create', compact('invoices'));
    }

    public function createForInvoice(Invoice $invoice)
    {
        $invoice->load('customer');
        return view('payments.create', compact('invoice'));
    }

    public function store(StorePaymentRequest $request)
    {
        $data = $request->validated();
        $data['payment_number'] = 'PAY-' . strtoupper(Str::random(8));
        $data['customer_id'] = Invoice::find($data['invoice_id'])->customer_id;
        $data['received_by'] = auth()->id();

        $payment = Payment::create($data);

        ActivityLog::log('payment_created', "Payment {$payment->payment_number} created", $payment);

        return redirect()->route('invoices.show', $payment->invoice)
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice', 'customer', 'receiver']);
        return view('payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;
        $payment->delete();

        ActivityLog::log('payment_deleted', "Payment {$payment->payment_number} deleted", $payment);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Payment deleted successfully.');
    }
}
