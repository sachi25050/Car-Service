@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your customer database</p>
        </div>
        <a href="{{ route('customers.create') }}" class="btn-primary-modern">
            <i class="bi bi-plus-circle"></i>
            Add Customer
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('customers.index') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-12 sm:gap-4">
            <div class="sm:col-span-5">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, email, or phone..."
                       class="input-modern">
            </div>
            <div class="sm:col-span-3">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="input-modern">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="sm:col-span-4 flex items-end gap-2">
                <button type="submit" class="btn-primary-modern flex-1">
                    <i class="bi bi-search"></i>
                    Search
                </button>
                <a href="{{ route('customers.index') }}" class="btn-secondary-modern">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Vehicles</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-primary-700 font-medium text-sm">{{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $customer->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $customer->city ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-gray-900">{{ $customer->email ?? 'N/A' }}</span>
                        </td>
                        <td>
                            <span class="text-gray-900">{{ $customer->phone }}</span>
                        </td>
                        <td>
                            <span class="text-gray-900">{{ $customer->vehicles->count() }}</span>
                        </td>
                        <td>
                            <x-badge :variant="$customer->is_active ? 'success' : 'default'">
                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                            </x-badge>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('customers.show', $customer) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" 
                                   class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="bi bi-people text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-500">No customers found</p>
                            <a href="{{ route('customers.create') }}" class="mt-4 inline-block text-primary-600 hover:text-primary-700 text-sm font-medium">
                                Add your first customer
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $customers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
