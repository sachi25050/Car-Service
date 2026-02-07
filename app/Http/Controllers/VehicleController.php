<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with('customer');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $vehicles = $query->latest()->paginate(15);
        $customers = Customer::where('is_active', true)->get();

        return view('vehicles.index', compact('vehicles', 'customers'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        return view('vehicles.create', compact('customers'));
    }

    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());

        ActivityLog::log('vehicle_created', "Vehicle {$vehicle->registration_number} created", $vehicle);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle created successfully.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['customer', 'appointments', 'jobCards']);
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        $customers = Customer::where('is_active', true)->get();
        return view('vehicles.edit', compact('vehicle', 'customers'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        ActivityLog::log('vehicle_updated', "Vehicle {$vehicle->registration_number} updated", $vehicle);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        ActivityLog::log('vehicle_deleted', "Vehicle {$vehicle->registration_number} deleted", $vehicle);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }

    public function getByCustomer(Customer $customer)
    {
        return response()->json($customer->vehicles);
    }
}
