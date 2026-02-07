@extends('layouts.app')

@section('title', 'Appointments')
@section('page-title', 'Appointments')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Appointments</h1>
            <p class="mt-1 text-sm text-gray-500">Manage customer appointments</p>
        </div>
        <a href="{{ route('appointments.create') }}" class="btn-primary-modern">
            <i class="bi bi-plus-circle"></i>
            New Appointment
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('appointments.index') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-12 sm:gap-4">
            <div class="sm:col-span-4">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" 
                       name="date" 
                       id="date"
                       value="{{ request('date') }}"
                       class="input-modern">
            </div>
            <div class="sm:col-span-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="input-modern">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="sm:col-span-4 flex items-end gap-2">
                <button type="submit" class="btn-primary-modern flex-1">
                    <i class="bi bi-search"></i>
                    Filter
                </button>
                <a href="{{ route('appointments.index') }}" class="btn-secondary-modern">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Appointments Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($appointments as $appointment)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <i class="bi bi-calendar3 text-primary-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $appointment->customer->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $appointment->vehicle->make }} {{ $appointment->vehicle->model }}</p>
                        </div>
                    </div>
                    <div class="space-y-1 text-sm">
                        <p class="text-gray-600">
                            <i class="bi bi-calendar-event mr-1"></i>
                            {{ $appointment->appointment_date->format('M d, Y') }}
                        </p>
                        <p class="text-gray-600">
                            <i class="bi bi-clock mr-1"></i>
                            {{ $appointment->appointment_time }}
                        </p>
                        <p class="text-gray-600">
                            <i class="bi bi-tools mr-1"></i>
                            {{ $appointment->service->name ?? $appointment->package->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                <x-badge :variant="$appointment->status === 'completed' ? 'success' : ($appointment->status === 'confirmed' ? 'info' : ($appointment->status === 'cancelled' ? 'danger' : 'warning'))">
                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                </x-badge>
            </div>
            <div class="flex items-center gap-2 pt-4 border-t border-gray-200">
                <a href="{{ route('appointments.show', $appointment) }}" 
                   class="flex-1 btn-secondary-modern text-center text-sm">
                    <i class="bi bi-eye"></i>
                    View
                </a>
                <a href="{{ route('appointments.edit', $appointment) }}" 
                   class="flex-1 btn-primary-modern text-center text-sm">
                    <i class="bi bi-pencil"></i>
                    Edit
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <i class="bi bi-calendar-x text-5xl text-gray-300 mb-4"></i>
                <p class="text-sm text-gray-500 mb-4">No appointments found</p>
                <a href="{{ route('appointments.create') }}" class="btn-primary-modern">
                    <i class="bi bi-plus-circle"></i>
                    Create Appointment
                </a>
            </div>
        </div>
        @endforelse
    </div>

    @if($appointments->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        {{ $appointments->links() }}
    </div>
    @endif
</div>
@endsection
