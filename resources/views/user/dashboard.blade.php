@extends('layouts.dashboard')

@section('page-title', 'My Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Progress and Status Cards Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Onboarding Progress</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Overall Completion</span>
                            <span>{{ round($completionPercentage) }}%</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar {{ $completionPercentage < 100 ? 'bg-info' : 'bg-success' }}"
                                 role="progressbar"
                                 style="width: {{ $completionPercentage . '%' }};"
                                 aria-valuenow="{{ $completionPercentage }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                {{ round($completionPercentage) }}%
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Company Information</span>
                                    <span class="badge {{ $completionStatus['company'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $completionStatus['company'] ? 'Complete' : 'Incomplete' }}
                                    </span>
                                </div>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar {{ $completionStatus['company'] ? 'bg-success' : 'bg-danger' }}"
                                         role="progressbar"
                                         style="width: {{ $completionStatus['company'] ? '100' : '0' }}%;">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Employees ({{ $counts['employees'] }})</span>
                                    <span class="badge {{ $completionStatus['employees'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $completionStatus['employees'] ? 'Complete' : 'Incomplete' }}
                                    </span>
                                </div>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar {{ $completionStatus['employees'] ? 'bg-success' : 'bg-danger' }}"
                                         role="progressbar"
                                         style="width: {{ $completionStatus['employees'] ? '100' : '0' }}%;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Devices ({{ $counts['devices'] }})</span>
                                    <span class="badge {{ $completionStatus['devices'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $completionStatus['devices'] ? 'Complete' : 'Incomplete' }}
                                    </span>
                                </div>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar {{ $completionStatus['devices'] ? 'bg-success' : 'bg-danger' }}"
                                         role="progressbar"
                                         style="width: {{ $completionStatus['devices'] ? '100' : '0' }}%;">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Software Licenses ({{ $counts['licenses'] }})</span>
                                    <span class="badge {{ $completionStatus['licenses'] ? 'bg-success' : 'bg-danger' }}">
                                        {{ $completionStatus['licenses'] ? 'Complete' : 'Incomplete' }}
                                    </span>
                                </div>
                                <div class="progress mt-1" style="height: 8px;">
                                    <div class="progress-bar {{ $completionStatus['licenses'] ? 'bg-success' : 'bg-danger' }}"
                                         role="progressbar"
                                         style="width: {{ $completionStatus['licenses'] ? '100' : '0' }}%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="{{ route('onboarding') }}" class="btn btn-primary">
                        <i class="fas fa-clipboard-list me-2"></i> Go to Onboarding Form
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Submission Status</h5>
                </div>
                <div class="card-body">
                    @if($status == 'approved')
                        <div class="text-center mb-3">
                            <i class="fas fa-check-circle text-success fa-5x mb-3"></i>
                            <h4 class="text-success">Approved</h4>
                            <p class="text-muted">Your submission has been approved!</p>
                        </div>
                    @elseif($status == 'rejected')
                        <div class="text-center mb-3">
                            <i class="fas fa-times-circle text-danger fa-5x mb-3"></i>
                            <h4 class="text-danger">Rejected</h4>
                            <p class="text-muted">Please review feedback and resubmit.</p>
                        </div>
                    @else
                        <div class="text-center mb-3">
                            <i class="fas fa-clock text-warning fa-5x mb-3"></i>
                            <h4 class="text-warning">Pending Review</h4>
                            <p class="text-muted">Your submission is being reviewed.</p>
                        </div>
                    @endif

                    @if($feedback)
                        <div class="mt-3">
                            <h6 class="border-bottom pb-2">Admin Feedback:</h6>
                            <p class="mb-0">{{ $feedback }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards Row -->
    <div class="row">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-3x text-primary mb-3"></i>
                    <h5>Company Information</h5>
                    <p class="text-muted mb-3">Manage your company details and branches</p>
                    <a href="{{ route('onboarding') }}" class="btn btn-sm btn-outline-primary">
                        {{ $completionStatus['company'] ? 'Edit' : 'Complete' }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h5>Employees</h5>
                    <p class="text-muted mb-3">Manage your employee information and contacts</p>
                    <a href="{{ route('onboarding.contact_information') }}" class="btn btn-sm btn-outline-success">
                        {{ $completionStatus['employees'] ? 'Edit' : 'Complete' }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-laptop fa-3x text-info mb-3"></i>
                    <h5>Devices</h5>
                    <p class="text-muted mb-3">Manage your device inventory and credentials</p>
                    <a href="{{ route('onboarding.physical_devices') }}" class="btn btn-sm btn-outline-info">
                        {{ $completionStatus['devices'] ? 'Edit' : 'Complete' }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-key fa-3x text-warning mb-3"></i>
                    <h5>Software Licenses</h5>
                    <p class="text-muted mb-3">Manage your software licenses and subscriptions</p>
                    <a href="{{ route('onboarding.software_licenses') }}" class="btn btn-sm btn-outline-warning">
                        {{ $completionStatus['licenses'] ? 'Edit' : 'Complete' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
