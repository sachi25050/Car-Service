@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card 
            title="Total Customers" 
            :value="number_format($stats['total_customers'])" 
            icon="people"
            color="blue" />
        
        <x-stat-card 
            title="Today's Appointments" 
            :value="number_format($stats['today_appointments'])" 
            icon="calendar-check"
            color="green" />
        
        <x-stat-card 
            title="In Progress Jobs" 
            :value="number_format($stats['in_progress_jobs'])" 
            icon="clipboard-check"
            color="yellow" />
        
        <x-stat-card 
            title="Today's Revenue" 
            :value="'₹' . number_format($stats['today_revenue'], 2)" 
            icon="currency-rupee"
            color="primary" />
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Vehicles</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_vehicles']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-car-front text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Appointments</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['pending_appointments']) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-clock-history text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Month Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($stats['month_revenue'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="bi bi-graph-up-arrow text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-primary-600"></i>
                        Upcoming Appointments
                    </h3>
                    <a href="{{ route('appointments.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        View All
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($upcoming_appointments as $appointment)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-calendar3 text-primary-600"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $appointment->customer->full_name }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-0.5">
                                        {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $appointment->service->name ?? $appointment->package->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4">
                            <x-badge :variant="$appointment->status === 'confirmed' ? 'success' : 'warning'">
                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </x-badge>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <i class="bi bi-calendar-x text-4xl text-gray-300 mb-3"></i>
                    <p class="text-sm text-gray-500">No upcoming appointments</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Job Cards -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-clipboard-data text-primary-600"></i>
                        Recent Job Cards
                    </h3>
                    <a href="{{ route('job-cards.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">
                        View All
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_job_cards as $jobCard)
                <a href="{{ route('job-cards.show', $jobCard) }}" 
                   class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="bi bi-clipboard-check text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $jobCard->job_number }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-0.5">
                                        {{ $jobCard->customer->full_name }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $jobCard->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 text-right">
                            <p class="text-sm font-semibold text-gray-900">₹{{ number_format($jobCard->final_amount, 2) }}</p>
                            <x-badge :variant="$jobCard->status === 'completed' ? 'success' : ($jobCard->status === 'in_progress' ? 'info' : 'warning')" class="mt-1">
                                {{ ucfirst(str_replace('_', ' ', $jobCard->status)) }}
                            </x-badge>
                        </div>
                    </div>
                </a>
                @empty
                <div class="px-6 py-12 text-center">
                    <i class="bi bi-clipboard text-4xl text-gray-300 mb-3"></i>
                    <p class="text-sm text-gray-500">No recent job cards</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <a href="{{ route('customers.create') }}" 
               class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors group">
                <i class="bi bi-person-plus text-2xl text-gray-400 group-hover:text-primary-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">New Customer</span>
            </a>
            <a href="{{ route('appointments.create') }}" 
               class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors group">
                <i class="bi bi-calendar-plus text-2xl text-gray-400 group-hover:text-primary-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">New Appointment</span>
            </a>
            <a href="{{ route('job-cards.create') }}" 
               class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors group">
                <i class="bi bi-clipboard-plus text-2xl text-gray-400 group-hover:text-primary-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">New Job Card</span>
            </a>
            <a href="{{ route('reports.index') }}" 
               class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary-500 hover:bg-primary-50 transition-colors group">
                <i class="bi bi-graph-up text-2xl text-gray-400 group-hover:text-primary-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700">View Reports</span>
            </a>
        </div>
    </div>
</div>
@endsection
