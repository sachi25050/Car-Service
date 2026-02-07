<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\Staff;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['customer', 'vehicle', 'service', 'package', 'assignedStaff']);

        if ($request->has('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        $packages = ServicePackage::where('is_active', true)->get();
        $staff = Staff::with('user')->get();

        return view('appointments.create', compact('customers', 'services', 'packages', 'staff'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        // Check for conflicts
        $conflict = Appointment::where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked.'])->withInput();
        }

        $appointment = Appointment::create($data);

        ActivityLog::log('appointment_created', "Appointment created for {$appointment->customer->full_name}", $appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['customer', 'vehicle', 'service', 'package', 'assignedStaff', 'jobCard']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $customers = Customer::where('is_active', true)->get();
        $vehicles = Vehicle::where('customer_id', $appointment->customer_id)->get();
        $services = Service::where('is_active', true)->get();
        $packages = ServicePackage::where('is_active', true)->get();
        $staff = Staff::with('user')->get();

        return view('appointments.edit', compact('appointment', 'customers', 'vehicles', 'services', 'packages', 'staff'));
    }

    public function update(StoreAppointmentRequest $request, Appointment $appointment)
    {
        $data = $request->validated();

        // Check for conflicts (excluding current appointment)
        $conflict = Appointment::where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->where('id', '!=', $appointment->id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return back()->withErrors(['appointment_time' => 'This time slot is already booked.'])->withInput();
        }

        $appointment->update($data);

        ActivityLog::log('appointment_updated', "Appointment updated for {$appointment->customer->full_name}", $appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        ActivityLog::log('appointment_deleted', "Appointment deleted", $appointment);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled,no_show'
        ]);

        $appointment->update(['status' => $request->status]);

        ActivityLog::log('appointment_status_changed', "Appointment status changed to {$request->status}", $appointment);

        return back()->with('success', 'Appointment status updated.');
    }
}
