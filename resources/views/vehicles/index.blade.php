@extends('layouts.app')

@section('title', 'Vehicles')
@section('page-title', 'Vehicles')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vehicles</h1>
            <p class="mt-1 text-sm text-gray-500">Manage vehicle database</p>
        </div>
        <a href="{{ route('vehicles.create') }}" class="btn-primary-modern">
            <i class="bi bi-plus-circle"></i>
            Add Vehicle
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('vehicles.index') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-12 sm:gap-4">
            <div class="sm:col-span-5">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search by registration, make, model, or customer..."
                       class="input-modern">
            </div>
            <div class="sm:col-span-3">
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                <select name="customer_id" id="customer_id" class="input-modern">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->full_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-4 flex items-end gap-2">
                <button type="submit" class="btn-primary-modern flex-1">
                    <i class="bi bi-search"></i>
                    Search
                </button>
                <a href="{{ route('vehicles.index') }}" class="btn-secondary-modern">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Vehicles Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Registration #</th>
                        <th>Make & Model</th>
                        <th>Year</th>
                        <th>Color</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                    <tr>
                        <td>
                            <strong class="text-gray-900">{{ $vehicle->registration_number }}</strong>
                        </td>
                        <td>
                            <div class="text-sm text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</div>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">{{ $vehicle->year ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600">{{ $vehicle->color ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="badge-modern badge-secondary">{{ ucfirst($vehicle->vehicle_type ?? 'N/A') }}</span>
                        </td>
                        <td>
                            @if($vehicle->customer)
                            <a href="{{ route('customers.show', $vehicle->customer) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                {{ $vehicle->customer->full_name }}
                            </a>
                            @else
                            <span class="text-sm text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('vehicles.show', $vehicle) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors duration-200"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('vehicles.edit', $vehicle) }}" 
                                   class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded-lg transition-colors duration-200"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('vehicles.destroy', $vehicle) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center">
                                <i class="bi bi-car-front text-4xl text-gray-400 mb-3"></i>
                                <p class="text-gray-500 font-medium">No vehicles found</p>
                                <p class="text-sm text-gray-400 mt-1">Get started by adding a new vehicle</p>
                                <a href="{{ route('vehicles.create') }}" class="btn-primary-modern mt-4">
                                    <i class="bi bi-plus-circle"></i>
                                    Add Vehicle
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vehicles->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $vehicles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
