@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-calendar-pencil"></i> Edit Appointment</h1>
    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('appointments.update', $appointment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $appointment->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->full_name }} - {{ $customer->phone }}
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="vehicle_id" class="form-label">Vehicle <span class="text-danger">*</span></label>
                    <select class="form-select @error('vehicle_id') is-invalid @enderror" id="vehicle_id" name="vehicle_id" required>
                        <option value="">Select Vehicle</option>
                        @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $appointment->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                            {{ $vehicle->make }} {{ $vehicle->model }} ({{ $vehicle->registration_number }})
                        </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_id" class="form-label">Service</label>
                    <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id">
                        <option value="">Select Service</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" data-duration="{{ $service->duration_minutes }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} ({{ $service->duration_minutes }} min) - ₹{{ number_format($service->base_price, 2) }}
                        </option>
                        @endforeach
                    </select>
                    @error('service_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="package_id" class="form-label">Service Package</label>
                    <select class="form-select @error('package_id') is-invalid @enderror" id="package_id" name="package_id">
                        <option value="">Select Package</option>
                        @foreach($packages as $package)
                        <option value="{{ $package->id }}" data-duration="{{ $package->duration_minutes }}" {{ old('package_id', $appointment->package_id) == $package->id ? 'selected' : '' }}>
                            {{ $package->name }} ({{ $package->duration_minutes }} min) - ₹{{ number_format($package->price, 2) }}
                        </option>
                        @endforeach
                    </select>
                    @error('package_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                           id="appointment_date" name="appointment_date" 
                           value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}" 
                           min="{{ date('Y-m-d') }}" required>
                    @error('appointment_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                    <input type="time" class="form-control @error('appointment_time') is-invalid @enderror" 
                           id="appointment_time" name="appointment_time" 
                           value="{{ old('appointment_time', $appointment->appointment_time) }}" required>
                    @error('appointment_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="estimated_duration" class="form-label">Estimated Duration (minutes)</label>
                    <input type="number" class="form-control @error('estimated_duration') is-invalid @enderror" 
                           id="estimated_duration" name="estimated_duration" 
                           value="{{ old('estimated_duration', $appointment->estimated_duration) }}" min="15" step="15">
                    @error('estimated_duration')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="assigned_staff_id" class="form-label">Assign Staff</label>
                    <select class="form-select @error('assigned_staff_id') is-invalid @enderror" id="assigned_staff_id" name="assigned_staff_id">
                        <option value="">No Assignment</option>
                        @foreach($staff as $staffMember)
                        <option value="{{ $staffMember->id }}" {{ old('assigned_staff_id', $appointment->assigned_staff_id) == $staffMember->id ? 'selected' : '' }}>
                            {{ $staffMember->user->name }} - {{ $staffMember->designation }}
                        </option>
                        @endforeach
                    </select>
                    @error('assigned_staff_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                    <option value="pending" {{ old('status', $appointment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ old('status', $appointment->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="in_progress" {{ old('status', $appointment->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status', $appointment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $appointment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="no_show" {{ old('status', $appointment->status) === 'no_show' ? 'selected' : '' }}>No Show</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Appointment</button>
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
