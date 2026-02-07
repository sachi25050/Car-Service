@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-speedometer2"></i> Dashboard</h1>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Total Customers</h6>
                        <h2 class="mb-0">{{ $stats['total_customers'] }}</h2>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Today's Appointments</h6>
                        <h2 class="mb-0">{{ $stats['today_appointments'] }}</h2>
                    </div>
                    <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">In Progress Jobs</h6>
                        <h2 class="mb-0">{{ $stats['in_progress_jobs'] }}</h2>
                    </div>
                    <i class="bi bi-clipboard-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-subtitle mb-2">Today's Revenue</h6>
                        <h2 class="mb-0">₹{{ number_format($stats['today_revenue'], 2) }}</h2>
                    </div>
                    <i class="bi bi-currency-rupee fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Upcoming Appointments</h5>
            </div>
            <div class="card-body">
                @if($upcoming_appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcoming_appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}<br>
                                    <small class="text-muted">{{ $appointment->appointment_time }}</small>
                                </td>
                                <td>{{ $appointment->customer->full_name }}</td>
                                <td>{{ $appointment->service->name ?? $appointment->package->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center">No upcoming appointments</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> Recent Job Cards</h5>
            </div>
            <div class="card-body">
                @if($recent_job_cards->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Job #</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_job_cards as $jobCard)
                            <tr>
                                <td><a href="{{ route('job-cards.show', $jobCard) }}">{{ $jobCard->job_number }}</a></td>
                                <td>{{ $jobCard->customer->full_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $jobCard->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($jobCard->status) }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($jobCard->final_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-muted text-center">No recent job cards</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
