@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-check"></i> Appointments</h1>
    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Appointment
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('appointments.index') }}" class="mb-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr>
                        <td>
                            {{ $appointment->appointment_date->format('M d, Y') }}<br>
                            <small class="text-muted">{{ $appointment->appointment_time }}</small>
                        </td>
                        <td>{{ $appointment->customer->full_name }}</td>
                        <td>{{ $appointment->vehicle->make }} {{ $appointment->vehicle->model }}</td>
                        <td>{{ $appointment->service->name ?? $appointment->package->name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $appointment->status === 'completed' ? 'success' : 
                                ($appointment->status === 'cancelled' ? 'danger' : 
                                ($appointment->status === 'confirmed' ? 'primary' : 'warning')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">No appointments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection
