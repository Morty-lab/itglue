<div class="tab-pane fade {{ session('active_tab') == 'webpage-tab' ? 'show active' : '' }}" id="webpage" role="tabpanel" aria-labelledby="webpage-tab">
    <div class="p-3">
        @if ($webpages->count() > 0)
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
                        @foreach ($webpages as $webpage)
                            @php
                                $credential_types = [
                                    '1' => 'Domain Registrar',
                                    '2' => 'Web Hosting Provider',
                                    '3' => 'DNS Provider',
                                    '4' => 'Microsoft Admin',
                                    '5' => 'Google Admin',
                                    '6' => 'AWS Admin',
                                    '7' => 'Security Cameras',
                                    '8' => 'Access Control',
                                    '9' => 'Cloud',
                                    '10' => 'Vendor',
                                    '11' => 'Telephony',
                                    '12' => 'Machine Accounts',
                                    '13' => 'Meraki',
                                    '14' => 'Web/FTP',
                                    '15' => 'SQL',
                                    '16' => 'WiFi',
                                    '17' => 'Other',
                                ];

                            @endphp
                            <tr data-webpage-id="{{ $webpage->id }}" class="webpage-row">
                                <td>{{ $credential_types[$webpage->credential_type] }}</td>
                                <td>{{ $webpage->credential_name }}</td>
                                <td>{{ $webpage->credential_url }}</td>
                                <td>{{ $webpage->credential_username }}</td>
                                <td>{{ $webpage->credential_mfa }}</td>
                                <td>{{ $webpage->credential_notes }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-webpage-btn" data-toggle="modal"
                                        data-target="#editWebpageModal-{{ $webpage->id }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (isset($company->attachments) && count($company->attachments) > 0)
                <div class="mt-4">
                    <h5>Webpage Attachments</h5>
                    <div class="list-group">
                        @foreach ($company->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment) }}" target="_blank"
                                class="list-group-item list-group-item-action">
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
@foreach ($webpages as $webpage)
    <!-- Edit Webpage Modal -->
    <div class="modal fade" id="editWebpageModal-{{ $webpage->id }}" tabindex="-1" role="dialog"
        aria-labelledby="editWebpageModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWebpageModalLabel">Edit Webpage Credential</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('webpages.update-multiple-fields', ['id' => $webpage->id]) }}"
                    id="editWebpageForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="credential_type">Credential Type</label>
                            <select class="form-select form-select-sm credentialType" name="data[credential_type]" required>
                                <option selected value="">Select credential type</option>
                                <option value="1" {{ $webpage->credential_type === '1' ? 'selected' : '' }}>
                                    Domain
                                    Registrar</option>
                                <option value="2" {{ $webpage->credential_type === '2' ? 'selected' : '' }}>Web
                                    Hosting Provider</option>
                                <option value="3" {{ $webpage->credential_type === '3' ? 'selected' : '' }}>DNS
                                    Provider</option>
                                <option value="4" {{ $webpage->credential_type === '4' ? 'selected' : '' }}>
                                    Microsoft Admin</option>
                                <option value="5" {{ $webpage->credential_type === '5' ? 'selected' : '' }}>
                                    Google
                                    Admin</option>
                                <option value="6" {{ $webpage->credential_type === '6' ? 'selected' : '' }}>
                                    AWS
                                    Admin</option>
                                <option value="7" {{ $webpage->credential_type === '7' ? 'selected' : '' }}>
                                    Security Cameras</option>
                                <option value="8" {{ $webpage->credential_type === '8' ? 'selected' : '' }}>
                                    Access
                                    Control</option>
                                <option value="9" {{ $webpage->credential_type === '9' ? 'selected' : '' }}>
                                    Cloud
                                </option>
                                <option value="10" {{ $webpage->credential_type === '10' ? 'selected' : '' }}>
                                    Vendor</option>
                                <option value="11" {{ $webpage->credential_type === '11' ? 'selected' : '' }}>
                                    Telephoy</option>
                                <option value="12" {{ $webpage->credential_type === '12' ? 'selected' : '' }}>
                                    Machine Accounts</option>
                                <option value="13" {{ $webpage->credential_type === '13' ? 'selected' : '' }}>
                                    Meraki</option>
                                <option value="14" {{ $webpage->credential_type === '14' ? 'selected' : '' }}>
                                    Web/FTP</option>
                                <option value="15" {{ $webpage->credential_type === '15' ? 'selected' : '' }}>
                                    SQL
                                </option>
                                <option value="16" {{ $webpage->credential_type === '16' ? 'selected' : '' }}>
                                    WiFi
                                </option>
                                <option value="17" {{ $webpage->credential_type === '17' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="credential_name">Name</label>
                            <input type="text" class="form-control" id="credential_name" name="data[credential_name]"
                                value="{{ $webpage->credential_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="credential_url">URL</label>
                            <input type="text" class="form-control" id="credential_url" name="data[credential_url]"
                                value="{{ $webpage->credential_url }}" required>
                        </div>
                        <div class="form-group">
                            <label for="credential_username">Username</label>
                            <input type="text" class="form-control" id="credential_username"
                                name="data[credential_username]" value="{{ $webpage->credential_username }}" required>
                        </div>
                        <div class="form-group">
                            <label for="credential_mfa">MFA/OTP Enabled</label>
                            <input type="text" class="form-control" id="credential_mfa" name="data[credential_mfa]"
                                value="{{ $webpage->credential_mfa }}" required>
                        </div>
                        <div class="form-group">
                            <label for="credential_notes">Notes</label>
                            <input type="text" class="form-control" id="credential_notes" name="data[credential_notes]"
                                value="{{ $webpage->credential_notes }}" required>
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
@endforeach


<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Then load Bootstrap's JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
