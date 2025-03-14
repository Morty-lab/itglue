@extends('layouts.onboarding')
@section('content')
    <form action="{{ route('onboarding.software_licenses.store', ['user_id' => auth()->user()->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="tab">
            <div class="mb-3 w-100">
                <div class="d-flex justify-content-between align-items-center mb-3 ms-2">
                    <label class="form-control-sm ps-0">Software Licenses</label>
                    <button type="button" class="btn btn-sm btn-primary add-license">Add License</button>
                </div>
                <div id="license-container">
                    @if(isset($software_licenses) && $software_licenses->count() > 0)
                        @foreach($software_licenses as $index => $license)
                            <div class="d-flex align-items-center mb-2 license-entry">
                                <input type="hidden" name="software_licenses[{{ $index }}][id]" value="{{ $license->id }}">
                                <select class="form-select form-select-sm license-select" name="software_licenses[{{ $index }}][name]" required>
                                    <option disabled>Please select software licenses used</option>
                                    <option value="Adobe" {{ $license->software_license == 'Adobe' ? 'selected' : '' }}>Adobe</option>
                                    <option value="Acronis" {{ $license->software_license == 'Acronis' ? 'selected' : '' }}>Acronis</option>
                                    <option value="Autodesk/AutoCAD" {{ $license->software_license == 'Autodesk/AutoCAD' ? 'selected' : '' }}>Autodesk/AutoCAD</option>
                                    <option value="CADSketch" {{ $license->software_license == 'CADSketch' ? 'selected' : '' }}>CADSketch</option>
                                    <option value="Citrix" {{ $license->software_license == 'Citrix' ? 'selected' : '' }}>Citrix</option>
                                    <option value="Intuit" {{ $license->software_license == 'Intuit' ? 'selected' : '' }}>Intuit</option>
                                    <option value="Microsoft" {{ $license->software_license == 'Microsoft' ? 'selected' : '' }}>Microsoft</option>
                                    <option value="Microsoft SPLA" {{ $license->software_license == 'Microsoft SPLA' ? 'selected' : '' }}>Microsoft SPLA</option>
                                    <option value="Nitro" {{ $license->software_license == 'Nitro' ? 'selected' : '' }}>Nitro</option>
                                    <option value="SAGE" {{ $license->software_license == 'SAGE' ? 'selected' : '' }}>SAGE</option>
                                    <option value="Sketchup" {{ $license->software_license == 'Sketchup' ? 'selected' : '' }}>Sketchup</option>
                                    <option value="Vision Solutions" {{ $license->software_license == 'Vision Solutions' ? 'selected' : '' }}>Vision Solutions</option>
                                    <option value="V-Ray" {{ $license->software_license == 'V-Ray' ? 'selected' : '' }}>V-Ray</option>
                                    <option value="Sophos AV" {{ $license->software_license == 'Sophos AV' ? 'selected' : '' }}>Sophos AV</option>
                                    <option value="Other" {{ !in_array($license->software_license, ['Adobe', 'Acronis', 'Autodesk/AutoCAD', 'CADSketch', 'Citrix', 'Intuit', 'Microsoft', 'Microsoft SPLA', 'Nitro', 'SAGE', 'Sketchup', 'Vision Solutions', 'V-Ray', 'Sophos AV']) ? 'selected' : '' }}>Other</option>
                                </select>
                                <input type="text" class="form-control form-control-sm ms-2 license-name {{ !in_array($license->software_license, ['Adobe', 'Acronis', 'Autodesk/AutoCAD', 'CADSketch', 'Citrix', 'Intuit', 'Microsoft', 'Microsoft SPLA', 'Nitro', 'SAGE', 'Sketchup', 'Vision Solutions', 'V-Ray', 'Sophos AV']) ? '' : 'd-none' }}"
                                    placeholder="Enter software name" name="software_licenses[{{ $index }}][other_name]" value="{{ !in_array($license->software_license, ['Adobe', 'Acronis', 'Autodesk/AutoCAD', 'CADSketch', 'Citrix', 'Intuit', 'Microsoft', 'Microsoft SPLA', 'Nitro', 'SAGE', 'Sketchup', 'Vision Solutions', 'V-Ray', 'Sophos AV']) ? $license->software_license : '' }}">
                                <input type="number" class="form-control form-control-sm ms-2 license-qty"
                                    placeholder="Quantity" min="1" name="software_licenses[{{ $index }}][qty]" value="{{ $license->quantity }}" required>
                                <button type="button" class="btn btn-sm btn-danger ms-2 remove-license">✕</button>
                            </div>
                        @endforeach
                    {{-- @else
                        <div class="d-flex align-items-center mb-2 license-entry">
                            <select class="form-select form-select-sm license-select" name="software_licenses[0][name]" required>
                                <option selected disabled>Please select software licenses used</option>
                                <option value="Adobe">Adobe</option>
                                <option value="Acronis">Acronis</option>
                                <option value="Autodesk/AutoCAD">Autodesk/AutoCAD</option>
                                <option value="CADSketch">CADSketch</option>
                                <option value="Citrix">Citrix</option>
                                <option value="Intuit">Intuit</option>
                                <option value="Microsoft">Microsoft</option>
                                <option value="Microsoft SPLA">Microsoft SPLA</option>
                                <option value="Nitro">Nitro</option>
                                <option value="SAGE">SAGE</option>
                                <option value="Sketchup">Sketchup</option>
                                <option value="Vision Solutions">Vision Solutions</option>
                                <option value="V-Ray">V-Ray</option>
                                <option value="Sophos AV">Sophos AV</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" class="form-control form-control-sm ms-2 license-name d-none"
                                placeholder="Enter software name" name="software_licenses[0][other_name]">
                            <input type="number" class="form-control form-control-sm ms-2 license-qty"
                                placeholder="Quantity" min="1" name="software_licenses[0][qty]" required>
                            <button type="button" class="btn btn-sm btn-danger ms-2 remove-license">✕</button>
                        </div> --}}
                    @endif
                </div>

                <div class="mb-3">
                    <label for="formFileSm" class="form-control-sm ps-0">Attachment (Optional)</label>
                    <input class="form-control form-control-sm" id="licenseFiles" type="file" name="licenses_files[]"
                        multiple data-max-size="2048">
                        @if(isset($license_attachments) && count($license_attachments) > 0)
                    <div class="mb-3 mt-3">
                        <label class="form-control-sm ps-0">Existing Attachments</label>
                        <div class="list-group">
                            @foreach($license_attachments as $attachment)
                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-file me-2"></i>
                                        {{ basename($attachment) }}
                                    </span>
                                    <a href="{{ asset('storage/' . $attachment) }}" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
