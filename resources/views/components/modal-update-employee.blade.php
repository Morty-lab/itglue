<div class="tab-pane fade" id="employees" role="tabpanel" aria-labelledby="employees-tab">
    <div class="p-3">
        @if(isset($employees) && count($employees) > 0)
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                            <tr data-employee-id="{{ $employee->id }}" class="employee-row">
                                <td>{{ $employee->firstname }} {{ $employee->lastname }}</td>
                                <td>{{ $employee->employee_title }}</td>
                                <td>{{ $employee->employee_email }}</td>
                                <td>{{ $employee->employee_phone_number }}</td>
                                <td>{{ $employee->employee_working_number }}</td>
                                <td>{{ $employee->employee_working_location }}</td>
                                <td>{{ $employee->notes ?: 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-employee-btn" data-toggle="modal" data-target="#editEmployeeModal" data-employee="{{ json_encode($employee) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(isset($employee_attachments) && count($employee_attachments) > 0)
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

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editEmployeeForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_title">Title</label>
                        <input type="text" class="form-control" id="employee_title" name="employee_title" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_email">Email</label>
                        <input type="email" class="form-control" id="employee_email" name="employee_email" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_phone_number">Mobile Number</label>
                        <input type="text" class="form-control" id="employee_phone_number" name="employee_phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="employee_working_number">Work Phone</label>
                        <input type="text" class="form-control" id="employee_working_number" name="employee_working_number">
                    </div>
                    <div class="form-group">
                        <label for="employee_working_location">Location</label>
                        <input type="text" class="form-control" id="employee_working_location" name="employee_working_location">
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <input type="text" class="form-control" id="notes" name="notes">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Then load Bootstrap's JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Your custom script must come after Bootstrap -->
<script>
    $(document).ready(function () {
        var originalData = {};
        var employeeId;

        $('#editEmployeeModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var employeeData = button.attr('data-employee'); // Get data attribute
            var employee = JSON.parse(employeeData); // Parse JSON

            employeeId = employee.id; // Store the employee ID

            var modal = $(this);
            modal.find('#firstname').val(employee.firstname || '');
            modal.find('#lastname').val(employee.lastname || '');
            modal.find('#employee_title').val(employee.employee_title || '');
            modal.find('#employee_email').val(employee.employee_email || '');
            modal.find('#employee_phone_number').val(employee.employee_phone_number || '');
            modal.find('#employee_working_number').val(employee.employee_working_number || '');
            modal.find('#employee_working_location').val(employee.employee_working_location || '');
            modal.find('#notes').val(employee.notes || '');

            // Store original data
            originalData = {
                firstname: employee.firstname,
                lastname: employee.lastname,
                employee_title: employee.employee_title,
                employee_email: employee.employee_email,
                employee_phone_number: employee.employee_phone_number,
                employee_working_number: employee.employee_working_number,
                employee_working_location: employee.employee_working_location,
                notes: employee.notes
            };
        });

        $('#editEmployeeForm').on('submit', function (event) {
            event.preventDefault();

            var formData = {};
            var hasChanges = false;

            // Check for changes and collect updated data
            $('#editEmployeeForm').find('input').each(function () {
                var field = $(this).attr('name');
                var value = $(this).val();
                if (value !== originalData[field]) {
                    formData[field] = value;
                    hasChanges = true;
                }
            });

            if (!hasChanges) {
                alert('No changes detected.');
                return;
            }

            // Add AJAX submission logic here
            $.ajax({
                url: '{{ route("employees.updateAll") }}', // Adjust the route as necessary
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: employeeId, // Include the employee ID
                    data: formData
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        alert('Employee updated successfully!');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to update employee.');
                    }
                },
                error: function (xhr) {
                    alert('An error occurred while updating the employee.');
                }
            });
        });
    });
</script>
