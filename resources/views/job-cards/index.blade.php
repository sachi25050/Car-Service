@extends('layouts.app')

@section('title', 'Job Cards')
@section('page-title', 'Job Cards')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Job Cards</h1>
            <p class="mt-1 text-sm text-gray-500">Manage service orders and job cards</p>
        </div>
        <a href="{{ route('job-cards.create') }}" class="btn-primary-modern">
            <i class="bi bi-plus-circle"></i>
            New Job Card
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('job-cards.index') }}" class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-12 sm:gap-4">
            <div class="sm:col-span-5">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search by job number or customer..."
                       class="input-modern">
            </div>
            <div class="sm:col-span-3">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="input-modern">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="sm:col-span-4 flex items-end gap-2">
                <button type="submit" class="btn-primary-modern flex-1">
                    <i class="bi bi-search"></i>
                    Search
                </button>
                <a href="{{ route('job-cards.index') }}" class="btn-secondary-modern">
                    <i class="bi bi-arrow-clockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Job Cards Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Job Number</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Amount</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobCards as $jobCard)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-clipboard-check text-blue-600"></i>
                                </div>
                                <span class="font-semibold text-gray-900">{{ $jobCard->job_number }}</span>
                            </div>
                        </td>
                        <td>
                            <p class="font-medium text-gray-900">{{ $jobCard->customer->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $jobCard->customer->phone }}</p>
                        </td>
                        <td>
                            <p class="text-gray-900">{{ $jobCard->vehicle->make }} {{ $jobCard->vehicle->model }}</p>
                            <p class="text-xs text-gray-500">{{ $jobCard->vehicle->registration_number }}</p>
                        </td>
                        <td>
                            <x-badge :variant="$jobCard->status === 'completed' ? 'success' : ($jobCard->status === 'in_progress' ? 'info' : ($jobCard->status === 'cancelled' ? 'danger' : 'warning'))">
                                {{ ucfirst(str_replace('_', ' ', $jobCard->status)) }}
                            </x-badge>
                        </td>
                        <td>
                            <x-badge :variant="$jobCard->priority === 'urgent' ? 'danger' : ($jobCard->priority === 'high' ? 'warning' : 'default')">
                                {{ ucfirst($jobCard->priority) }}
                            </x-badge>
                        </td>
                        <td>
                            <span class="font-semibold text-gray-900">₹{{ number_format($jobCard->final_amount, 2) }}</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('job-cards.show', $jobCard) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                   title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('job-cards.edit', $jobCard) }}" 
                                   class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="bi bi-clipboard text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-500">No job cards found</p>
                            <a href="{{ route('job-cards.create') }}" class="mt-4 inline-block text-primary-600 hover:text-primary-700 text-sm font-medium">
                                Create your first job card
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobCards->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $jobCards->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
