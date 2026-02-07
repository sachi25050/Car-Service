@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-person"></i> User Details</h1>
    <div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Name:</strong><br>
                        <span class="fs-5">{{ $user->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Email:</strong><br>
                        {{ $user->email }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Phone:</strong><br>
                        {{ $user->phone ?? 'N/A' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Role:</strong><br>
                        @if($user->role)
                        <span class="badge bg-info fs-6">{{ $user->role->display_name }}</span>
                        @else
                        <span class="badge bg-secondary">No Role</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} fs-6">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Created At:</strong><br>
                        {{ $user->created_at->format('d/m/Y h:i A') }}
                    </div>
                </div>
            </div>
        </div>

        @if($user->staff)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Staff Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Employee ID:</strong> {{ $user->staff->employee_id }}</p>
                <p><strong>Designation:</strong> {{ $user->staff->designation }}</p>
                <p><strong>Department:</strong> {{ $user->staff->department ?? 'N/A' }}</p>
                @if($user->staff->hire_date)
                <p><strong>Hire Date:</strong> {{ $user->staff->hire_date->format('d/m/Y') }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
