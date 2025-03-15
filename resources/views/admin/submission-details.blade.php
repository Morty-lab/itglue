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
                            @if ($company && $company->approval_status == 'approved')
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
                                                            <option value="pending"
                                                                {{ $company && $company->approval_status == 'pending' ? 'selected' : '' }}>
                                                                Pending</option>
                                                            <option value="approved"
                                                                {{ $company && $company->approval_status == 'approved' ? 'selected' : '' }}>
                                                                Approved</option>
                                                            <option value="rejected"
                                                                {{ $company && $company->approval_status == 'rejected' ? 'selected' : '' }}>
                                                                Rejected</option>
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
                                <button class="nav-link active" id="company-tab" data-bs-toggle="tab"
                                    data-bs-target="#company" type="button" role="tab" aria-controls="company"
                                    aria-selected="true">
                                    Company Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="branches-tab" data-bs-toggle="tab" data-bs-target="#branches"
                                    type="button" role="tab" aria-controls="branches" aria-selected="false">
                                    Branches ({{ count($branches) }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="employees-tab" data-bs-toggle="tab" data-bs-target="#employees"
                                    type="button" role="tab" aria-controls="employees" aria-selected="false">
                                    Employees ({{ count($employees) }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="devices-tab" data-bs-toggle="tab" data-bs-target="#devices"
                                    type="button" role="tab" aria-controls="devices" aria-selected="false">
                                    Devices ({{ count($devices) }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="licenses-tab" data-bs-toggle="tab" data-bs-target="#licenses"
                                    type="button" role="tab" aria-controls="licenses" aria-selected="false">
                                    Licenses ({{ count($licenses) }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="webpage-tab" data-bs-toggle="tab" data-bs-target="#webpage"
                                    type="button" role="tab" aria-controls="webpage" aria-selected="false">
                                    Webpage & Credentials
                                </button>
                            </li>
                        </ul>
                        <input type="hidden" id="current-user-id" value="{{ $user->id }}">

                        <!-- Tab content -->
                        <div class="tab-content" id="submissionTabsContent">
                            <!-- Company Information Tab -->
                            <div class="tab-pane fade show active" id="company" role="tabpanel"
                                aria-labelledby="company-tab">
                                <div class="p-3">
                                    @if ($company)
                                        <form action="{{ route('admin.company.update-multiple-fields', ['user_id' => $user->id]) }}"
                                            method="post" id="companyForm">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                <!-- Left Column: Company Information -->
                                                <div class="col-md-6">
                                                    <!-- Company Name -->
                                                    <div class="mb-4 editable-field">
                                                        <label class="text-lg font-bold block mb-2">Company Name</label>
                                                        <div class="flex items-center space-x-2">
                                                            <span
                                                                class="view-mode text-base font-medium">{{ $company->company_name }}</span>
                                                            <input type="text"
                                                                class="form-input edit-mode hidden w-full p-2 border rounded-md"
                                                                value="{{ $company->company_name }}"
                                                                name="companies[company_name]">
                                                        </div>
                                                    </div>

                                                    <!-- Contact Information -->
                                                    <div class="mb-4">
                                                        <label class="text-lg font-bold block mb-2">Contact
                                                            Information</label>
                                                        <div class="space-y-4">
                                                            <!-- Primary Number -->
                                                            <div class="editable-field">
                                                                <label class="text-sm font-semibold">Primary
                                                                    Number:</label>
                                                                <span
                                                                    class="view-mode text-base font-medium">{{ $company->primary_number }}</span>
                                                                <input type="text"
                                                                    class="form-input edit-mode hidden w-full p-2 border rounded-md"
                                                                    value="{{ $company->primary_number }}"
                                                                    name="companies[primary_number]">
                                                            </div>

                                                            <!-- Secondary Number -->
                                                            <div class="editable-field">
                                                                <label class="text-sm font-semibold">Secondary
                                                                    Number:</label>
                                                                <span
                                                                    class="view-mode text-base font-medium">{{ $company->secondary_number ?: 'N/A' }}</span>
                                                                <input type="text"
                                                                    class="form-input edit-mode hidden w-full p-2 border rounded-md"
                                                                    value="{{ $company->secondary_number }}"
                                                                    name="companies[secondary_number]">
                                                            </div>

                                                            <!-- HQ Fax -->
                                                            <div class="editable-field">
                                                                <label class="text-sm font-semibold">HQ Fax:</label>
                                                                <span
                                                                    class="view-mode text-base font-medium">{{ $company->hq_fax ?: 'N/A' }}</span>
                                                                <input type="text"
                                                                    class="form-input edit-mode hidden w-full p-2 border rounded-md"
                                                                    value="{{ $company->hq_fax }}"
                                                                    name="companies[hq_fax]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Office Hours -->
                                                    <div class="mb-4 editable-field">
                                                        <label class="text-lg font-bold block mb-2">Office Hours</label>
                                                        <div class="flex items-center space-x-2">
                                                            <span
                                                                class="view-mode text-base font-medium">{{ $company->hq_opening_time }}
                                                                - {{ $company->hq_closing_time }}</span>
                                                            <div
                                                                class="input-group edit-mode hidden flex space-x-2 w-full">
                                                                <input type="text"
                                                                    class="form-input w-1/2 p-2 border rounded-md"
                                                                    value="{{ $company->hq_opening_time }}"
                                                                    placeholder="Opening Time"
                                                                    name="companies[hq_opening_time]">
                                                                <input type="text"
                                                                    class="form-input w-1/2 p-2 border rounded-md"
                                                                    value="{{ $company->hq_closing_time }}"
                                                                    placeholder="Closing Time"
                                                                    name="companies[hq_closing_time]">
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>

                                                <!-- Right Column: Additional Information -->
                                                <div class="col-md-6">
                                                    <!-- Address and Website -->
                                                    <div class="mb-3">

                                                        <!-- Address Section -->
                                                        <div class="editable-field bg-gray-100 p-4 rounded-lg">
                                                            <span class="view-mode block">
                                                                <span
                                                                    class="text-xl font-semibold">{{ $company->hq_location_name }}</span><br>
                                                                <span
                                                                    class="text-lg">{{ $company->hq_address }}</span><br>
                                                                <span class="text-lg">{{ $company->hq_city }},
                                                                    {{ $company->hq_state }}
                                                                    {{ $company->hq_postal_code }}</span><br>
                                                                <span
                                                                    class="text-lg">{{ $company->hq_country }}</span><br>
                                                                <span class="text-lg">{{ $company->hq_province }}</span>
                                                            </span>

                                                            <!-- Address Edit Mode -->
                                                            <div class="edit-mode hidden">
                                                                <label class="form-label text-sm">Location Name:</label>
                                                                <input type="text"
                                                                    class="form-control border p-2 rounded w-full"
                                                                    value="{{ $company->hq_location_name }}"
                                                                    name="companies[hq_location_name]">

                                                                <label class="form-label text-sm">Address:</label>
                                                                <input type="text"
                                                                    class="form-control border p-2 rounded w-full"
                                                                    value="{{ $company->hq_address }}"
                                                                    name="companies[hq_address]">

                                                                <label class="form-label text-sm">City:</label>
                                                                <input type="text"
                                                                    class="form-control border p-2 rounded w-full"
                                                                    value="{{ $company->hq_city }}"
                                                                    name="companies[hq_city]">

                                                                <label class="form-label text-sm">State:</label>
                                                                <input type="text"
                                                                    class="form-control border p-2 rounded w-full"
                                                                    value="{{ $company->hq_state }}"
                                                                    name="companies[hq_state]">

                                                                <label class="form-label text-sm">Postal Code:</label>
                                                                <input type="text"
                                                                    class="form-control border p-2 rounded w-full"
                                                                    value="{{ $company->hq_postal_code }}"
                                                                    name="companies[hq_postal_code]">

                                                                <label class="form-label text-sm">Country:</label>
                                                                <input type="text"
                                                                    class="form-control border p-2 rounded w-full"
                                                                    value="{{ $company->hq_country }}"
                                                                    name="companies[hq_country]">

                                                                <label class="form-label text-sm">Province:</label>
                                                                <input type="text"
                                                                    class="form-control border p-2 rounded w-full"
                                                                    value="{{ $company->hq_province }}"
                                                                    name="companies[hq_province]">
                                                            </div>
                                                        </div>

                                                        <!-- Website Section -->
                                                        <div class="editable-field mt-4 bg-gray-100 p-4 rounded-lg">
                                                            <label class="form-label font-semibold">Company Website</label>
                                                            <span
                                                                class="view-mode block text-lg font-medium">{{ $company->hq_website ?: 'N/A' }}</span>
                                                            <input type="text"
                                                                class="form-control edit-mode border p-2 rounded w-full hidden"
                                                                value="{{ $company->hq_website }}"
                                                                name="companies[hq_website]">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-warning">
                                            No company information has been submitted yet.
                                        </div>
                                    @endif
                                </div>
                            </div>


                            <!-- Branches Tab -->
                            <x-modal-update-branches :branches="$branches" />
                            <!-- Employees Tab -->
                            <x-modal-update-employee :employees="$employees" />
                            <!-- Devices Tab -->
                            <x-modal-update-devices :devices="$devices" />
                            <!-- Licenses Tab -->
                            <x-modal-update-licenses :licenses="$licenses" />

                            <!-- Webpage and Credentials Tab -->
                            <x-modal-update-webpage-credentials :company="$webpage" />
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
                            const editable = input.closest('.editable-field');
                            if (!editable) return;

                            const fieldContainer = editable.querySelector('[data-field]');
                            if (!fieldContainer) return;

                            const field = fieldContainer.dataset.field;
                            const value = input.value.trim() || '';

                            if (field) {
                                updatedFields[field] = value;
                            }
                        });

                        companyInfoSection.querySelectorAll('.edit-mode').forEach(input => {
                            input.style.display = 'none';
                        });
                        companyInfoSection.querySelectorAll('.view-mode').forEach(viewMode => {
                            viewMode.style.display = 'block';
                        });
                        // Only proceed if we have fields to update
                        // if (Object.keys(updatedFields).length === 0) {
                        //     alert('No changes to save');
                        //     return;
                        // }
                        // const requestData = {
                        //     companies: [{
                        //         user_id: userId, // Ensure this is the correct company ID
                        //         fields: updatedFields // Ensure this is an object like { "name": "New Name" }
                        //     }]
                        // };

                        // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        //     'content');
                        // fetch(`{{ route('admin.company.update-multiple-fields') }}`, {

                        //         method: 'PUT',
                        //         headers: {
                        //             'Content-Type': 'application/json',
                        //             'Accept': 'application/json',
                        //             'X-CSRF-TOKEN': csrfToken
                        //         },
                        //         body: JSON.stringify(requestData)
                        //     })
                        //     .then(response => {
                        //         // Check if response is successful
                        //         if (!response.ok) {
                        //             throw new Error(`HTTP error! status: ${response.status}`);
                        //         }
                        //         return response.json();
                        //     })
                        //     .then(data => {
                        //         if (data.success) {
                        //             // Update view modes with new values
                        //             Object.keys(updatedFields).forEach(field => {
                        //                 const fieldContainer = companyInfoSection.querySelector(
                        //                     `[data-field="${field}"]`);
                        //                 if (fieldContainer) {
                        //                     const viewMode = fieldContainer.closest(
                        //                         '.editable-field').querySelector(
                        //                         '.view-mode');
                        //                     const input = fieldContainer.closest(
                        //                         '.editable-field').querySelector(
                        //                         '.edit-mode');

                        //                     if (viewMode && input) {
                        //                         viewMode.textContent = updatedFields[field] ||
                        //                             'N/A';
                        //                         viewMode.style.display = 'block';
                        //                         input.style.display = 'none';
                        //                     }
                        //                 }
                        //             });

                        //             // Reset button
                                    this.classList.remove('btn-success');
                                    this.classList.add('btn-primary');
                                    this.innerHTML = '<i class="fas fa-pencil-alt"></i> Edit All';
                                    isEditMode = false;

                                    document.getElementById('companyForm').submit();

                        //         } else {
                        //             console.error('Update failed:', data);
                        //             alert(data.message || 'Update failed');
                        //         }
                        //     })
                        //     .catch(error => {
                        //         console.error('Error:', error);
                        //         alert(`An error occurred: ${error.message}`);
                        //     });
                    }
                });
            }
        });
    </script>
@endpush
