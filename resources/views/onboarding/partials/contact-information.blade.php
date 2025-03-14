@extends('layouts.onboarding')
@section('content')
<form action="{{ route('onboarding.contact_information.employees.store', ['user_id' => auth()->user()->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="tab" id="employeeAccountsTab">
            <div id="employeeContainer">
                @if (isset($employees))
                    @foreach ($employees as $index => $employee)
                        <div class="employee-fields" id="employee-{{ $index }}">
                            <!-- Add hidden ID field to track existing employees -->
                            <input type="hidden" name="employees[{{ $index }}][id]" value="{{ $employee->id }}">

                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">First Name</label>
                                    <input required name="employees[{{ $index }}][first_name]"
                                        class="form-control form-control-sm" type="text"
                                        value="{{ $employee->firstname ?? '' }}">
                                </div>
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Last Name</label>
                                    <input required name="employees[{{ $index }}][last_name]"
                                        class="form-control form-control-sm" type="text"
                                        value="{{ $employee->lastname ?? '' }}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Title</label>
                                    <input required name="employees[{{ $index }}][title]"
                                        class="form-control form-control-sm" type="text"
                                        value="{{ $employee->employee_title ?? '' }}">
                                </div>
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Email</label>
                                    <input required name="employees[{{ $index }}][email]"
                                        class="form-control form-control-sm" type="email"
                                        value="{{ $employee->employee_email ?? '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-control-sm ps-0">Working Location</label>
                                <input required name="employees[{{ $index }}][working_location]"
                                    class="form-control form-control-sm" type="text"
                                    value="{{ $employee->employee_working_location ?? '' }}">
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Mobile Number</label>
                                    <input required name="employees[{{ $index }}][mobile_number]"
                                        class="form-control form-control-sm" type="tel"
                                        value="{{ $employee->employee_phone_number ?? '' }}">
                                </div>
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Work Phone</label>
                                    <input required name="employees[{{ $index }}][work_phone]"
                                        class="form-control form-control-sm" type="tel"
                                        value="{{ $employee->employee_working_number ?? '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-control-sm ps-0">Notes</label>
                                <textarea name="employees[{{ $index }}][notes]"
                                    class="form-control form-control-sm" rows="3">{{ $employee->notes ?? '' }}</textarea>
                            </div>
                            <!-- Add remove button for each employee -->
                            <button type="button" class="btn btn-danger btn-sm mb-3 remove-employee" data-employee-id="{{ $index }}">
                                Remove Employee
                            </button>
                            <hr>
                        </div>
                    @endforeach
                @else
                    <div class="employee-fields" id="employee-0">
                        <input type="hidden" name="employees[0][id]" value="">

                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">First Name</label>
                                <input required name="employees[0][first_name]" class="form-control form-control-sm"
                                    type="text">
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Last Name</label>
                                <input required name="employees[0][last_name]" class="form-control form-control-sm"
                                    type="text">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Title</label>
                                <input required name="employees[0][title]" class="form-control form-control-sm" type="text">
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Email</label>
                                <input required name="employees[0][email]" class="form-control form-control-sm"
                                    type="email">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-control-sm ps-0">Working Location</label>
                            <input required name="employees[0][working_location]" class="form-control form-control-sm"
                                type="text">
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Mobile Number</label>
                                <input required name="employees[0][mobile_number]" class="form-control form-control-sm"
                                    type="tel">
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Work Phone</label>
                                <input required name="employees[0][work_phone]" class="form-control form-control-sm"
                                    type="tel">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-control-sm ps-0">Notes</label>
                            <textarea name="employees[0][notes]" class="form-control form-control-sm" rows="3"></textarea>
                        </div>
                        <!-- No remove button for the first employee if it's the only one -->
                    </div>
                @endif
            </div>
            <!-- Add Employee Button -->
            <button type="button" class="btn btn-outline-secondary rounded-0 btn-sm mt-3 mb-3" id="addEmployeeBtn">+ Add
                Employee</button>
                <div class="mb-3">
                    <label for="formFileSm" class="form-control-sm ps-0">Attachment (Optional)</label>
                    <input class="form-control form-control-sm"
                        id="employeeFiles"
                        type="file"
                        name="employee_files[]"
                        multiple
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.drawio">

                        @if(!empty($employee_attachments))
                        <div class="mt-2">
                            <p>Current attachments:</p>
                            <ul class="list-group">
                                @foreach($employee_attachments as $attachment)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ basename($attachment) }}
                                        <a href="{{ asset('storage/' . $attachment) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-info">View</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
        </div>
        <div class=" d-flex justify-content-end mt-3 gap-2">

            <a href="{{ route('onboarding') }}" class="btn btn-secondary rounded-0 px-5 fw-semibold">
                Previous
            </a>
            <button type="submit" class="btn rounded-0 text-white px-5 fw-semibold"
                style="background-color: #0369A1; border-color: #0369A1;">Next</button>
        </div>
    </form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Employee count for new employees
        let employeeCount = {{ isset($employees) ? count($employees) : 1 }};
        let addEmployeeBtn = document.getElementById('addEmployeeBtn');

        // Remove any existing event listeners to prevent duplication
        if (addEmployeeBtn) {
            // Clone the button to remove all event listeners
            const newButton = addEmployeeBtn.cloneNode(true);
            addEmployeeBtn.parentNode.replaceChild(newButton, addEmployeeBtn);
            addEmployeeBtn = newButton;

            // Add the event listener to the clean button
            addEmployeeBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default button behavior
                e.stopPropagation(); // Stop event propagation

                const newIndex = employeeCount;
                console.log('Adding new employee with index:', newIndex); // Debug log

                const employeeHtml = `
                    <div class="employee-fields" id="employee-${newIndex}">
                        <input type="hidden" name="employees[${newIndex}][id]" value="">

                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">First Name</label>
                                <input required name="employees[${newIndex}][first_name]" class="form-control form-control-sm" type="text">
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Last Name</label>
                                <input required name="employees[${newIndex}][last_name]" class="form-control form-control-sm" type="text">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Title</label>
                                <input required name="employees[${newIndex}][title]" class="form-control form-control-sm" type="text">
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Email</label>
                                <input required name="employees[${newIndex}][email]" class="form-control form-control-sm" type="email">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-control-sm ps-0">Working Location</label>
                            <input required name="employees[${newIndex}][working_location]" class="form-control form-control-sm" type="text">
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Mobile Number</label>
                                <input required name="employees[${newIndex}][mobile_number]" class="form-control form-control-sm" type="tel">
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Work Phone</label>
                                <input required name="employees[${newIndex}][work_phone]" class="form-control form-control-sm" type="tel">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-control-sm ps-0">Notes</label>
                            <textarea name="employees[${newIndex}][notes]" class="form-control form-control-sm" rows="3"></textarea>
                        </div>

                        <button type="button" class="btn btn-danger btn-sm mb-3 remove-employee" data-employee-id="${newIndex}">
                            Remove Employee
                        </button>
                        <hr>
                    </div>
                `;

                document.getElementById('employeeContainer').insertAdjacentHTML('beforeend', employeeHtml);
                employeeCount++;
                return false; // Prevent event bubbling
            });
        }

        // Remove Employee Button Click (using event delegation)
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-employee')) {
                const employeeId = e.target.getAttribute('data-employee-id');
                const employeeElement = document.getElementById('employee-' + employeeId);

                if (employeeElement) {
                    employeeElement.remove();
                }
            }
        });
    });
</script>
@endsection
