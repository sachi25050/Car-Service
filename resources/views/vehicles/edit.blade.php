@extends('layouts.app')

@section('title', 'Edit Vehicle')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-car-front"></i> Edit Vehicle</h1>
    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('vehicles.update', $vehicle) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                    <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $vehicle->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->full_name }} - {{ $customer->phone }}
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="registration_number" class="form-label">Registration Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                           id="registration_number" name="registration_number" value="{{ old('registration_number', $vehicle->registration_number) }}" required>
                    @error('registration_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="make" class="form-label">Make <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('make') is-invalid @enderror" 
                           id="make" name="make" value="{{ old('make', $vehicle->make) }}" required>
                    @error('make')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                           id="model" name="model" value="{{ old('model', $vehicle->model) }}" required>
                    @error('model')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                           id="year" name="year" value="{{ old('year', $vehicle->year) }}" min="1900" max="{{ date('Y') + 1 }}">
                    @error('year')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" class="form-control" id="color" name="color" value="{{ old('color', $vehicle->color) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="vehicle_type" class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" required>
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
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="fuel_type" class="form-label">Fuel Type <span class="text-danger">*</span></label>
                    <select class="form-select @error('fuel_type') is-invalid @enderror" id="fuel_type" name="fuel_type" required>
                        <option value="petrol" {{ old('fuel_type', $vehicle->fuel_type) === 'petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type) === 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="electric" {{ old('fuel_type', $vehicle->fuel_type) === 'electric' ? 'selected' : '' }}>Electric</option>
                        <option value="hybrid" {{ old('fuel_type', $vehicle->fuel_type) === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="cng" {{ old('fuel_type', $vehicle->fuel_type) === 'cng' ? 'selected' : '' }}>CNG</option>
                        <option value="lpg" {{ old('fuel_type', $vehicle->fuel_type) === 'lpg' ? 'selected' : '' }}>LPG</option>
                    </select>
                    @error('fuel_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="mileage" class="form-label">Mileage</label>
                    <input type="number" class="form-control" id="mileage" name="mileage" value="{{ old('mileage', $vehicle->mileage) }}" min="0">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="vin_number" class="form-label">VIN Number</label>
                    <input type="text" class="form-control" id="vin_number" name="vin_number" value="{{ old('vin_number', $vehicle->vin_number) }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label for="insurance_number" class="form-label">Insurance Number</label>
                    <input type="text" class="form-control" id="insurance_number" name="insurance_number" value="{{ old('insurance_number', $vehicle->insurance_number) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="insurance_expiry" class="form-label">Insurance Expiry</label>
                    <input type="date" class="form-control" id="insurance_expiry" name="insurance_expiry" 
                           value="{{ old('insurance_expiry', $vehicle->insurance_expiry?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $vehicle->notes) }}</textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Vehicle</button>
            </div>
        </form>
    </div>
</div>
@endsection
