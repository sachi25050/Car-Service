@extends('layouts.app')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Appointment Details</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $appointment->appointment_date->format('l, F j, Y') }} at 
                {{ \Carbon\Carbon::createFromFormat('H:i', is_string($appointment->appointment_time) ? substr($appointment->appointment_time, 0, 5) : $appointment->appointment_time)->format('h:i A') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('appointments.edit', $appointment) }}" class="btn-secondary-modern">
                <i class="bi bi-pencil"></i>
                Edit
            </a>
            <a href="{{ route('appointments.index') }}" class="btn-secondary-modern">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Appointment Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointment Information</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $appointment->appointment_date->format('d/m/Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Time</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            {{ \Carbon\Carbon::createFromFormat('H:i', is_string($appointment->appointment_time) ? substr($appointment->appointment_time, 0, 5) : $appointment->appointment_time)->format('h:i A') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'confirmed' => 'bg-green-100 text-green-800 border-green-300',
                                    'in_progress' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'completed' => 'bg-gray-100 text-gray-800 border-gray-300',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-300',
                                    'no_show' => 'bg-orange-100 text-orange-800 border-orange-300',
                                ];
                                $statusClass = $statusColors[$appointment->status] ?? $statusColors['pending'];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estimated Duration</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->estimated_duration ?? 60 }} minutes</dd>
                    </div>
                    @if($appointment->service)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Service</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold">{{ $appointment->service->name }}</span>
                            <span class="text-gray-500 ml-2">- Rs.{{ number_format($appointment->service->base_price, 2) }}</span>
                        </dd>
                    </div>
                    @endif
                    @if($appointment->package)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Package</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold">{{ $appointment->package->name }}</span>
                            <span class="text-gray-500 ml-2">- Rs.{{ number_format($appointment->package->price, 2) }}</span>
                        </dd>
                    </div>
                    @endif
                    @if($appointment->assignedStaff)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Assigned Staff</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $appointment->assignedStaff->user->name }}
                            <span class="text-gray-500 ml-2">- {{ $appointment->assignedStaff->designation }}</span>
                        </dd>
                    </div>
                    @endif
                    @if($appointment->notes)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-700 whitespace-pre-wrap">{{ $appointment->notes }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Customer Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Customer Information</h3>
                    <a href="{{ route('customers.show', $appointment->customer) }}" 
                       class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        View Details <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $appointment->customer->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->customer->email ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->customer->phone }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Vehicle Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Vehicle Information</h3>
                    <a href="{{ route('vehicles.show', $appointment->vehicle) }}" 
                       class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        View Details <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Make & Model</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $appointment->vehicle->make }} {{ $appointment->vehicle->model }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $appointment->vehicle->registration_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Color</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $appointment->vehicle->color ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                
                <!-- Status Update Form -->
                <form action="{{ route('appointments.update-status', $appointment) }}" method="POST" class="mb-4">
                    @csrf
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Change Status
                    </label>
                    <select name="status" id="status" class="input-modern mb-3">
                        <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ $appointment->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $appointment->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ $appointment->status === 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                    <button type="submit" class="btn-primary-modern w-full">
                        <i class="bi bi-check-circle"></i>
                        Update Status
                    </button>
                </form>

                <!-- Job Card Actions -->
                @if($appointment->status !== 'completed' && !$appointment->jobCard)
                <a href="{{ route('job-cards.create', ['appointment_id' => $appointment->id]) }}" 
                   class="btn-primary-modern w-full mb-3">
                    <i class="bi bi-clipboard-plus"></i>
                    Create Job Card
                </a>
                @endif

                @if($appointment->jobCard)
                <a href="{{ route('job-cards.show', $appointment->jobCard) }}" 
                   class="btn-secondary-modern w-full">
                    <i class="bi bi-clipboard-check"></i>
                    View Job Card
                </a>
                @endif
            </div>

            <!-- Quick Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Info</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $appointment->created_at->format('d/m/Y h:i A') }}
                        </dd>
                    </div>
                    @if($appointment->updated_at != $appointment->created_at)
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $appointment->updated_at->format('d/m/Y h:i A') }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
