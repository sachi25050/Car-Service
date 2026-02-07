@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-car-front"></i> Vehicles</h1>
    <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add Vehicle
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('vehicles.index') }}" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by registration, make, model, or customer..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Search</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Registration #</th>
                        <th>Make & Model</th>
                        <th>Year</th>
                        <th>Color</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                    <tr>
                        <td><strong>{{ $vehicle->registration_number }}</strong></td>
                        <td>{{ $vehicle->make }} {{ $vehicle->model }}</td>
                        <td>{{ $vehicle->year ?? 'N/A' }}</td>
                        <td>{{ $vehicle->color ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($vehicle->vehicle_type) }}</span>
                        </td>
                        <td>{{ $vehicle->customer->full_name }}</td>
                        <td>
                            <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('vehicles.destroy', $vehicle) }}" method="POST" 
                                  class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No vehicles found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $vehicles->links() }}
        </div>
    </div>
</div>
@endsection
