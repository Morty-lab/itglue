@extends('layouts.onboarding')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('branches.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="tab">
            <div class="mb-3">
                <label for="company_name" class="form-control-sm ps-0">Company Name</label>
                <input name="company_name" class="form-control form-control-sm" type="text"
                    value="{{ $company ? $company->company_name : old('company_name') }}" required>
            </div>
            <div class="mb-3">
                <label for="phone_numbers" class="form-control-sm ps-0">Phone Numbers</label>
                <div class="d-flex justify-content-between gap-2">
                    <input name="primary_number" class="form-control form-control-sm" type="tel"
                        value="{{ $company ? $company->primary_number : old('primary_number') }}"
                        placeholder="Primary Number" required>
                    <input name="secondary_number" class="form-control form-control-sm" type="tel"
                        value="{{ $company ? $company->secondary_number : old('secondary_number') }}"
                        placeholder="Secondary Number">
                </div>
            </div>
            <div class="mb-3">
                <label for="hq_info" class="form-control-sm ps-0">Headquarter Information</label>
                <div class="mb-3">
                    <div class="d-flex justify-content-between gap-2">
                        <input name="hq_location_name" class="form-control form-control-sm" type="text"
                            value="{{ $company ? $company->hq_location_name : old('hq_location_name') }}"
                            placeholder="Enter your location name" required>
                        <input name="hq_address" class="form-control form-control-sm" type="text"
                            value="{{ $company ? $company->hq_address : old('hq_address') }}"
                            placeholder="Enter your address" required>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between gap-2">
                        <input name="hq_city" class="form-control form-control-sm" type="text"
                            value="{{ $company ? $company->hq_city : old('hq_city') }}" placeholder="City" required>
                        <input name="hq_state" class="form-control form-control-sm" type="text"
                            value="{{ $company ? $company->hq_state : old('hq_state') }}" placeholder="State" required>
                        <input name="hq_postal_code" class="form-control form-control-sm" type="text"
                            value="{{ $company ? $company->hq_postal_code : old('hq_postal-code') }}"
                            placeholder="Postal Code" required>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 mb-2">
                    <div class="w-full md:w-1/2">
                        @php
                            use Monarobase\CountryList\CountryList;
                            $countries = new CountryList();
                            $countryList = $countries->getList('en');
                        @endphp

                        <label for="hq_country" class="block text-sm font-medium text-gray-700">Country</label>
                        <select name="hq_country" class="form-control form-control-sm mt-1 w-full" required>
                            @foreach ($countryList as $code => $name)
                                <option value="{{ $code }}" {{ $company && $company->hq_country === $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-1/2">
                        <label for="hq_province" class="block text-sm font-medium text-gray-700">Provinces</label>
                        <select name="hq_province" id="province" class="form-control form-control-sm mt-1 w-full">
                            @foreach ([
                                'AB' => 'Alberta',
                                'BC' => 'British Columbia',
                                'MB' => 'Manitoba',
                                'NB' => 'New Brunswick',
                                'NL' => 'Newfoundland and Labrador',
                                'NS' => 'Nova Scotia',
                                'ON' => 'Ontario',
                                'PE' => 'Prince Edward Island',
                                'QC' => 'Québec',
                                'SK' => 'Saskatchewan',
                                'YT' => 'Yukon',
                                'NT' => 'Northwest Territories',
                                'NU' => 'Nunavut'
                            ] as $code => $name)
                                <option value="{{ $code }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            <div>
                <div class="d-flex justify-content-between gap-2">
                    <input name="hq_fax" class="form-control form-control-sm" type="tel"
                        value="{{ $company ? $company->hq_fax : old('hq_fax') }}" placeholder="FAX" required>
                    <input name="hq_website" class="form-control form-control-sm" type="text"
                        value="{{ $company ? $company->hq_website : old('hq_website') }}" placeholder="Website" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="office_hours" class="form-control-sm ps-0 text-secondary">Office Hours (Opening & Closing
                    Time)</label>
                <div class="d-flex justify-content-between gap-2">
                    <input name="hq_opening_time" class="form-control form-control-sm" type="time"
                        value="{{ $company ? $company->hq_opening_time : old('hq_opening_time') }}"
                        aria-label="Opening Time" required>
                    <input name="hq_closing_time" class="form-control form-control-sm" type="time"
                        value="{{ $company ? $company->hq_closing_time : old('hq_closing_time') }}"
                        aria-label="Closing Time" required>
                </div>
            </div>




        <!-- Add Branch Button -->
        <button type="button" class="btn btn-outline-secondary rounded-0 btn-sm mb-3" id="addBranchBtn">+ Add
            Branch</button>

        <!-- Container for Branches -->
        <div id="branchesContainer">
            @if (isset($branches))
                @foreach ($branches as $index => $branchData)
                    <div class="mb-3 border p-3 rounded branch-item" id="branch-{{ $index }}">
                        <div class="d-flex justify-content-between mb-2">
                            <label class="form-control-sm ps-0">Branch Information</label>
                            <button type="button" class="btn btn-sm btn-danger remove-branch"
                                data-branch-id="{{ $index }}">Remove</button>
                        </div>

                        <input type="hidden" name="branches[{{ $index }}][id]"
                            value="{{ $branchData['id'] ?? '' }}">

                        <div class="d-flex justify-content-between gap-2">
                            <input class="form-control form-control-sm branch-input" type="text"
                                placeholder="Branch Address" name="branches[{{ $index }}][address]"
                                value="{{ $branchData['branch_address'] ?? '' }}" required>
                            <input class="form-control form-control-sm branch-input" type="tel"
                                placeholder="Phone Number" name="branches[{{ $index }}][phone_number]"
                                value="{{ $branchData['phone_number'] ?? '' }}" required>
                        </div>
                        <div class="d-flex justify-content-between gap-2 mt-2">
                            <input class="form-control form-control-sm branch-input" type="tel" placeholder="FAX"
                                name="branches[{{ $index }}][fax]" value="{{ $branchData['fax'] ?? '' }}"
                                required>
                            <input class="form-control form-control-sm branch-input" type="text" placeholder="Website"
                                name="branches[{{ $index }}][website]" value="{{ $branchData['website'] ?? '' }}"
                                required>
                        </div>
                        <div class="d-flex justify-content-between gap-2 mt-2">
                            <input class="form-control form-control-sm branch-input" type="time"
                                aria-label="Opening Time" name="branches[{{ $index }}][opening_time]"
                                value="{{ $branchData['opening_time'] ?? '' }}" required>
                            <input class="form-control form-control-sm branch-input" type="time"
                                aria-label="Closing Time" name="branches[{{ $index }}][closing_time]"
                                value="{{ $branchData['closing_time'] ?? '' }}" required>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="mb-3">
            <label for="formFileSm" class="form-control-sm ps-0">Attachment (Optional)</label>
            <input name="company_attachments[]" class="form-control form-control-sm" id="companyFiles" type="file"
                multiple data-max-size="2048">

            @if ($company && $company->attachment)
                <div class="mt-2">
                    <p>Current attachments:</p>
                    <ul class="list-group">
                        @foreach (explode(',', $company->attachment) as $attachment)
                            <li class="list-group-item">
                                {{ basename($attachment) }}
                                <a href="{{ asset('storage/' . $attachment) }}" target="_blank"
                                    class="btn btn-sm btn-info float-end">View</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        </div>

        <div class="d-flex justify-content-end mt-3 gap-2">
            <button type="submit" class="btn rounded-0 text-white px-5 fw-semibold"
                style="background-color: #0369A1; border-color: #0369A1;">Next</button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Branch count for new branches
            let branchCount = {{ isset($branches) ? count($branches) : 0 }};
            let addBranchBtn = document.getElementById('addBranchBtn');

            // Remove any existing event listeners to prevent duplication
            if (addBranchBtn) {
                // Clone the button to remove all event listeners
                const newButton = addBranchBtn.cloneNode(true);
                addBranchBtn.parentNode.replaceChild(newButton, addBranchBtn);
                addBranchBtn = newButton;

                // Add the event listener to the clean button
                addBranchBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default button behavior
                    e.stopPropagation(); // Stop event propagation

                    const newIndex = branchCount;
                    console.log('Adding new branch with index:', newIndex); // Debug log

                    const branchHtml = `
                    <div class="mb-3 border p-3 rounded branch-item" id="branch-${newIndex}">
                        <div class="d-flex justify-content-between mb-2">
                            <label class="form-control-sm ps-0">Branch Information</label>
                            <button type="button" class="btn btn-sm btn-danger remove-branch"
                                    data-branch-id="${newIndex}">Remove</button>
                        </div>

                        <input type="hidden" name="branches[${newIndex}][id]" value="">

                        <div class="d-flex justify-content-between gap-2">
                            <input class="form-control form-control-sm branch-input" type="text"
                                placeholder="Branch Address" name="branches[${newIndex}][address]" required>
                            <input class="form-control form-control-sm branch-input" type="tel"
                                placeholder="Phone Number" name="branches[${newIndex}][phone_number]" required>
                        </div>
                        <div class="d-flex justify-content-between gap-2 mt-2">
                            <input class="form-control form-control-sm branch-input" type="tel" placeholder="FAX"
                                name="branches[${newIndex}][fax]" required>
                            <input class="form-control form-control-sm branch-input" type="text"
                                placeholder="Website" name="branches[${newIndex}][website]" required>
                        </div>
                        <div class="d-flex justify-content-between gap-2 mt-2">
                            <input class="form-control form-control-sm branch-input" type="time"
                                aria-label="Opening Time" name="branches[${newIndex}][opening_time]" required>
                            <input class="form-control form-control-sm branch-input" type="time"
                                aria-label="Closing Time" name="branches[${newIndex}][closing_time]" required>
                        </div>
                    </div>
                `;

                    document.getElementById('branchesContainer').insertAdjacentHTML('beforeend',
                        branchHtml);
                    branchCount++;
                    return false; // Prevent event bubbling
                });
            }

            // Remove Branch Button Click (using event delegation)
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-branch')) {
                    const branchId = e.target.getAttribute('data-branch-id');
                    const branchElement = document.getElementById('branch-' + branchId);

                    if (branchElement) {
                        branchElement.remove();
                    }
                }
            });
        });
    </script>
@endsection
