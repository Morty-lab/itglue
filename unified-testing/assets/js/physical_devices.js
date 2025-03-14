document.addEventListener("DOMContentLoaded", function() {
    function toggleOtherDeviceInput(selectElement) {
        let otherDeviceContainer = selectElement.closest(".d-flex").parentNode.querySelector(".other-device-container");
        if (selectElement.value === "8") {
            otherDeviceContainer.style.display = "block";
        } else {
            otherDeviceContainer.style.display = "none";
        }
    }

    // Attach event listener to all existing dropdowns
    document.querySelectorAll(".device-type-select").forEach(select => {
        select.addEventListener("change", function() {
            toggleOtherDeviceInput(this);
        });
    });

    document.getElementById("addDeviceBtn").addEventListener("click", function() {
        let deviceContainer = document.getElementById("deviceContainer");
        let newDeviceFields = document.createElement("div");
        newDeviceFields.classList.add("device-fields", "mt-3");

        newDeviceFields.innerHTML = `
            <p class="fw-semibold">Physical Device</p>
            <div class="d-flex justify-content-between gap-2">
                <div class="mb-2 w-100">
                    <label class="form-control-sm ps-0">Device Type</label>
                    <select class="form-select form-select-sm device-type-select" aria-label="Select device type">
                        <option selected>Select device type</option>
                        <option value="1">Printer</option>
                        <option value="2">Server</option>
                        <option value="3">Laptop</option>
                        <option value="4">Desktop</option>
                        <option value="5">Mobile</option>
                        <option value="6">Firewall</option>
                        <option value="7">Network Devices</option>
                        <option value="8">Others</option>
                    </select>
                </div> 
                <div class="mb-3 w-100 other-device-container" style="display: none;">
                    <label class="form-control-sm ps-0">Other Device Type</label>
                    <input class="form-control form-control-sm" type="text" placeholder="Specify device type if not listed">
                </div>
            </div>
            <div class="d-flex justify-content-between gap-2">
                <div class="mb-3 w-100">
                    <label class="form-control-sm ps-0">Device Name</label>
                    <input class="form-control form-control-sm" type="text">
                </div> 
                <div class="mb-3 w-100">
                    <label class="form-control-sm ps-0">Device Username</label>
                    <input class="form-control form-control-sm" type="text">
                </div>
            </div>
            <div class="d-flex justify-content-between gap-2">
                <div class="mb-3 w-100">
                    <label class="form-control-sm ps-0">Primary Password</label>
                    <input class="form-control form-control-sm" type="text">
                </div> 
                <div class="mb-3 w-100">
                    <label class="form-control-sm ps-0">IP Address</label>
                    <input class="form-control form-control-sm" type="text">
                </div>
            </div> 
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Location</label>
                <input class="form-control form-control-sm" type="text">
            </div>
            <div class="mb-3">
                <label class="form-control-sm ps-0">Notes</label>
                <textarea class="form-control form-control-sm" rows="3"></textarea>
            </div>
            <button type="button" class="btn my-2 btn-outline-danger rounded-0 btn-sm remove-device">Remove</button>
        `;

        deviceContainer.appendChild(newDeviceFields);

        // Attach event listener to remove button
        newDeviceFields.querySelector(".remove-device").addEventListener("click", function() {
            deviceContainer.removeChild(newDeviceFields);
        });

        // Attach event listener to the new device type dropdown
        newDeviceFields.querySelector(".device-type-select").addEventListener("change", function() {
            toggleOtherDeviceInput(this);
        });
    });
});
