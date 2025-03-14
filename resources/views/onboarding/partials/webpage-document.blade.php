<!-- Webpage & Document -->
@extends('layouts.onboarding')
@section('content')
    <form action="{{ route('onboarding.webpage_development.webpage-development.store', ['user_id' => auth()->user()->id]) }}"
        method="post" enctype="multipart/form-data">
        @csrf
        <div class="tab">
            <div id="credentialInputs">
                @if (count($webpages) > 0)
                    @foreach ($webpages as $index => $webpage)
                        <div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Credential Type</label>
                                    <input type="hidden" name="webpage_document[{{ $index }}][id]"
                                        value="{{ $webpage->id ?? null }}">

                                    <select class="form-select form-select-sm credentialType"
                                        id="credentialType-{{ $index }}"
                                        name="webpage_document[{{ $index }}][credential_type]" required
                                        onchange="checkCredentialType({{ $index }})">
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
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Name</label>
                                    <input class="form-control form-control-sm web-document-input" type="text"
                                        name="webpage_document[{{ $index }}][credential_name]"
                                        value="{{ $webpage->credential_name ?? '' }}" required disabled>
                                </div>
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">URL</label>
                                    <input class="form-control form-control-sm web-document-input" type="password"
                                        name="webpage_document[{{ $index }}][credential_url]"
                                        value="{{ $webpage->credential_url ?? '' }}" required disabled>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Username</label>
                                    <input class="form-control form-control-sm web-document-input" type="text"
                                        name="webpage_document[{{ $index }}][credential_username]"
                                        value="{{ $webpage->credential_username ?? '' }}" required disabled>
                                </div>
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Password</label>
                                    <input class="form-control form-control-sm web-document-input" type="password"
                                        name="webpage_document[{{ $index }}][credential_password]"
                                        value="{{ $webpage->credential_password ?? '' }}" required disabled>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-control-sm ps-0">MFA/OTP Enabled</label>
                                <input class="form-control form-control-sm web-document-input" type="text"
                                    name="webpage_document[{{ $index }}][credential_mfa]"
                                    value="{{ $webpage->credential_mfa ?? '' }}" required disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-control-sm ps-0">Notes</label>
                                <textarea class="form-control form-control-sm web-document-input" rows="3"
                                    name="webpage_document[{{ $index }}][credential_notes]">{{ $webpage->credential_notes ?? '' }}</textarea>
                            </div>
                        </div>
                            <button type="button" class="btn btn-sm btn-danger mb-2"
                                onclick="axios.post('{{ route('credentials.destroy', $webpage->id) }}')
                                    .then(response => {
                                        window.location.reload();
                                    })
                                    .catch(error => {
                                        console.error(error);
                                    })">
                                Delete
                            </button>
                    @endforeach
                @else
                <div>
                    <div class="d-flex justify-content-between gap-2">
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">Credential Type</label>
                            <input type="hidden" name="webpage_document[0][id]" value="">

                            <select class="form-select form-select-sm credentialType"
                                id="credentialType-0"
                                name="webpage_document[0][credential_type]" required
                                onchange="checkCredentialType(0)">
                                <option selected value="">Select credential type</option>
                                <option value="1">Domain Registrar</option>
                                <option value="2">Web Hosting Provider</option>
                                <option value="3">DNS Provider</option>
                                <option value="4">Microsoft Admin</option>
                                <option value="5">Google Admin</option>
                                <option value="6">AWS Admin</option>
                                <option value="7">Security Cameras</option>
                                <option value="8">Access Control</option>
                                <option value="9">Cloud</option>
                                <option value="10">Vendor</option>
                                <option value="11">Telephoy</option>
                                <option value="12">Machine Accounts</option>
                                <option value="13">Meraki</option>
                                <option value="14">Web/FTP</option>
                                <option value="15">SQL</option>
                                <option value="16">WiFi</option>
                                <option value="17">Other</option>
                            </select>
                        </div>
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">Name</label>
                            <input class="form-control form-control-sm web-document-input" type="text"
                                name="webpage_document[0][credential_name]" value="" required disabled>
                        </div>
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">URL</label>
                            <input class="form-control form-control-sm web-document-input" type="password"
                                name="webpage_document[0][credential_url]" value="" required disabled>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between gap-2">
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">Username</label>
                            <input class="form-control form-control-sm web-document-input" type="text"
                                name="webpage_document[0][credential_username]" value="" required disabled>
                        </div>
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">Password</label>
                            <input class="form-control form-control-sm web-document-input" type="password"
                                name="webpage_document[0][credential_password]" value="" required disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-control-sm ps-0">MFA/OTP Enabled</label>
                        <input class="form-control form-control-sm web-document-input" type="text"
                            name="webpage_document[0][credential_mfa]" value="" required disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-control-sm ps-0">Notes</label>
                        <textarea class="form-control form-control-sm web-document-input" rows="3"
                            name="webpage_document[0][credential_notes]"></textarea>
                    </div>
                </div>
                @endif

            </div>
            <div class="mb-3">
                <button type="button" class="btn btn-primary" id="addCredentialButton">
                    Add Credential
                </button>
            </div>

            <script>
                document.getElementById('addCredentialButton').addEventListener('click', function() {
                    var newCredential = document.createElement('div');
                    newCredential.innerHTML = `
                                            <div class="d-flex justify-content-between gap-2">
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">Credential Type</label>
                            <input type="hidden" name="webpage_document[${document.getElementById('credentialInputs').children.length}][id]" value=null }}">
                            <select class="form-select form-select-sm " id="credentialType-${document.getElementById('credentialInputs').children.length}" name="webpage_document[${document.getElementById('credentialInputs').children.length}][credential_type]" required onchange="checkCredentialType(${document.getElementById('credentialInputs').children.length})">
                                 <option selected value="">Select credential type</option>
                                <option value="1">Domain Registrar</option>
                                <option value="2">Web Hosting Provider</option>
                                <option value="3">DNS Provider</option>
                                <option value="4">Microsoft Admin</option>
                                <option value="5">Google Admin</option>
                                <option value="6">AWS Admin</option>
                                <option value="7">Security Cameras</option>
                                <option value="8">Access Control</option>
                                <option value="9">Cloud</option>
                                <option value="10">Vendor</option>
                                <option value="11">Telephoy</option>
                                <option value="12">Machine Accounts</option>
                                <option value="13">Meraki</option>
                                <option value="14">Web/FTP</option>
                                <option value="15">SQL</option>
                                <option value="16">WiFi</option>
                                <option value="17">Other</option>
                            </select>
                        </div>
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">Name</label>
                            <input class="form-control form-control-sm web-document-input" type="text" name="webpage_document[${document.getElementById('credentialInputs').children.length}][credential_name]" value="" required disabled>
                        </div>
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">URL</label>
                            <input class="form-control form-control-sm web-document-input" type="password" name="webpage_document[${document.getElementById('credentialInputs').children.length}][credential_url]" value="" required disabled>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between gap-2">
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">Username</label>
                            <input class="form-control form-control-sm web-document-input" type="text" name="webpage_document[${document.getElementById('credentialInputs').children.length}][credential_username]" value="" required disabled>
                        </div>
                        <div class="mb-3 w-100">
                            <label class="form-control-sm ps-0">Password</label>
                            <input class="form-control form-control-sm web-document-input" type="password" name="webpage_document[${document.getElementById('credentialInputs').children.length}][credential_password]" value="" required disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-control-sm ps-0">MFA/OTP Enabled</label>
                        <input class="form-control form-control-sm web-document-input" type="text" name="webpage_document[${document.getElementById('credentialInputs').children.length}][credential_mfa]" value="" required disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-control-sm ps-0">Notes</label>
                        <textarea class="form-control form-control-sm web-document-input" rows="3" name="webpage_document[${document.getElementById('credentialInputs').children.length}][credential_notes]"></textarea>
                    </div>

                    <button type="button" class="btn btn-danger btn-sm remove-credential mb-2" onclick="this.parentElement.remove();">
                        Remove Credential
                    </button>

                    `;
                    document.getElementById('credentialInputs').appendChild(newCredential);
                });
            </script>




            <div class="mb-3">
                <label for="formFileSm" class="form-control-sm ps-0">Attachment (Optional)</label>
                <input class="form-control form-control-sm" id="webpageFiles" type="file" name="webpage_files[]" multiple
                    data-max-size="2048">

                @if (!empty($webpage_attachments))
                    <div class="mt-2">
                        <p>Current attachments:</p>
                        <ul class="list-group">
                            @foreach ($webpage_attachments as $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ basename($attachment) }}
                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank"
                                        class="btn btn-sm btn-info">View</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class=" d-flex justify-content-end mt-3 gap-2">

            <a href="{{ route('onboarding.physical_devices') }}" class="btn btn-secondary rounded-0 px-5 fw-semibold">
                Previous
            </a>
            <button type="submit" class="btn rounded-0 text-white px-5 fw-semibold"
                style="background-color: #0369A1; border-color: #0369A1;">Next</button>
        </div>

    </form>

@endsection
