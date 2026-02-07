@extends('layouts.app')

@section('title', 'Edit Appointment')
@section('page-title', 'Edit Appointment')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Appointment</h1>
            <p class="mt-1 text-sm text-gray-500">Update appointment information</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('appointments.show', $appointment) }}" class="btn-secondary-modern">
                <i class="bi bi-eye"></i>
                View
            </a>
            <a href="{{ route('appointments.index') }}" class="btn-secondary-modern">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Customer & Vehicle -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer & Vehicle</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Customer <span class="text-red-500">*</span>
                        </label>
                        <select id="customer_id" 
                                name="customer_id" 
                                required
                                class="input-modern @error('customer_id') border-red-300 @enderror">
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $appointment->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->full_name }} - {{ $customer->phone }}
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Vehicle <span class="text-red-500">*</span>
                        </label>
                        <select id="vehicle_id" 
                                name="vehicle_id" 
                                required
                                class="input-modern @error('vehicle_id') border-red-300 @enderror">
                            <option value="">Select Vehicle</option>
                            @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $appointment->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->registration_number }})
                            </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Service & Package -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Information</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select id="service_id" 
                                name="service_id"
                                class="input-modern @error('service_id') border-red-300 @enderror">
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" data-duration="{{ $service->duration_minutes }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} ({{ $service->duration_minutes }} min) - Rs.{{ number_format($service->base_price, 2) }}
                            </option>
                            @endforeach
                        </select>
                        @error('service_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="package_id" class="block text-sm font-medium text-gray-700 mb-1">Service Package</label>
                        <select id="package_id" 
                                name="package_id"
                                class="input-modern @error('package_id') border-red-300 @enderror">
                            <option value="">Select Package</option>
                            @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-duration="{{ $package->duration_minutes }}" {{ old('package_id', $appointment->package_id) == $package->id ? 'selected' : '' }}>
                                {{ $package->name }} ({{ $package->duration_minutes }} min) - Rs.{{ number_format($package->price, 2) }}
                            </option>
                            @endforeach
                        </select>
                        @error('package_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Date & Time -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointment Schedule</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Appointment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="appointment_date" 
                               name="appointment_date" 
                               value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" 
                               min="{{ date('Y-m-d') }}" 
                               required
                               class="input-modern @error('appointment_date') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500">System uses dd/mm/yyyy format</p>
                        @error('appointment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Appointment Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="appointment_time" 
                               name="appointment_time" 
                               value="{{ old('appointment_time', is_string($appointment->appointment_time) ? substr($appointment->appointment_time, 0, 5) : $appointment->appointment_time) }}" 
                               required
                               class="input-modern @error('appointment_time') border-red-300 @enderror">
                        @error('appointment_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Duration & Staff -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Details</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="estimated_duration" class="block text-sm font-medium text-gray-700 mb-1">Estimated Duration (minutes)</label>
                        <input type="number" 
                               id="estimated_duration" 
                               name="estimated_duration" 
                               value="{{ old('estimated_duration', $appointment->estimated_duration) }}" 
                               min="15" 
                               step="15"
                               class="input-modern @error('estimated_duration') border-red-300 @enderror">
                        @error('estimated_duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assigned_staff_id" class="block text-sm font-medium text-gray-700 mb-1">Assign Staff</label>
                        <select id="assigned_staff_id" 
                                name="assigned_staff_id"
                                class="input-modern @error('assigned_staff_id') border-red-300 @enderror">
                            <option value="">No Assignment</option>
                            @foreach($staff as $staffMember)
                            <option value="{{ $staffMember->id }}" {{ old('assigned_staff_id', $appointment->assigned_staff_id) == $staffMember->id ? 'selected' : '' }}>
                                {{ $staffMember->user->name }} - {{ $staffMember->designation }}
                            </option>
                            @endforeach
                        </select>
                        @error('assigned_staff_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Notes -->
            <div class="border-t border-gray-200 pt-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" 
                                name="status"
                                class="input-modern @error('status') border-red-300 @enderror">
                            <option value="pending" {{ old('status', $appointment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status', $appointment->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="in_progress" {{ old('status', $appointment->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $appointment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $appointment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="no_show" {{ old('status', $appointment->status) === 'no_show' ? 'selected' : '' }}>No Show</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="input-modern @error('notes') border-red-300 @enderror">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('appointments.show', $appointment) }}" class="btn-secondary-modern">
                    Cancel
                </a>
                <button type="submit" class="btn-primary-modern">
                    <i class="bi bi-check-circle"></i>
                    Update Appointment
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const packageSelect = document.getElementById('package_id');
    const durationInput = document.getElementById('estimated_duration');

    // Update duration when service is selected
    serviceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.duration) {
            durationInput.value = selectedOption.dataset.duration;
        }
        // Clear package selection when service is selected
        packageSelect.value = '';
    });

    // Update duration when package is selected
    packageSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && selectedOption.dataset.duration) {
            durationInput.value = selectedOption.dataset.duration;
        }
        // Clear service selection when package is selected
        serviceSelect.value = '';
    });
});
</script>
@endpush
@endsection
