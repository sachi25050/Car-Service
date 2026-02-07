@extends('layouts.app')

@section('title', 'Edit Vehicle')
@section('page-title', 'Edit Vehicle')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Vehicle</h1>
            <p class="mt-1 text-sm text-gray-500">Update vehicle information</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn-secondary-modern">
                <i class="bi bi-eye"></i>
                View
            </a>
            <a href="{{ route('vehicles.index') }}" class="btn-secondary-modern">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('vehicles.update', $vehicle) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Customer & Registration -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
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
                            <option value="{{ $customer->id }}" {{ old('customer_id', $vehicle->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->full_name }} - {{ $customer->phone }}
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Registration Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="registration_number" 
                               name="registration_number" 
                               value="{{ old('registration_number', $vehicle->registration_number) }}"
                               required
                               class="input-modern @error('registration_number') border-red-300 @enderror">
                        @error('registration_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Make, Model, Year -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Details</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label for="make" class="block text-sm font-medium text-gray-700 mb-1">
                            Make <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="make" 
                               name="make" 
                               value="{{ old('make', $vehicle->make) }}"
                               required
                               class="input-modern @error('make') border-red-300 @enderror">
                        @error('make')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">
                            Model <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="model" 
                               name="model" 
                               value="{{ old('model', $vehicle->model) }}"
                               required
                               class="input-modern @error('model') border-red-300 @enderror">
                        @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <input type="number" 
                               id="year" 
                               name="year" 
                               value="{{ old('year', $vehicle->year) }}"
                               min="1900" 
                               max="{{ date('Y') + 1 }}"
                               class="input-modern @error('year') border-red-300 @enderror">
                        @error('year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Color, Type, Fuel -->
            <div class="border-t border-gray-200 pt-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="text" 
                               id="color" 
                               name="color" 
                               value="{{ old('color', $vehicle->color) }}"
                               class="input-modern">
                    </div>

                    <div>
                        <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Vehicle Type <span class="text-red-500">*</span>
                        </label>
                        <select id="vehicle_type" 
                                name="vehicle_type" 
                                required
                                class="input-modern @error('vehicle_type') border-red-300 @enderror">
                            <option value="sedan" {{ old('vehicle_type', $vehicle->vehicle_type) === 'sedan' ? 'selected' : '' }}>Sedan</option>
                            <option value="suv" {{ old('vehicle_type', $vehicle->vehicle_type) === 'suv' ? 'selected' : '' }}>SUV</option>
                            <option value="hatchback" {{ old('vehicle_type', $vehicle->vehicle_type) === 'hatchback' ? 'selected' : '' }}>Hatchback</option>
                            <option value="coupe" {{ old('vehicle_type', $vehicle->vehicle_type) === 'coupe' ? 'selected' : '' }}>Coupe</option>
                            <option value="convertible" {{ old('vehicle_type', $vehicle->vehicle_type) === 'convertible' ? 'selected' : '' }}>Convertible</option>
                            <option value="truck" {{ old('vehicle_type', $vehicle->vehicle_type) === 'truck' ? 'selected' : '' }}>Truck</option>
                            <option value="van" {{ old('vehicle_type', $vehicle->vehicle_type) === 'van' ? 'selected' : '' }}>Van</option>
                            <option value="motorcycle" {{ old('vehicle_type', $vehicle->vehicle_type) === 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                            <option value="other" {{ old('vehicle_type', $vehicle->vehicle_type) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('vehicle_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fuel_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Fuel Type <span class="text-red-500">*</span>
                        </label>
                        <select id="fuel_type" 
                                name="fuel_type" 
                                required
                                class="input-modern @error('fuel_type') border-red-300 @enderror">
                            <option value="petrol" {{ old('fuel_type', $vehicle->fuel_type) === 'petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type) === 'diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="electric" {{ old('fuel_type', $vehicle->fuel_type) === 'electric' ? 'selected' : '' }}>Electric</option>
                            <option value="hybrid" {{ old('fuel_type', $vehicle->fuel_type) === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                            <option value="cng" {{ old('fuel_type', $vehicle->fuel_type) === 'cng' ? 'selected' : '' }}>CNG</option>
                            <option value="lpg" {{ old('fuel_type', $vehicle->fuel_type) === 'lpg' ? 'selected' : '' }}>LPG</option>
                        </select>
                        @error('fuel_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Mileage, VIN, Insurance -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label for="mileage" class="block text-sm font-medium text-gray-700 mb-1">Mileage</label>
                        <input type="number" 
                               id="mileage" 
                               name="mileage" 
                               value="{{ old('mileage', $vehicle->mileage) }}"
                               min="0"
                               class="input-modern">
                    </div>

                    <div>
                        <label for="vin_number" class="block text-sm font-medium text-gray-700 mb-1">VIN Number</label>
                        <input type="text" 
                               id="vin_number" 
                               name="vin_number" 
                               value="{{ old('vin_number', $vehicle->vin_number) }}"
                               class="input-modern">
                    </div>

                    <div>
                        <label for="insurance_number" class="block text-sm font-medium text-gray-700 mb-1">Insurance Number</label>
                        <input type="text" 
                               id="insurance_number" 
                               name="insurance_number" 
                               value="{{ old('insurance_number', $vehicle->insurance_number) }}"
                               class="input-modern">
                    </div>
                </div>

                <div class="mt-6">
                    <label for="insurance_expiry" class="block text-sm font-medium text-gray-700 mb-1">Insurance Expiry</label>
                    <input type="date" 
                           id="insurance_expiry" 
                           name="insurance_expiry" 
                           value="{{ old('insurance_expiry', $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('Y-m-d') : '') }}"
                           class="input-modern w-full sm:w-auto">
                    <p class="mt-1 text-xs text-gray-500">System uses dd/mm/yyyy format</p>
                </div>
            </div>

            <!-- Notes -->
            <div class="border-t border-gray-200 pt-6">
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="input-modern">{{ old('notes', $vehicle->notes) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('vehicles.show', $vehicle) }}" class="btn-secondary-modern">
                    Cancel
                </a>
                <button type="submit" class="btn-primary-modern">
                    <i class="bi bi-check-circle"></i>
                    Update Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
