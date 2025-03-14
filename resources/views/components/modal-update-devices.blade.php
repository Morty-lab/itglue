<div class="tab-pane fade" id="devices" role="tabpanel" aria-labelledby="devices-tab">
    <div class="p-3">
        @if(isset($devices) && count($devices) > 0)
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($devices as $device)
                            <tr data-device-id="{{ $device->id }}" class="device-row">
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
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-device-btn" data-toggle="modal" data-target="#editDeviceModal" data-device="{{ json_encode($device) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(isset($device_attachments) && count($device_attachments) > 0)
                <div class="mt-4">
                    <h5>Device Attachments</h5>
                    <div class="list-group">
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

<!-- Edit Device Modal -->
<div class="modal fade" id="editDeviceModal" tabindex="-1" role="dialog" aria-labelledby="editDeviceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeviceModalLabel">Edit Device</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDeviceForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="device_type">Type</label>
                        <input type="text" class="form-control" id="device_type" name="device_type" required>
                    </div>
                    <div class="form-group">
                        <label for="device_name">Name</label>
                        <input type="text" class="form-control" id="device_name" name="device_name" required>
                    </div>
                    <div class="form-group">
                        <label for="device_username">Username</label>
                        <input type="text" class="form-control" id="device_username" name="device_username">
                    </div>
                    <div class="form-group">
                        <label for="device_ip_address">IP Address</label>
                        <input type="text" class="form-control" id="device_ip_address" name="device_ip_address">
                    </div>
                    <div class="form-group">
                        <label for="device_location">Location</label>
                        <input type="text" class="form-control" id="device_location" name="device_location">
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
        var deviceId;

        $('#editDeviceModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var deviceData = button.attr('data-device'); // Get data attribute
            var device = JSON.parse(deviceData); // Parse JSON

            deviceId = device.id; // Store the device ID

            var modal = $(this);
            modal.find('#device_type').val(device.device_type || '');
            modal.find('#device_name').val(device.device_name || '');
            modal.find('#device_username').val(device.device_username || '');
            modal.find('#device_ip_address').val(device.device_ip_address || '');
            modal.find('#device_location').val(device.device_location || '');
            modal.find('#notes').val(device.notes || '');

            // Store original data
            originalData = {
                device_type: device.device_type,
                device_name: device.device_name,
                device_username: device.device_username,
                device_ip_address: device.device_ip_address,
                device_location: device.device_location,
                notes: device.notes
            };
        });

        $('#editDeviceForm').on('submit', function (event) {
            event.preventDefault();

            var formData = {};
            var hasChanges = false;

            // Check for changes and collect updated data
            $('#editDeviceForm').find('input').each(function () {
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
                url: '{{ route("devices.updateAll") }}', // Adjust the route as necessary
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: deviceId, // Include the device ID
                    data: formData
                },
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        alert('Device updated successfully!');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to update device.');
                    }
                },
                error: function (xhr) {
                    alert('An error occurred while updating the device.');
                }
            });
        });
    });
</script>
