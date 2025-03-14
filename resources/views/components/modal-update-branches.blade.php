<div class="tab-pane fade" id="branches" role="tabpanel" aria-labelledby="branches-tab">
    <div class="p-3">
        @if(count($branches) > 0)
            <script>console.log(@json($branches));</script>
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
                                <td>{{ $branch->branch_address }}</td>
                                <td>{{ $branch->phone_number }}</td>
                                <td>{{ $branch->fax ?: 'N/A' }}</td>
                                <td>{{ $branch->website ?: 'N/A' }}</td>
                                <td>{{ $branch->opening_time }}</td>
                                <td>{{ $branch->closing_time }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-branch-btn" data-toggle="modal" data-target="#editBranchModal" data-branch="{{ json_encode($branch) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Edit Branch Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog" aria-labelledby="editBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBranchModalLabel">Edit Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editBranchForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="branch_address">Address</label>
                        <input type="text" class="form-control" id="branch_address" name="branch_address" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="fax">Fax</label>
                        <input type="text" class="form-control" id="fax" name="fax">
                    </div>
                    <div class="form-group">
                        <label for="website">Website</label>
                        <input type="text" class="form-control" id="website" name="website">
                    </div>
                    <div class="form-group">
                        <label for="opening_time">Opening Time</label>
                        <input type="text" class="form-control" id="opening_time" name="opening_time" required>
                    </div>
                    <div class="form-group">
                        <label for="closing_time">Closing Time</label>
                        <input type="text" class="form-control" id="closing_time" name="closing_time" required>
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
        var branchId;

        $('#editBranchModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var branchData = button.attr('data-branch'); // Get data attribute
            var branch = JSON.parse(branchData); // Parse JSON

            branchId = branch.id; // Store the branch ID

            var modal = $(this);
            modal.find('#branch_address').val(branch.branch_address || '');
            modal.find('#phone_number').val(branch.phone_number || '');
            modal.find('#fax').val(branch.fax || '');
            modal.find('#website').val(branch.website || '');
            modal.find('#opening_time').val(branch.opening_time || '');
            modal.find('#closing_time').val(branch.closing_time || '');

            // Store original data
            originalData = {
                branch_address: branch.branch_address,
                phone_number: branch.phone_number,
                fax: branch.fax,
                website: branch.website,
                opening_time: branch.opening_time,
                closing_time: branch.closing_time
            };
        });

        $('#editBranchForm').on('submit', function (event) {
            event.preventDefault();

            var formData = {};
            var hasChanges = false;

            // Check for changes and collect updated data
            $('#editBranchForm').find('input').each(function () {
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
                url: '{{ route("branches.updateAll") }}', // Adjust the route as necessary
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: branchId, // Include the branch ID
                    data: formData
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        alert('Branch updated successfully!');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to update branch.');
                    }
                },
                error: function (xhr) {
                    alert('An error occurred while updating the branch.');
                }
            });
        });
    });
</script>
