@extends('layouts.app')

@section('title', 'Vehicle Details')
@section('page-title', 'Vehicle Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $vehicle->registration_number }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn-secondary-modern">
                <i class="bi bi-pencil"></i>
                Edit
            </a>
            <a href="{{ route('vehicles.index') }}" class="btn-secondary-modern">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Vehicle Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $vehicle->registration_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Make & Model</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Year</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->year ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Color</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->color ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Vehicle Type</dt>
                        <dd class="mt-1">
                            <span class="badge-modern badge-secondary">{{ ucfirst($vehicle->vehicle_type ?? 'N/A') }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fuel Type</dt>
                        <dd class="mt-1">
                            <span class="badge-modern badge-info">{{ ucfirst($vehicle->fuel_type ?? 'N/A') }}</span>
                        </dd>
                    </div>
                    @if($vehicle->mileage)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Mileage</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($vehicle->mileage) }} km</dd>
                    </div>
                    @endif
                    @if($vehicle->vin_number)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">VIN Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $vehicle->vin_number }}</dd>
                    </div>
                    @endif
                    @if($vehicle->insurance_number)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Insurance Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->insurance_number }}</dd>
                    </div>
                    @endif
                    @if($vehicle->insurance_expiry)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Insurance Expiry</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->insurance_expiry->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            @if($vehicle->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $vehicle->notes }}</p>
            </div>
            @endif

            <!-- Customer Information -->
            @if($vehicle->customer)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    <a href="{{ route('customers.show', $vehicle->customer) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        View Details <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->customer->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->customer->email ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $vehicle->customer->phone }}</dd>
                    </div>
                </dl>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Appointments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $vehicle->appointments->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Job Cards</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $vehicle->jobCards->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Appointments -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Appointments</h3>
                    @if($vehicle->appointments->count() > 5)
                    <a href="{{ route('appointments.index', ['vehicle_id' => $vehicle->id]) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        View All
                    </a>
                    @endif
                </div>
                <div class="space-y-3">
                    @forelse($vehicle->appointments->take(5) as $appointment)
                    <a href="{{ route('appointments.show', $appointment) }}" 
                       class="block p-3 border border-gray-200 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-medium text-gray-900">{{ $appointment->appointment_date->format('d/m/Y') }}</p>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::createFromFormat('H:i', is_string($appointment->appointment_time) ? substr($appointment->appointment_time, 0, 5) : $appointment->appointment_time)->format('h:i A') }}</span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $appointment->service->name ?? $appointment->package->name ?? 'N/A' }}</p>
                        <div class="mt-2">
                            <x-badge :variant="$appointment->status === 'completed' ? 'success' : ($appointment->status === 'in_progress' ? 'info' : ($appointment->status === 'cancelled' ? 'danger' : 'warning'))">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </x-badge>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No appointments</p>
                    @endforelse
                </div>
            </div>

            <!-- Job Cards -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Job Cards</h3>
                    @if($vehicle->jobCards->count() > 5)
                    <a href="{{ route('job-cards.index', ['vehicle_id' => $vehicle->id]) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        View All
                    </a>
                    @endif
                </div>
                <div class="space-y-3">
                    @forelse($vehicle->jobCards->take(5) as $jobCard)
                    <a href="{{ route('job-cards.show', $jobCard) }}" 
                       class="block p-3 border border-gray-200 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <p class="font-medium text-gray-900">{{ $jobCard->job_number }}</p>
                            <span class="text-xs text-gray-500">{{ $jobCard->created_at->format('d/m/Y') }}</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">Rs.{{ number_format($jobCard->final_amount, 2) }}</p>
                        <div class="mt-2">
                            <x-badge :variant="$jobCard->status === 'completed' ? 'success' : ($jobCard->status === 'in_progress' ? 'info' : ($jobCard->status === 'cancelled' ? 'danger' : 'warning'))">
                                {{ ucfirst(str_replace('_', ' ', $jobCard->status)) }}
                            </x-badge>
                        </div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No job cards</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
