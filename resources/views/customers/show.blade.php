@extends('layouts.app')

@section('title', 'Customer Details')
@section('page-title', 'Customer Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $customer->full_name }}</h1>
            <p class="mt-1 text-sm text-gray-500">Customer profile and information</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('customers.edit', $customer) }}" class="btn-secondary-modern">
                <i class="bi bi-pencil"></i>
                Edit
            </a>
            <a href="{{ route('customers.index') }}" class="btn-secondary-modern">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $customer->full_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $customer->email ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $customer->phone }}</dd>
                    </div>
                    @if($customer->alternate_phone)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Alternate Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $customer->alternate_phone }}</dd>
                    </div>
                    @endif
                    @if($customer->date_of_birth)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $customer->date_of_birth->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                    @if($customer->gender)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($customer->gender) }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <x-badge :variant="$customer->is_active ? 'success' : 'default'">
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </x-badge>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Address Information -->
            @if($customer->address || $customer->city)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Address Information</h3>
                <dl class="space-y-3">
                    @if($customer->address)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $customer->address }}</dd>
                    </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4">
                        @if($customer->city)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">City</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->city }}</dd>
                        </div>
                        @endif
                        @if($customer->state)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">State</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->state }}</dd>
                        </div>
                        @endif
                        @if($customer->zip_code)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Zip Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $customer->zip_code }}</dd>
                        </div>
                        @endif
                    </div>
                </dl>
            </div>
            @endif

            @if($customer->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $customer->notes }}</p>
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
                        <p class="text-sm text-gray-500">Total Vehicles</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customer->vehicles->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Appointments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customer->appointments->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Job Cards</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $customer->jobCards->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Vehicles -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Vehicles</h3>
                    <a href="{{ route('vehicles.create', ['customer_id' => $customer->id]) }}" 
                       class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        <i class="bi bi-plus-circle"></i> Add
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($customer->vehicles as $vehicle)
                    <a href="{{ route('vehicles.show', $vehicle) }}" 
                       class="block p-3 border border-gray-200 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors">
                        <p class="font-medium text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $vehicle->registration_number }}</p>
                    </a>
                    @empty
                    <p class="text-sm text-gray-500 text-center py-4">No vehicles</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
