@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-check"></i> Appointment Details</h1>
    <div>
        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Appointment Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date:</strong><br>
                        <span class="fs-5">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Time:</strong><br>
                        <span class="fs-5">{{ $appointment->appointment_time }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ 
                            $appointment->status === 'completed' ? 'success' : 
                            ($appointment->status === 'cancelled' ? 'danger' : 
                            ($appointment->status === 'confirmed' ? 'primary' : 'warning')) 
                        }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Estimated Duration:</strong><br>
                        {{ $appointment->estimated_duration }} minutes
                    </div>
                </div>
                @if($appointment->service)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Service:</strong><br>
                        {{ $appointment->service->name }} - ₹{{ number_format($appointment->service->base_price, 2) }}
                    </div>
                </div>
                @endif
                @if($appointment->package)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Package:</strong><br>
                        {{ $appointment->package->name }} - ₹{{ number_format($appointment->package->price, 2) }}
                    </div>
                </div>
                @endif
                @if($appointment->assignedStaff)
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong>Assigned Staff:</strong><br>
                        {{ $appointment->assignedStaff->user->name }} - {{ $appointment->assignedStaff->designation }}
                    </div>
                </div>
                @endif
                @if($appointment->notes)
                <div class="mb-3">
                    <strong>Notes:</strong><br>
                    {{ $appointment->notes }}
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $appointment->customer->full_name }}</p>
                <p><strong>Email:</strong> {{ $appointment->customer->email ?? 'N/A' }}</p>
                <p><strong>Phone:</strong> {{ $appointment->customer->phone }}</p>
                <a href="{{ route('customers.show', $appointment->customer) }}" class="btn btn-sm btn-primary">
                    View Customer Details
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Vehicle Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Make & Model:</strong> {{ $appointment->vehicle->make }} {{ $appointment->vehicle->model }}</p>
                <p><strong>Registration:</strong> {{ $appointment->vehicle->registration_number }}</p>
                <p><strong>Color:</strong> {{ $appointment->vehicle->color ?? 'N/A' }}</p>
                <a href="{{ route('vehicles.show', $appointment->vehicle) }}" class="btn btn-sm btn-primary">
                    View Vehicle Details
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('appointments.update-status', $appointment) }}" method="POST" class="mb-3">
                    @csrf
                    <label for="status" class="form-label">Change Status</label>
                    <select name="status" id="status" class="form-select mb-2">
                        <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ $appointment->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>

                @if($appointment->status !== 'completed' && !$appointment->jobCard)
                <a href="{{ route('job-cards.create', ['appointment_id' => $appointment->id]) }}" class="btn btn-success w-100 mb-2">
                    <i class="bi bi-clipboard-plus"></i> Create Job Card
                </a>
                @endif

                @if($appointment->jobCard)
                <a href="{{ route('job-cards.show', $appointment->jobCard) }}" class="btn btn-info w-100">
                    <i class="bi bi-clipboard-check"></i> View Job Card
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
