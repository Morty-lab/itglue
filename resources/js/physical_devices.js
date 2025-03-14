let device_index = 1;
document.getElementById("addDeviceBtn").addEventListener("click", function() {


    let newDeviceFields = document.createElement("div");
    newDeviceFields.classList.add("device-fields", "mt-3");

    newDeviceFields.innerHTML = `
        <p class="fw-semibold">Physical Device ${device_index + 1}</p>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-2 w-100">
                <label class="form-control-sm ps-0">Device Type</label>
                <select class="form-select form-select-sm" name="devices[${device_index}][type]" aria-label="Select device type">
                    <option selected>Select device type</option>
                    <option value="1">Printer</option>
                    <option value="2">Server</option>
                    <option value="3">Laptop</option>
                    <option value="4">Desktop</option>
                    <option value="5">Mobile</option>
                    <option value="6">Firewall</option>
                    <option value="7">Network Devices</option>
                </select>
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Other Device Type</label>
                <input class="form-control form-control-sm" type="text" name="devices[${device_index}][other_type]" placeholder="Specify device type if not listed">
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Device Name</label>
                <input class="form-control form-control-sm" type="text" name="devices[${device_index}][name]">
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Device Username</label>
                <input class="form-control form-control-sm" type="text" name="devices[${device_index}][username]">
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Primary Password</label>
                <input class="form-control form-control-sm" type="text" name="devices[${device_index}][primary_password]">
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Additional Passwords (if any)</label>
                <input class="form-control form-control-sm" type="password" name="devices[${device_index}][additional_passwords]">
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">IP Address</label>
                <input class="form-control form-control-sm" type="text" name="devices[${device_index}][ip_address]">
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Location</label>
                <input class="form-control form-control-sm" type="text" name="devices[${device_index}][location]">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-control-sm ps-0">Notes</label>
            <textarea class="form-control form-control-sm" rows="3" name="devices[${device_index}][notes]"></textarea>
        </div>
        <button type="button" class="btn my-2 btn-danger btn-sm remove-device">Remove</button>
    `;

    // Append the new device fields to the container
    deviceContainer.appendChild(newDeviceFields);

    // Attach event listener to remove button
    newDeviceFields.querySelector(".remove-device").addEventListener("click", function() {
        deviceContainer.removeChild(newDeviceFields);
    });

    device_index++;

    console.log(device_index);
});

