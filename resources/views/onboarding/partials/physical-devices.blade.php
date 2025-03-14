@extends('layouts.onboarding')
@section('content')
    <form action="{{ route('onboarding.physical_devices.devices.store', ['user_id' => auth()->user()->id]) }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <div class="tab" id="deviceTab">
            <div id="deviceContainer">
                @if (isset($devices))
                    @foreach ($devices as $index => $device)
                        <div class="device-fields mt-3" id="device-{{ $index }}">
                            <!-- Add hidden ID field to track existing devices -->
                            <input type="hidden" name="devices[{{ $index }}][id]" value="{{ $device->id }}">

                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-2 w-100">
                                    <label class="form-control-sm ps-0">Device Type</label>
                                    <select class="form-select form-select-sm" aria-label="Select device type"
                                        name="devices[{{ $index }}][type]" id="deviceTypeSelect-{{ $index }}"
                                        onchange="checkDeviceType({{ $index }})" required>
                                        <option selected disabled>Select device type</option>
                                        <option value="Printer" {{ $device->device_type === 'Printer' ? 'selected' : '' }}>
                                            Printer
                                        </option>
                                        <option value="Server" {{ $device->device_type === 'Server' ? 'selected' : '' }}>
                                            Server
                                        </option>
                                        <option value="Laptop" {{ $device->device_type === 'Laptop' ? 'selected' : '' }}>
                                            Laptop
                                        </option>
                                        <option value="Desktop" {{ $device->device_type === 'Desktop' ? 'selected' : '' }}>
                                            Desktop
                                        </option>
                                        <option value="Mobile" {{ $device->device_type === 'Mobile' ? 'selected' : '' }}>
                                            Mobile
                                        </option>
                                        <option value="Firewall"
                                            {{ $device->device_type === 'Firewall' ? 'selected' : '' }}>
                                            Firewall</option>
                                        <option value="Network Devices"
                                            {{ $device->device_type === 'Network Devices' ? 'selected' : '' }}>Network
                                            Devices</option>
                                        <option value="0" {{ $device->device_type === '0' ? 'selected' : '' }}>Other
                                            Device Type</option>
                                    </select>
                                </div>
                                <div class="mb-3 w-100 {{ $device->device_type !== '0' ? 'd-none' : '' }}""
                                    id="otherDeviceType-{{ $index }}">
                                    <label class="form-control-sm ps-0">Other Device Type</label>
                                    <input class="form-control form-control-sm" type="text"
                                        value="{{ $device->other_type ?? '' }}"
                                        placeholder="Specify device type if not listed"
                                        name="devices[{{ $index }}][other_type]">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Device Name</label>
                                    <input class="form-control form-control-sm" type="text"
                                        value="{{ $device->device_name ?? '' }}" name="devices[{{ $index }}][name]"
                                        required>
                                </div>
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Device Username</label>
                                    <input class="form-control form-control-sm" type="text"
                                        value="{{ $device->device_username ?? '' }}"
                                        name="devices[{{ $index }}][username]" required>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Primary Password</label>
                                    <input class="form-control form-control-sm" type="password"
                                        value="{{ $device->primary_password ?? '' }}"
                                        name="devices[{{ $index }}][primary_password]" required>
                                </div>
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Additional Passwords (if any)</label>
                                    <input class="form-control form-control-sm" type="password"
                                        value="{{ $device->additional_passwords ?? '' }}"
                                        name="devices[{{ $index }}][additional_passwords]">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between gap-2">
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">IP Address</label>
                                    <input class="form-control form-control-sm" type="text"
                                        value="{{ $device->device_ip_address ?? '' }}"
                                        name="devices[{{ $index }}][ip_address]" required>
                                </div>
                                <div class="mb-3 w-100">
                                    <label class="form-control-sm ps-0">Location</label>
                                    <input class="form-control form-control-sm" type="text"
                                        value="{{ $device->device_location ?? '' }}"
                                        name="devices[{{ $index }}][location]" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-control-sm ps-0">Notes</label>
                                <textarea class="form-control form-control-sm" rows="3" name="devices[{{ $index }}][notes]">{{ $device->notes ?? '' }}</textarea>
                            </div>

                            <!-- Add remove button -->
                            <button type="button" class="btn btn-danger btn-sm mb-3 remove-device"
                                data-device-id="{{ $index }}">
                                Remove Device
                            </button>
                            <hr>
                        </div>
                    @endforeach
                @else
                    <div class="device-fields mt-3" id="device-0">
                        <!-- Add hidden ID field -->
                        <input type="hidden" name="devices[0][id]" value="">

                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-2 w-100">
                                <label class="form-control-sm ps-0">Device Type</label>
                                <select class="form-select form-select-sm" aria-label="Select device type"
                                    name="devices[0][type]" required>
                                    <option selected disabled>Select device type</option>
                                    <option value="Printer" {{ $device->device_type === 'Printer' ? 'selected' : '' }}>
                                        Printer
                                    </option>
                                    <option value="Server" {{ $device->device_type === 'Server' ? 'selected' : '' }}>
                                        Server
                                    </option>
                                    <option value="Laptop" {{ $device->device_type === 'Laptop' ? 'selected' : '' }}>
                                        Laptop
                                    </option>
                                    <option value="Desktop" {{ $device->device_type === 'Desktop' ? 'selected' : '' }}>
                                        Desktop
                                    </option>
                                    <option value="Mobile" {{ $device->device_type === 'Mobile' ? 'selected' : '' }}>
                                        Mobile
                                    </option>
                                    <option value="Firewall" {{ $device->device_type === 'Firewall' ? 'selected' : '' }}>
                                        Firewall</option>
                                    <option value="Network Devices"
                                        {{ $device->device_type === 'Network Devices' ? 'selected' : '' }}>Network
                                        Devices</option>
                                    <option value="0">Other Device Type</option>
                                </select>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Other Device Type</label>
                                <input class="form-control form-control-sm" type="text"
                                    placeholder="Specify device type if not listed" name="devices[0][other_type]">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Device Name</label>
                                <input class="form-control form-control-sm" type="text" name="devices[0][name]"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Device Username</label>
                                <input class="form-control form-control-sm" type="text" name="devices[0][username]"
                                    required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Primary Password</label>
                                <input class="form-control form-control-sm" type="password"
                                    name="devices[0][primary_password]" required>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Additional Passwords (if any)</label>
                                <input class="form-control form-control-sm" type="password"
                                    name="devices[0][additional_passwords]">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">IP Address</label>
                                <input class="form-control form-control-sm" type="text" name="devices[0][ip_address]"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Location</label>
                                <input class="form-control form-control-sm" type="text" name="devices[0][location]"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-control-sm ps-0">Notes</label>
                            <textarea class="form-control form-control-sm" rows="3" name="devices[0][notes]"></textarea>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Add Device Button -->
            <button type="button" class="btn btn-outline-secondary rounded-0 btn-sm mb-3 mt-3" id="addDeviceBtn">+ Add
                Device</button>

            <div class="mb-3">
                <label for="formFileSm" class="form-control-sm ps-0">Attachment (Optional)</label>
                <input class="form-control form-control-sm" id="deviceFiles" type="file" name="device_files[]"
                    multiple data-max-size="2048">

                @if (!empty($device_attachments))
                    <div class="mt-2">
                        <p>Current attachments:</p>
                        <ul class="list-group">
                            @foreach ($device_attachments as $attachment)
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

            <a href="{{ route('onboarding.contact_information') }}" class="btn btn-secondary rounded-0 px-5 fw-semibold">
                Previous
            </a>
            <button type="submit" class="btn rounded-0 text-white px-5 fw-semibold"
                style="background-color: #0369A1; border-color: #0369A1;">Next</button>
        </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Device count for new devices
            let deviceCount = {{ isset($devices) ? count($devices) : 1 }};
            let addDeviceBtn = document.getElementById('addDeviceBtn');

            // Remove any existing event listeners to prevent duplication
            if (addDeviceBtn) {
                // Clone the button to remove all event listeners
                const newButton = addDeviceBtn.cloneNode(true);
                addDeviceBtn.parentNode.replaceChild(newButton, addDeviceBtn);
                addDeviceBtn = newButton;

                // Add the event listener to the clean button
                addDeviceBtn.addEventListener('click', function(e) {
                    e.preventDefault(); // Prevent default button behavior
                    e.stopPropagation(); // Stop event propagation

                    const newIndex = deviceCount;
                    console.log('Adding new device with index:', newIndex); // Debug log

                    const deviceHtml = `
                    <div class="device-fields mt-3" id="device-${newIndex}">
                        <input type="hidden" name="devices[${newIndex}][id]" value="">

                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-2 w-100">
                                <label class="form-control-sm ps-0">Device Type</label>
                                <select class="form-select form-select-sm" id="deviceTypeSelect-${newIndex}" aria-label="Select device type"
                                    name="devices[${newIndex}][type]" onchange="checkDeviceType(${newIndex})" required>
                                    <option selected disabled>Select device type</option>
                                    <option value="Printer" >
                                            Printer
                                        </option>
                                        <option value="Server" >
                                            Server
                                        </option>
                                        <option value="Laptop" >
                                            Laptop
                                        </option>
                                        <option value="Desktop" >
                                            Desktop
                                        </option>
                                        <option value="Mobile" >
                                            Mobile
                                        </option>
                                        <option value="Firewall"
                                          >
                                            Firewall</option>
                                        <option value="Network Devices"
                                            >Network
                                            Devices</option>
                                    <option value="0">Other Device Type</option>

                                </select>
                            </div>
                            <div class="mb-3 w-100 d-none" id="otherDeviceType-${newIndex}">
                                <label class="form-control-sm ps-0">Other Device Type</label>
                                <input class="form-control form-control-sm" type="text" placeholder="Specify device type if not listed"
                                    name="devices[${newIndex}][other_type]">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Device Name</label>
                                <input class="form-control form-control-sm" type="text" name="devices[${newIndex}][name]" required>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Device Username</label>
                                <input class="form-control form-control-sm" type="text" name="devices[${newIndex}][username]" required>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Primary Password</label>
                                <input class="form-control form-control-sm" type="password" name="devices[${newIndex}][primary_password]"
                                    required>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Additional Passwords (if any)</label>
                                <input class="form-control form-control-sm" type="password" name="devices[${newIndex}][additional_passwords]">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">IP Address</label>
                                <input class="form-control form-control-sm" type="text" name="devices[${newIndex}][ip_address]" required>
                            </div>
                            <div class="mb-3 w-100">
                                <label class="form-control-sm ps-0">Location</label>
                                <input class="form-control form-control-sm" type="text" name="devices[${newIndex}][location]" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-control-sm ps-0">Notes</label>
                            <textarea class="form-control form-control-sm" rows="3" name="devices[${newIndex}][notes]"></textarea>
                        </div>

                        <button type="button" class="btn btn-danger btn-sm mb-3 remove-device" data-device-id="${newIndex}">
                            Remove Device
                        </button>
                        <hr>
                    </div>
                `;

                    document.getElementById('deviceContainer').insertAdjacentHTML('beforeend', deviceHtml);
                    deviceCount++;
                    return false; // Prevent event bubbling
                });
            }

            // Remove Device Button Click (using event delegation)
            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-device')) {
                    const deviceId = e.target.getAttribute('data-device-id');
                    const deviceElement = document.getElementById('device-' + deviceId);

                    if (deviceElement) {
                        deviceElement.remove();
                    }
                }
            });
        });
    </script>

    <script>
        function checkDeviceType(index) {
            const deviceSelect = document.getElementById(`deviceTypeSelect-${index}`);

            if (deviceSelect.value == 0) {
                document.getElementById(`otherDeviceType-${index}`).classList.remove('d-none');
            } else {
                document.getElementById(`otherDeviceType-${index}`).classList.add('d-none');
            }
        }
    </script>
@endsection
