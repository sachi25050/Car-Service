<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobCardRequest;
use App\Models\JobCard;
use App\Models\JobCardService;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Invoice;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobCardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobCard::with(['customer', 'vehicle', 'assignedStaff']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $jobCards = $query->latest()->paginate(15);

        return view('job-cards.index', compact('jobCards'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        $staff = Staff::with('user')->get();

        return view('job-cards.create', compact('customers', 'services', 'staff'));
    }

    public function store(StoreJobCardRequest $request)
    {
        $data = $request->validated();
        $data['job_number'] = 'JC-' . strtoupper(Str::random(8));
        $data['created_by'] = auth()->id();

        $jobCard = JobCard::create($data);

        // Add services
        foreach ($request->services as $serviceData) {
            $service = Service::find($serviceData['service_id']);
            JobCardService::create([
                'job_card_id' => $jobCard->id,
                'service_id' => $serviceData['service_id'],
                'quantity' => $serviceData['quantity'],
                'unit_price' => $service->base_price,
                'total_price' => $service->base_price * $serviceData['quantity'],
            ]);
        }

        $jobCard->calculateTotal();

        ActivityLog::log('job_card_created', "Job card {$jobCard->job_number} created", $jobCard);

        return redirect()->route('job-cards.show', $jobCard)
            ->with('success', 'Job card created successfully.');
    }

    public function show(JobCard $jobCard)
    {
        $jobCard->load(['customer', 'vehicle', 'services.service', 'assignedStaff', 'invoice']);
        return view('job-cards.show', compact('jobCard'));
    }

    public function edit(JobCard $jobCard)
    {
        $customers = Customer::where('is_active', true)->get();
        $vehicles = Vehicle::where('customer_id', $jobCard->customer_id)->get();
        $services = Service::where('is_active', true)->get();
        $staff = Staff::with('user')->get();

        $jobCard->load('services');

        return view('job-cards.edit', compact('jobCard', 'customers', 'vehicles', 'services', 'staff'));
    }

    public function update(Request $request, JobCard $jobCard)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled,on_hold',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'assigned_staff_id' => 'nullable|exists:staff,id',
            'notes' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $jobCard->update($request->only(['status', 'priority', 'assigned_staff_id', 'notes', 'discount_amount']));

        if ($request->has('tax_percentage')) {
            $jobCard->tax_amount = ($jobCard->total_amount - $jobCard->discount_amount) * ($request->tax_percentage / 100);
            $jobCard->save();
        }

        $jobCard->calculateTotal();

        ActivityLog::log('job_card_updated', "Job card {$jobCard->job_number} updated", $jobCard);

        return redirect()->route('job-cards.show', $jobCard)
            ->with('success', 'Job card updated successfully.');
    }

    public function destroy(JobCard $jobCard)
    {
        $jobCard->delete();

        ActivityLog::log('job_card_deleted', "Job card {$jobCard->job_number} deleted", $jobCard);

        return redirect()->route('job-cards.index')
            ->with('success', 'Job card deleted successfully.');
    }

    public function createInvoice(JobCard $jobCard)
    {
        if ($jobCard->invoice) {
            return redirect()->route('invoices.show', $jobCard->invoice)
                ->with('info', 'Invoice already exists for this job card.');
        }

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'job_card_id' => $jobCard->id,
            'customer_id' => $jobCard->customer_id,
            'invoice_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $jobCard->total_amount,
            'discount_amount' => $jobCard->discount_amount,
            'tax_percentage' => 0,
            'tax_amount' => $jobCard->tax_amount,
            'total_amount' => $jobCard->final_amount,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        $invoice->calculateTotals();

        ActivityLog::log('invoice_created', "Invoice {$invoice->invoice_number} created from job card", $invoice);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }
}
