<div class="tab-pane fade" id="webpage" role="tabpanel" aria-labelledby="webpage-tab">
    <div class="p-3">
        @if($company)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Credential Type</th>
                            <th>Name</th>
                            <th>URL</th>
                            <th>Username</th>
                            <th>MFA/OTP Enabled</th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-webpage-id="{{ $company->id }}" class="webpage-row">
                            <td>{{ $company->credential_type }}</td>
                            <td>{{ $company->credential_name }}</td>
                            <td>{{ $company->credential_url }}</td>
                            <td>{{ $company->credential_username }}</td>
                            <td>{{ $company->credential_mfa }}</td>
                            <td>{{ $company->credential_notes }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-webpage-btn" data-toggle="modal" data-target="#editWebpageModal" data-webpage="{{ json_encode($company) }}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if(isset($company->attachments) && count($company->attachments) > 0)
                <div class="mt-4">
                    <h5>Webpage Attachments</h5>
                    <div class="list-group">
                        @foreach($company->attachments as $attachment)
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

<!-- Edit Webpage Modal -->
<div class="modal fade" id="editWebpageModal" tabindex="-1" role="dialog" aria-labelledby="editWebpageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWebpageModalLabel">Edit Webpage Credential</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editWebpageForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="credential_type">Credential Type</label>
                        <input type="text" class="form-control" id="credential_type" name="credential_type" required>
                    </div>
                    <div class="form-group">
                        <label for="credential_name">Name</label>
                        <input type="text" class="form-control" id="credential_name" name="credential_name" required>
                    </div>
                    <div class="form-group">
                        <label for="credential_url">URL</label>
                        <input type="text" class="form-control" id="credential_url" name="credential_url" required>
                    </div>
                    <div class="form-group">
                        <label for="credential_username">Username</label>
                        <input type="text" class="form-control" id="credential_username" name="credential_username" required>
                    </div>
                    <div class="form-group">
                        <label for="credential_mfa">MFA/OTP Enabled</label>
                        <input type="text" class="form-control" id="credential_mfa" name="credential_mfa" required>
                    </div>
                    <div class="form-group">
                        <label for="credential_notes">Notes</label>
                        <input type="text" class="form-control" id="credential_notes" name="credential_notes" required>
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
        var webpageId;

        $('#editWebpageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var webpageData = button.attr('data-webpage'); // Get data attribute
            var webpage = JSON.parse(webpageData); // Parse JSON

            webpageId = webpage.id; // Store the webpage ID

            var modal = $(this);
            modal.find('#credential_type').val(webpage.credential_type || '');
            modal.find('#credential_name').val(webpage.credential_name || '');
            modal.find('#credential_url').val(webpage.credential_url || '');
            modal.find('#credential_username').val(webpage.credential_username || '');
            modal.find('#credential_mfa').val(webpage.credential_mfa || '');
            modal.find('#credential_notes').val(webpage.credential_notes || '');

            // Store original data
            originalData = {
                credential_type: webpage.credential_type,
                credential_name: webpage.credential_name,
                credential_url: webpage.credential_url,
                credential_username: webpage.credential_username,
                credential_mfa: webpage.credential_mfa,
                credential_notes: webpage.credential_notes
            };
        });

        $('#editWebpageForm').on('submit', function (event) {
            event.preventDefault();

            var formData = {};
            var hasChanges = false;

            // Check for changes and collect updated data
            $('#editWebpageForm').find('input').each(function () {
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

                url: `{{ route('webpages.update-multiple-fields') }}`,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: webpageId, // Include the webpage ID
                    data: formData
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        alert('Webpage credential updated successfully!');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to update webpage credential.');
                    }
                },
                error: function (xhr) {
                    alert('An error occurred while updating the webpage credential.');
                }
            });
        });
    });
</script>
