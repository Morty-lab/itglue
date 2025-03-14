let device_index = 0;
const deviceContainer = document.getElementById("deviceContainer");
const addDeviceBtn = document.getElementById("addDeviceBtn");

// Load devices from localStorage
function loadDevices() {
    deviceContainer.innerHTML = ""; // Clear existing device fields
    const storedDevices = JSON.parse(localStorage.getItem("devices")) || [];
    device_index = storedDevices.length;
    storedDevices.forEach((device, index) => addDevice(device, index));
}

// Save devices to localStorage
function saveDevices() {
    const devices = [...document.querySelectorAll(".device-fields")].map((field, index) => {
        return {
            type: field.querySelector("select").value,
            other_type: field.querySelector("input[name*='other_type']").value,
            name: field.querySelector("input[name*='name']").value,
            username: field.querySelector("input[name*='username']").value,
            primary_password: field.querySelector("input[name*='primary_password']").value,
            additional_passwords: field.querySelector("input[name*='additional_passwords']").value,
            ip_address: field.querySelector("input[name*='ip_address']").value,
            location: field.querySelector("input[name*='location']").value,
            notes: field.querySelector("textarea").value
        };
    });
    localStorage.setItem("devices", JSON.stringify(devices));
}

// Update devices when input changes
function updateDevices() {
    document.querySelectorAll(".device-fields input, .device-fields select, .device-fields textarea").forEach(input => {
        input.addEventListener("input", saveDevices);
    });
}

// Add new device field
function addDevice(deviceData = {}, index = device_index) {
    let newDeviceFields = document.createElement("div");
    newDeviceFields.classList.add("device-fields", "mt-3");
    newDeviceFields.innerHTML = `
        <p class="fw-semibold">Physical Device ${index + 1}</p>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-2 w-100">
                <label class="form-control-sm ps-0">Device Type</label>
                <select class="form-select form-select-sm" name="devices[${index}][type]">
                    <option value="1" ${deviceData.type === "1" ? "selected" : ""}>Printer</option>
                    <option value="2" ${deviceData.type === "2" ? "selected" : ""}>Server</option>
                    <option value="3" ${deviceData.type === "3" ? "selected" : ""}>Laptop</option>
                    <option value="4" ${deviceData.type === "4" ? "selected" : ""}>Desktop</option>
                    <option value="5" ${deviceData.type === "5" ? "selected" : ""}>Mobile</option>
                    <option value="6" ${deviceData.type === "6" ? "selected" : ""}>Firewall</option>
                    <option value="7" ${deviceData.type === "7" ? "selected" : ""}>Network Devices</option>
                    <option value="other" ${deviceData.type === "other" ? "selected" : ""}>other</option>

                </select>
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Other Device Type</label>
                <input class="form-control form-control-sm" type="text" name="devices[${index}][other_type]" value="${deviceData.other_type || ''}">
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Device Name</label>
                <input class="form-control form-control-sm" type="text" name="devices[${index}][name]" value="${deviceData.name || ''}">
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Device Username</label>
                <input class="form-control form-control-sm" type="text" name="devices[${index}][username]" value="${deviceData.username || ''}">
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Primary Password</label>
                <input class="form-control form-control-sm" type="text" name="devices[${index}][primary_password]" value="${deviceData.primary_password || ''}">
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Additional Passwords (if any)</label>
                <input class="form-control form-control-sm" type="password" name="devices[${index}][additional_passwords]" value="${deviceData.additional_passwords || ''}">
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">IP Address</label>
                <input class="form-control form-control-sm" type="text" name="devices[${index}][ip_address]" value="${deviceData.ip_address || ''}">
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Location</label>
                <input class="form-control form-control-sm" type="text" name="devices[${index}][location]" value="${deviceData.location || ''}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-control-sm ps-0">Notes</label>
            <textarea class="form-control form-control-sm" rows="3" name="devices[${index}][notes]">${deviceData.notes || ''}</textarea>
        </div>
        <button type="button" class="btn my-2 btn-danger btn-sm remove-device">Remove</button>
    `;

    deviceContainer.appendChild(newDeviceFields);

    // Attach event listeners to update localStorage on input change
    updateDevices();

    // Remove device event
    newDeviceFields.querySelector(".remove-device").addEventListener("click", function() {
        deviceContainer.removeChild(newDeviceFields);
        device_index--;
        saveDevices(); // Update localStorage after removal
        loadDevices(); // Refresh device list to keep it in sync
    });

    device_index++;
    saveDevices(); // Save new device list
}

// Add device on button click
addDeviceBtn.addEventListener("click", function() {
    addDevice();
});

// Load stored devices on page load
window.addEventListener("load", function () {
    // loadDevices();
    updateDevices();
});