@endif
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-3 gap-2">

            <a href="{{ route('onboarding.webpage_development') }}" class="btn btn-secondary rounded-0 px-5 fw-semibold">
                Previous
            </a>
            <button type="submit" class="btn rounded-0 text-white px-5 fw-semibold"
                style="background-color: #0369A1; border-color: #0369A1;">Submit</button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initial license count
            let licenseCount = {{ isset($software_licenses) ? $software_licenses->count() : 1 }};

            // Function to handle "Other" selection
            function handleOtherSelection() {
                document.querySelectorAll('.license-select').forEach(select => {
                    select.addEventListener('change', function() {
                        const otherNameField = this.closest('.license-entry').querySelector('.license-name');
                        if (this.value === 'Other') {
                            otherNameField.classList.remove('d-none');
                            otherNameField.setAttribute('required', 'required');
                        } else {
                            otherNameField.classList.add('d-none');
                            otherNameField.removeAttribute('required');
                        }
                    });
                });
            }

            // Add License button click
            // document.querySelector('.add-license').addEventListener('click', function() {
            //     const licenseContainer = document.getElementById('license-container');
            //     const newLicenseEntry = document.createElement('div');
            //     newLicenseEntry.className = 'd-flex align-items-center mb-2 license-entry';

            //     newLicenseEntry.innerHTML = `
            //         <select class="form-select form-select-sm license-select" name="software_licenses[${licenseCount}][name]" required>
            //             <option selected disabled>Please select software licenses used SHAMALAM</option>
            //             <option value="Adobe">Adobe</option>
            //             <option value="Acronis">Acronis</option>
            //             <option value="Autodesk/AutoCAD">Autodesk/AutoCAD</option>
            //             <option value="CADSketch">CADSketch</option>
            //             <option value="Citrix">Citrix</option>
            //             <option value="Intuit">Intuit</option>
            //             <option value="Microsoft">Microsoft</option>
            //             <option value="Microsoft SPLA">Microsoft SPLA</option>
            //             <option value="Nitro">Nitro</option>
            //             <option value="SAGE">SAGE</option>
            //             <option value="Sketchup">Sketchup</option>
            //             <option value="Vision Solutions">Vision Solutions</option>
            //             <option value="V-Ray">V-Ray</option>
            //             <option value="Sophos AV">Sophos AV</option>
            //             <option value="Other">Other</option>
            //         </select>
            //         <input type="text" class="form-control form-control-sm ms-2 license-name d-none"
            //             placeholder="Enter software name" name="software_licenses[${licenseCount}][other_name]">
            //         <input type="number" class="form-control form-control-sm ms-2 license-qty"
            //             placeholder="Quantity" min="1" name="software_licenses[${licenseCount}][qty]" required>
            //         <button type="button" class="btn btn-sm btn-danger ms-2 remove-license">✕</button>
            //     `;

            //     licenseContainer.appendChild(newLicenseEntry);
            //     licenseCount++;

            //     // Update event listeners for the new elements
            //     handleOtherSelection();
            // });

            // Remove License button click (using event delegation)
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-license')) {
                    const licenseEntries = document.querySelectorAll('.license-entry');

                    // Don't remove if it's the last one
                    if (licenseEntries.length > 1) {
                        e.target.closest('.license-entry').remove();
                    } else {
                        // Reset the fields if it's the last one
                        const lastEntry = licenseEntries[0];
                        const select = lastEntry.querySelector('.license-select');
                        const nameField = lastEntry.querySelector('.license-name');
                        const qtyField = lastEntry.querySelector('.license-qty');

                        select.value = 'Please select software licenses used';
                        nameField.value = '';
                        nameField.classList.add('d-none');
                        qtyField.value = '';
                    }
                }
            });

            // Initialize the "Other" selection handler
            handleOtherSelection();

            // Trigger the change event for any selects that already have "Other" selected
            document.querySelectorAll('.license-select').forEach(select => {
                if (select.value === 'Other') {
                    const event = new Event('change');
                    select.dispatchEvent(event);
                }
            });
        });
    </script>
@endsection
