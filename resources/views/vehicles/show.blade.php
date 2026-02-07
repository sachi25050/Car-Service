@extends('layouts.app')

@section('title', 'Vehicle Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-car-front"></i> Vehicle Details</h1>
    <div>
        <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Vehicle Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Registration Number:</strong><br>
                        <span class="fs-5">{{ $vehicle->registration_number }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Make & Model:</strong><br>
                        <span class="fs-5">{{ $vehicle->make }} {{ $vehicle->model }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Year:</strong><br>
                        {{ $vehicle->year ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Color:</strong><br>
                        {{ $vehicle->color ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Vehicle Type:</strong><br>
                        <span class="badge bg-secondary">{{ ucfirst($vehicle->vehicle_type) }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Fuel Type:</strong><br>
                        <span class="badge bg-info">{{ ucfirst($vehicle->fuel_type) }}</span>
                    </div>
                    <div class="col-md-4">
                        <strong>Mileage:</strong><br>
                        {{ $vehicle->mileage ? number_format($vehicle->mileage) . ' km' : 'N/A' }}
                    </div>
                </div>
                @if($vehicle->vin_number)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>VIN Number:</strong><br>
                        {{ $vehicle->vin_number }}
                    </div>
                </div>
                @endif
                @if($vehicle->insurance_number)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Insurance Number:</strong><br>
                        {{ $vehicle->insurance_number }}
                    </div>
                    <div class="col-md-6">
                        <strong>Insurance Expiry:</strong><br>
                        {{ $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('M d, Y') : 'N/A' }}
                    </div>
                </div>
                @endif
                @if($vehicle->notes)
                <div class="mb-3">
                    <strong>Notes:</strong><br>
                    {{ $vehicle->notes }}
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $vehicle->customer->full_name }}</p>
                <p><strong>Email:</strong> {{ $vehicle->customer->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $vehicle->customer->phone }}</p>
                <a href="{{ route('customers.show', $vehicle->customer) }}" class="btn btn-sm btn-primary">
                    View Customer Details
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Appointments</h5>
            </div>
            <div class="card-body">
                @if($vehicle->appointments->count() > 0)
                <div class="list-group">
                    @foreach($vehicle->appointments->take(5) as $appointment)
                    <a href="{{ route('appointments.show', $appointment) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $appointment->appointment_date->format('M d, Y') }}</h6>
                            <small>{{ $appointment->appointment_time }}</small>
                        </div>
                        <p class="mb-1">{{ $appointment->service->name ?? $appointment->package->name ?? 'N/A' }}</p>
                        <small>
                            <span class="badge bg-{{ $appointment->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </small>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-muted">No appointments found</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Job Cards</h5>
            </div>
            <div class="card-body">
                @if($vehicle->jobCards->count() > 0)
                <div class="list-group">
                    @foreach($vehicle->jobCards->take(5) as $jobCard)
                    <a href="{{ route('job-cards.show', $jobCard) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $jobCard->job_number }}</h6>
                            <small>{{ $jobCard->created_at->format('M d') }}</small>
                        </div>
                        <p class="mb-1">₹{{ number_format($jobCard->final_amount, 2) }}</p>
                        <small>
                            <span class="badge bg-{{ $jobCard->status === 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($jobCard->status) }}
                            </span>
                        </small>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-muted">No job cards found</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
