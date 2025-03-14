<div class="tab-pane fade" id="licenses" role="tabpanel" aria-labelledby="licenses-tab">
    <div class="p-3">
        @if(isset($licenses) && count($licenses) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Software</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($licenses as $license)
                            <tr data-license-id="{{ $license->id }}" class="license-row">
                                <td>{{ $license->software_license }}</td>
                                <td>{{ $license->quantity }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-license-btn" data-toggle="modal" data-target="#editLicenseModal" data-license="{{ json_encode($license) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(isset($license_attachments) && count($license_attachments) > 0)
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

<!-- Edit License Modal -->
<div class="modal fade" id="editLicenseModal" tabindex="-1" role="dialog" aria-labelledby="editLicenseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLicenseModalLabel">Edit License</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editLicenseForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="software_license">Software</label>
                        <input type="text" class="form-control" id="software_license" name="software_license" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
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
        var licenseId;

        $('#editLicenseModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var licenseData = button.attr('data-license'); // Get data attribute
            var license = JSON.parse(licenseData); // Parse JSON

            licenseId = license.id; // Store the license ID

            var modal = $(this);
            modal.find('#software_license').val(license.software_license || '');
            modal.find('#quantity').val(license.quantity || '');

            // Store original data
            originalData = {
                software_license: license.software_license,
                quantity: license.quantity
            };
        });

        $('#editLicenseForm').on('submit', function (event) {
            event.preventDefault();

            var formData = {};
            var hasChanges = false;

            // Check for changes and collect updated data
            $('#editLicenseForm').find('input').each(function () {
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
                url: '{{ route("licenses.updateAll") }}', // Adjust the route as necessary
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: licenseId, // Include the license ID
                    data: formData
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        alert('License updated successfully!');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to update license.');
                    }
                },
                error: function (xhr) {
                    alert('An error occurred while updating the license.');
                }
            });
        });
    });
</script>
