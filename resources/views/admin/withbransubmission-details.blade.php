@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        Submission Details - {{ $company ? $company->company_name : $user->email }}
                    </h4>
                    <div>
                        @if($company && $company->approval_status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($company && $company->approval_status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif

                        <a href="{{ route('admin.submissions') }}" class="btn btn-light btn-sm ms-2">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Review submission form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    Review Submission
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.submission.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-select" id="status" name="status" required>
                                                        <option value="pending" {{ $company && $company->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="approved" {{ $company && $company->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="rejected" {{ $company && $company->approval_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="mb-3">
                                                    <label for="feedback" class="form-label">Feedback</label>
                                                    <textarea class="form-control" id="feedback" name="feedback" rows="2">{{ $company ? $company->admin_feedback : '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update Status
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nav tabs for different sections -->
                    <ul class="nav nav-tabs" id="submissionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="company-tab" data-bs-toggle="tab" data-bs-target="#company" type="button" role="tab" aria-controls="company" aria-selected="true">
                                Company Info
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="branches-tab" data-bs-toggle="tab" data-bs-target="#branches" type="button" role="tab" aria-controls="branches" aria-selected="false">
                                Branches ({{ count($branches) }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees" type="button" role="tab" aria-controls="employees" aria-selected="false">
                                Employees ({{ count($employees) }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="devices-tab" data-bs-toggle="tab" data-bs-target="#devices" type="button" role="tab" aria-controls="devices" aria-selected="false">
                                Devices ({{ count($devices) }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="licenses-tab" data-bs-toggle="tab" data-bs-target="#licenses" type="button" role="tab" aria-controls="licenses" aria-selected="false">
                                Licenses ({{ count($licenses) }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="webpage-tab" data-bs-toggle="tab" data-bs-target="#webpage" type="button" role="tab" aria-controls="webpage" aria-selected="false">
                                Webpage & Credentials
                            </button>
                        </li>
                    </ul>
                    <input type="hidden" id="current-user-id" value="{{ $user->id }}">

                    <!-- Tab content -->
                    <div class="tab-content" id="submissionTabsContent">
                        <!-- Company Information Tab -->
                        <!-- Company Information Tab -->
                        <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                            <div class="p-3">
                                @if($company)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Company Name - Editable -->
                                            <div class="mb-3 editable-field position-relative">
                                                <label class="form-label fw-bold">Company Name</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="view-mode">{{ $company->company_name }}</span>
                                                    <input type="text"
                                                        class="form-control edit-mode"
                                                        value="{{ $company->company_name }}"
                                                        style="display:none;">
                                                    <button class="btn btn-link edit-btn ms-2">
                                                        <i class="fas fa-pencil-alt text-muted"></i>
                                                    </button>
                                                    <button class="btn btn-link save-btn ms-2"
                                                            data-field="company_name"
                                                            style="display:none;">
                                                        <i class="fas fa-save text-success"></i>
                                                    </button>
                                                    <button class="btn btn-link cancel-btn ms-2"
                                                            style="display:none;">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Primary Number - Editable -->
                                            <div class="mb-3 editable-field position-relative">
                                                <label class="form-label fw-bold">Primary Number</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="view-mode">{{ $company->primary_number }}</span>
                                                    <input type="text"
                                                        class="form-control edit-mode"
                                                        value="{{ $company->primary_number }}"
                                                        style="display:none;">
                                                    <button class="btn btn-link edit-btn ms-2">
                                                        <i class="fas fa-pencil-alt text-muted"></i>
                                                    </button>
                                                    <button class="btn btn-link save-btn ms-2"
                                                            data-field="primary_number"
                                                            style="display:none;">
                                                        <i class="fas fa-save text-success"></i>
                                                    </button>
                                                    <button class="btn btn-link cancel-btn ms-2"
                                                            style="display:none;">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Secondary Number - Editable -->
                                            <div class="mb-3 editable-field position-relative">
                                                <label class="form-label fw-bold">Secondary Number</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="view-mode">{{ $company->secondary_number ?: 'N/A' }}</span>
                                                    <input type="text"
                                                        class="form-control edit-mode"
                                                        value="{{ $company->secondary_number }}"
                                                        style="display:none;">
                                                    <button class="btn btn-link edit-btn ms-2">
                                                        <i class="fas fa-pencil-alt text-muted"></i>
                                                    </button>
                                                    <button class="btn btn-link save-btn ms-2"
                                                            data-field="secondary_number"
                                                            style="display:none;">
                                                        <i class="fas fa-save text-success"></i>
                                                    </button>
                                                    <button class="btn btn-link cancel-btn ms-2"
                                                            style="display:none;">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- HQ Address - Editable -->
                                            <div class="mb-3 editable-field position-relative">
                                                <label class="form-label fw-bold">HQ Address</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="view-mode">{{ $company->hq_address }}</span>
                                                    <input type="text"
                                                        class="form-control edit-mode"
                                                        value="{{ $company->hq_address }}"
                                                        style="display:none;">
                                                    <button class="btn btn-link edit-btn ms-2">
                                                        <i class="fas fa-pencil-alt text-muted"></i>
                                                    </button>
                                                    <button class="btn btn-link save-btn ms-2"
                                                            data-field="hq_address"
                                                            style="display:none;">
                                                        <i class="fas fa-save text-success"></i>
                                                    </button>
                                                    <button class="btn btn-link cancel-btn ms-2"
                                                            style="display:none;">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- HQ Phone - Editable -->
                                            <div class="mb-3 editable-field position-relative">
                                                <label class="form-label fw-bold">HQ Phone</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="view-mode">{{ $company->hq_phone }}</span>
                                                    <input type="text"
                                                        class="form-control edit-mode"
                                                        value="{{ $company->hq_phone }}"
                                                        style="display:none;">
                                                    <button class="btn btn-link edit-btn ms-2">
                                                        <i class="fas fa-pencil-alt text-muted"></i>
                                                    </button>
                                                    <button class="btn btn-link save-btn ms-2"
                                                            data-field="hq_phone"
                                                            style="display:none;">
                                                        <i class="fas fa-save text-success"></i>
                                                    </button>
                                                    <button class="btn btn-link cancel-btn ms-2"
                                                            style="display:none;">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Fax - Editable -->
                                            <div class="mb-3 editable-field position-relative">
                                                <label class="form-label fw-bold">Fax</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="view-mode">{{ $company->hq_fax ?: 'N/A' }}</span>
                                                    <input type="text"
                                                        class="form-control edit-mode"
                                                        value="{{ $company->hq_fax }}"
                                                        style="display:none;">
                                                    <button class="btn btn-link edit-btn ms-2">
                                                        <i class="fas fa-pencil-alt text-muted"></i>
                                                    </button>
                                                    <button class="btn btn-link save-btn ms-2"
                                                            data-field="hq_fax"
                                                            style="display:none;">
                                                        <i class="fas fa-save text-success"></i>
                                                    </button>
                                                    <button class="btn btn-link cancel-btn ms-2"
                                                            style="display:none;">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Website - Editable -->
                                            <div class="mb-3 editable-field position-relative">
                                                <label class="form-label fw-bold">Website</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="view-mode">{{ $company->hq_website ?: 'N/A' }}</span>
                                                    <input type="text"
                                                        class="form-control edit-mode"
                                                        value="{{ $company->hq_website }}"
                                                        style="display:none;">
                                                    <button class="btn btn-link edit-btn ms-2">
                                                        <i class="fas fa-pencil-alt text-muted"></i>
                                                    </button>
                                                    <button class="btn btn-link save-btn ms-2"
                                                            data-field="hq_website"
                                                            style="display:none;">
                                                        <i class="fas fa-save text-success"></i>
                                                    </button>
                                                    <button class="btn btn-link cancel-btn ms-2"
                                                            style="display:none;">
                                                        <i class="fas fa-times text-danger"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Office Hours - Editable -->
                                            <div class="mb-3 editable-field position-relative">
                                                <label class="form-label fw-bold">Office Hours</label>
                                                <div class="d-flex align-items-center">
                                                    <span class="view-mode">{{ $company->hq_opening_time }} - {{ $company->hq_closing_time }}</span>
                                                    <div class="input-group edit-mode" style="display:none;">
                                                        <input type="text"
                                                            class="form-control"
                                                            value="{{ $company->hq_opening_time }}"
                                                            placeholder="Opening Time">
                                                        <input type="text"
                                                            class="form-control"
                                                            value="{{ $company->hq_closing_time }}"
                                                            placeholder="Closing Time">
                                                        <button class="btn btn-link edit-btn ms-2">
                                                            <i class="fas fa-pencil-alt text-muted"></i>
                                                        </button>
                                                        <button class="btn btn-link save-btn ms-2"
                                                                data-field="office_hours"
                                                                style="display:none;">
                                                            <i class="fas fa-save text-success"></i>
                                                        </button>
                                                        <button class="btn btn-link cancel-btn ms-2"
                                                                style="display:none;">
                                                            <i class="fas fa-times text-danger"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        No company information has been submitted yet.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Branches Tab -->
                        <!-- <div class="tab-pane fade" id="branches" role="tabpanel" aria-labelledby="branches-tab">
                            <div class="p-3">
                                @if(count($branches) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Address</th>
                                                    <th>Phone Number</th>
                                                    <th>Fax</th>
                                                    <th>Website</th>
                                                    <th>Hours</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($branches as $branch)
                                                    <tr>
                                                        <td>{{ $branch->branch_address }}</td>
                                                        <td>{{ $branch->phone_number }}</td>
                                                        <td>{{ $branch->fax ?: 'N/A' }}</td>
                                                        <td>{{ $branch->website ?: 'N/A' }}</td>
                                                        <td>{{ $branch->opening_time }} - {{ $branch->closing_time }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        No branch information has been submitted yet.
                                    </div>
                                @endif
                            </div>
                        </div> -->
                           <!-- Branches Tab -->
                        <div class="tab-pane fade" id="branches" role="tabpanel" aria-labelledby="branches-tab">
                            <div class="p-3">
                                @if(count($branches) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Address</th>
                                                    <th>Phone Number</th>
                                                    <th>Fax</th>
                                                    <th>Website</th>
                                                    <th>Opening Time</th>
                                                    <th>Closing Time</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($branches as $branch)
                                                    <tr data-branch-id="{{ $branch->id }}" class="branch-row">
                                                        <td>
                                                            <span class="view-mode">{{ $branch->branch_address }}</span>
                                                            <input type="text"
                                                                class="form-control edit-mode"
                                                                value="{{ $branch->branch_address }}"
                                                                name="branch_address"
                                                                style="display:none;">
                                                        </td>
                                                        <td>
                                                            <span class="view-mode">{{ $branch->phone_number }}</span>
                                                            <input type="text"
                                                                class="form-control edit-mode"
                                                                value="{{ $branch->phone_number }}"
                                                                name="phone_number"
                                                                style="display:none;">
                                                        </td>
                                                        <td>
                                                            <span class="view-mode">{{ $branch->fax ?: 'N/A' }}</span>
                                                            <input type="text"
                                                                class="form-control edit-mode"
                                                                value="{{ $branch->fax }}"
                                                                name="fax"
                                                                style="display:none;">
                                                        </td>
                                                        <td>
                                                            <span class="view-mode">{{ $branch->website ?: 'N/A' }}</span>
                                                            <input type="text"
                                                                class="form-control edit-mode"
                                                                value="{{ $branch->website }}"
                                                                name="website"
                                                                style="display:none;">
                                                        </td>
                                                        <td>
                                                            <span class="view-mode">{{ $branch->opening_time }}</span>
                                                            <input type="text"
                                                                class="form-control edit-mode"
                                                                value="{{ $branch->opening_time }}"
                                                                name="opening_time"
                                                                style="display:none;">
                                                        </td>
                                                        <td>
                                                            <span class="view-mode">{{ $branch->closing_time }}</span>
                                                            <input type="text"
                                                                class="form-control edit-mode"
                                                                value="{{ $branch->closing_time }}"
                                                                name="closing_time"
                                                                style="display:none;">
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-outline-primary edit-branch-btn">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <div class="edit-controls" style="display:none;">
                                                                <button class="btn btn-sm btn-success save-branch-btn">
                                                                    <i class="fas fa-save"></i> Save
                                                                </button>
                                                                <button class="btn btn-sm btn-secondary cancel-branch-btn">
                                                                    <i class="fas fa-times"></i> Cancel
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        No branch information has been submitted yet.
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- Employees Tab -->
                        <div class="tab-pane fade" id="employees" role="tabpanel" aria-labelledby="employees-tab">
                            <div class="p-3">
                                @if(count($employees) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Title</th>
                                                    <th>Email</th>
                                                    <th>Mobile Number</th>
                                                    <th>Work Phone</th>
                                                    <th>Location</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($employees as $employee)
                                                    <tr>
                                                        <td>{{ $employee->firstname }} {{ $employee->lastname }}</td>
                                                        <td>{{ $employee->employee_title }}</td>
                                                        <td>{{ $employee->employee_email }}</td>
                                                        <td>{{ $employee->employee_phone_number }}</td>
                                                        <td>{{ $employee->employee_working_number }}</td>
                                                        <td>{{ $employee->employee_working_location }}</td>
                                                        <td>{{ $employee->notes ?: 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if(count($employee_attachments) > 0)
                                        <div class="mt-4">
                                            <h5>Employee Attachments</h5>
                                            <div class="list-group">
                                                @foreach($employee_attachments as $attachment)
                                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="list-group-item list-group-item-action">
                                                        <i class="fas fa-file me-2"></i>
                                                        {{ basename($attachment) }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">
                                        No employee information has been submitted yet.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Devices Tab -->
                        <div class="tab-pane fade" id="devices" role="tabpanel" aria-labelledby="devices-tab">
                            <div class="p-3">
                                @if(count($devices) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Name</th>
                                                    <th>Username</th>
                                                    <th>IP Address</th>
                                                    <th>Location</th>
                                                    <th>Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($devices as $device)
                                                    <tr>
                                                        <td>
                                                            @switch($device->device_type)
                                                                @case('1') Printer @break
                                                                @case('2') Server @break
                                                                @case('3') Laptop @break
                                                                @case('4') Desktop @break
                                                                @case('5') Mobile @break
                                                                @case('6') Firewall @break
                                                                @case('7') Network Devices @break
                                                                @default {{ $device->device_type }}
                                                            @endswitch
                                                        </td>
                                                        <td>{{ $device->device_name }}</td>
                                                        <td>{{ $device->device_username }}</td>
                                                        <td>{{ $device->device_ip_address }}</td>
                                                        <td>{{ $device->device_location }}</td>
                                                        <td>{{ $device->notes ?: 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if(count($device_attachments) > 0)
                                        <div class="mt-4">
                                            <h5>Device Attachments</h5>
                                            <div class="list-group"></div>
                                            @foreach($device_attachments as $attachment)
                                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="list-group-item list-group-item-action">
                                                        <i class="fas fa-file me-2"></i>
                                                        {{ basename($attachment) }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">
                                        No device information has been submitted yet.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Licenses Tab -->
                        <div class="tab-pane fade" id="licenses" role="tabpanel" aria-labelledby="licenses-tab">
                            <div class="p-3">
                                @if(count($licenses) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Software</th>
                                                    <th>Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($licenses as $license)
                                                    <tr>
                                                        <td>{{ $license->software_license }}</td>
                                                        <td>{{ $license->quantity }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    @if(count($license_attachments) > 0)
                                        <div class="mt-4">
                                            <h5>License Attachments</h5>
                                            <div class="list-group">
                                                @foreach($license_attachments as $attachment)
                                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="list-group-item list-group-item-action">
                                                        <i class="fas fa-file me-2"></i>
                                                        {{ basename($attachment) }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">
                                        No license information has been submitted yet.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Webpage and Credentials Tab -->
                        <div class="tab-pane fade" id="webpage" role="tabpanel" aria-labelledby="webpage-tab">
                            <div class="p-3">
                                @if($webpage)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Credential Type</label>
                                                <p>
                                                    @switch($webpage->credential_type)
                                                        @case('1') Domain Registrar @break
                                                        @case('2') Web Hosting Provider @break
                                                        @case('3') DNS Provider @break
                                                        @case('4') Microsoft Admin @break
                                                        @case('5') Google Admin @break
                                                        @case('6') AWS Admin @break
                                                        @case('7') Security Cameras @break
                                                        @case('8') Access Control @break
                                                        @case('9') Cloud @break
                                                        @case('10') Vendor @break
                                                        @case('11') Telephony @break
                                                        @case('12') Machine Accounts @break
                                                        @case('13') Meraki @break
                                                        @case('14') Web/FTP @break
                                                        @case('15') SQL @break
                                                        @case('16') WiFi @break
                                                        @case('17') Other @break
                                                        @default {{ $webpage->credential_type }}
                                                    @endswitch
                                                </p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Name</label>
                                                <p>{{ $webpage->credential_name ?: 'N/A' }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">URL</label>
                                                <p>{{ $webpage->credential_url ?: 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Username</label>
                                                <p>{{ $webpage->credential_username ?: 'N/A' }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">MFA/OTP Enabled</label>
                                                <p>{{ $webpage->credential_mfa ?: 'N/A' }}</p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Notes</label>
                                                <p>{{ $webpage->credential_notes ?: 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if(count($webpage_attachments) > 0)
                                        <div class="mt-4">
                                            <h5>Webpage Attachments</h5>
                                            <div class="list-group">
                                                @foreach($webpage_attachments as $attachment)
                                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="list-group-item list-group-item-action">
                                                        <i class="fas fa-file me-2"></i>
                                                        {{ basename($attachment) }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning">
                                        No webpage or credential information has been submitted yet.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userId = document.getElementById('current-user-id').value;
    const companyInfoSection = document.querySelector('#company .row');
    const editButton = document.createElement('button');
    editButton.className = 'btn btn-primary btn-sm edit-all-btn position-absolute top-0 end-0 m-3';
    editButton.innerHTML = '<i class="fas fa-pencil-alt"></i> Edit All';

    // Add edit button to the company info section
    if (companyInfoSection) {
        const headerContainer = companyInfoSection.closest('.tab-pane').querySelector('.p-3');
        headerContainer.style.position = 'relative';
        headerContainer.appendChild(editButton);

        // Track edit mode
        let isEditMode = false;

        editButton.addEventListener('click', function() {
            isEditMode = !isEditMode;

            // Toggle button appearance
            if (isEditMode) {
                this.classList.remove('btn-primary');
                this.classList.add('btn-success');
                this.innerHTML = '<i class="fas fa-save"></i> Save Changes';

                // Enable edit mode for all fields
                companyInfoSection.querySelectorAll('.view-mode').forEach(viewMode => {
                    const input = viewMode.nextElementSibling;
                    if (input && input.classList.contains('edit-mode')) {
                        viewMode.style.display = 'none';
                        input.style.display = 'block';
                    }
                });
            } else {
                // Prepare to save changes
                const updatedFields = {};
                companyInfoSection.querySelectorAll('.edit-mode').forEach(input => {
                    // Safely get the field and value
                    const editable = input.closest('.editable-field');
                    if (!editable) return;

                    const saveBtn = editable.querySelector('.save-btn');
                    if (!saveBtn) return;

                    const field = saveBtn.dataset.field;
                    const value = input.value ? input.value.trim() : '';

                    if (field) {
                        updatedFields[field] = value;
                    }
                });

                // Only proceed if we have fields to update
                if (Object.keys(updatedFields).length === 0) {
                    alert('No changes to save');
                    return;
                }

                // AJAX request to update multiple fields
                fetch(`/admin/company/update-multiple`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        fields: updatedFields
                    })
                })
                .then(response => {
                    // Log full response for debugging
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);

                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Response is not JSON');
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('Parsed response:', data);

                    if (data.success) {
                        // Update view modes with new values
                        Object.keys(updatedFields).forEach(field => {
                            const fieldContainer = companyInfoSection.querySelector(`[data-field="${field}"]`);
                            if (fieldContainer) {
                                const viewMode = fieldContainer.closest('.editable-field').querySelector('.view-mode');
                                const input = fieldContainer.closest('.editable-field').querySelector('.edit-mode');

                                if (viewMode && input) {
                                    viewMode.textContent = updatedFields[field] || 'N/A';
                                    viewMode.style.display = 'block';
                                    input.style.display = 'none';
                                }
                            }
                        });

                        // Reset button
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                        this.innerHTML = '<i class="fas fa-pencil-alt"></i> Edit All';
                    } else {
                        // More detailed error handling
                        console.error('Update failed:', data);
                        alert(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Full error:', error);
                    alert(`An error occurred: ${error.message}`);
                });
            }
        });
    }

    // ... rest of the existing script remains the sam


    // Branch Editing
    const branchTable = document.querySelector('#branches table tbody');

    if (branchTable) {
        branchTable.addEventListener('click', function(e) {
            const editBtn = e.target.closest('.edit-branch-btn');
            const saveBtn = e.target.closest('.save-branch-btn');
            const cancelBtn = e.target.closest('.cancel-branch-btn');

            // Edit Branch
            if (editBtn) {
                const row = editBtn.closest('.branch-row');
                editBtn.style.display = 'none';
                row.querySelector('.edit-controls').style.display = 'block';

                row.querySelectorAll('.view-mode').forEach(viewMode => {
                    viewMode.style.display = 'none';
                });
                row.querySelectorAll('.edit-mode').forEach(editMode => {
                    editMode.style.display = 'block';
                });
            }

            // Cancel Edit
            if (cancelBtn) {
                const row = cancelBtn.closest('.branch-row');
                row.querySelector('.edit-branch-btn').style.display = 'block';
                row.querySelector('.edit-controls').style.display = 'none';

                row.querySelectorAll('.edit-mode').forEach(editMode => {
                    editMode.style.display = 'none';
                });
                row.querySelectorAll('.view-mode').forEach(viewMode => {
                    viewMode.style.display = 'block';
                });
            }

            // Save Branch
            if (saveBtn) {
                const row = saveBtn.closest('.branch-row');
                const branchId = row.dataset.branchId;

                const updatedFields = {};
                row.querySelectorAll('.edit-mode').forEach(input => {
                    updatedFields[input.name] = input.value.trim();
                });

                fetch(`/admin/branches/${branchId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(updatedFields)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update view modes
                        row.querySelectorAll('.edit-mode').forEach(input => {
                            const viewMode = input.previousElementSibling;
                            viewMode.textContent = input.value || 'N/A';
                            input.style.display = 'none';
                            viewMode.style.display = 'block';
                        });

                        // Reset buttons
                        row.querySelector('.edit-branch-btn').style.display = 'block';
                        row.querySelector('.edit-controls').style.display = 'none';
                    } else {
                        alert(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            }
        });
    }
});
</script>
@endpush
