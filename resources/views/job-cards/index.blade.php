@extends('layouts.app')

@section('title', 'Job Cards')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-clipboard-check"></i> Job Cards</h1>
    <a href="{{ route('job-cards.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Job Card
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('job-cards.index') }}" class="mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by job number or customer..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">Search</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('job-cards.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Job Number</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobCards as $jobCard)
                    <tr>
                        <td><strong>{{ $jobCard->job_number }}</strong></td>
                        <td>{{ $jobCard->customer->full_name }}</td>
                        <td>{{ $jobCard->vehicle->make }} {{ $jobCard->vehicle->model }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $jobCard->status === 'completed' ? 'success' : 
                                ($jobCard->status === 'cancelled' ? 'danger' : 
                                ($jobCard->status === 'in_progress' ? 'primary' : 'warning')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $jobCard->status)) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ 
                                $jobCard->priority === 'urgent' ? 'danger' : 
                                ($jobCard->priority === 'high' ? 'warning' : 'secondary') 
                            }}">
                                {{ ucfirst($jobCard->priority) }}
                            </span>
                        </td>
                        <td>₹{{ number_format($jobCard->final_amount, 2) }}</td>
                        <td>
                            <a href="{{ route('job-cards.show', $jobCard) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('job-cards.edit', $jobCard) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No job cards found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $jobCards->links() }}
        </div>
    </div>
</div>
@endsection
