document.addEventListener("DOMContentLoaded", function () {
    const licenseContainer = document.getElementById("license-container");
    const addLicenseBtn = document.querySelector(".add-license");

    let index = 0;

    // Load licenses from localStorage
    function loadLicenses() {
        const storedLicenses =
            JSON.parse(localStorage.getItem("software_licenses")) || [];

        licenseContainer.innerHTML = ""; // Clear only if data exists
        index = storedLicenses.length;
        storedLicenses.forEach((license, i) => addNewLicenseEntry(license, i));
    }

    // Save licenses to localStorage
    function saveLicenses() {
        const licenses = [...document.querySelectorAll(".license-entry")].map(
            (entry, i) => ({
                name: entry.querySelector(".license-select").value,
                other_name: entry.querySelector(".license-name").value,
                qty: entry.querySelector(".license-qty").value,
            })
        );
        localStorage.setItem("software_licenses", JSON.stringify(licenses));
    }

    // Function to handle license selection
    function handleLicenseChange(selectElement) {
        const entryDiv = selectElement.closest(".license-entry");
        const nameInput = entryDiv.querySelector(".license-name");
        const qtyInput = entryDiv.querySelector(".license-qty");

        if (selectElement.value === "Other") {
            nameInput.classList.remove("d-none");
            nameInput.required = true;
        } else {
            nameInput.classList.add("d-none");
            nameInput.required = false;
        }

        if (selectElement.value) {
            qtyInput.classList.remove("d-none");
            qtyInput.required = true;
        } else {
            qtyInput.classList.add("d-none");
            qtyInput.required = false;
        }

        saveLicenses(); // Save changes when selection changes
    }

    // Function to add a new license entry
    function addNewLicenseEntry(licenseData = {}, i = index) {
        const newEntry = document.createElement("div");
        newEntry.classList.add(
            "d-flex",
            "align-items-center",
            "mb-2",
            "license-entry"
        );

        newEntry.innerHTML = `
            <select name="software_licenses[${i}][name]" class="form-select form-select-sm license-select">
                <option selected value="">Please select software licenses used</option>
                <option value="Adobe" ${
                    licenseData.name === "Adobe" ? "selected" : ""
                }>Adobe</option>
                <option value="Acronis" ${
                    licenseData.name === "Acronis" ? "selected" : ""
                }>Acronis</option>
                <option value="Autodesk/AutoCAD" ${
                    licenseData.name === "Autodesk/AutoCAD" ? "selected" : ""
                }>Autodesk/AutoCAD</option>
                <option value="CADSketch" ${
                    licenseData.name === "CADSketch" ? "selected" : ""
                }>CADSketch</option>
                <option value="Citrix" ${
                    licenseData.name === "Citrix" ? "selected" : ""
                }>Citrix</option>
                <option value="Intuit" ${
                    licenseData.name === "Intuit" ? "selected" : ""
                }>Intuit</option>
                <option value="Microsoft" ${
                    licenseData.name === "Microsoft" ? "selected" : ""
                }>Microsoft</option>
                <option value="Microsoft SPLA" ${
                    licenseData.name === "Microsoft SPLA" ? "selected" : ""
                }>Microsoft SPLA</option>
                <option value="Nitro" ${
                    licenseData.name === "Nitro" ? "selected" : ""
                }>Nitro</option>
                <option value="SAGE" ${
                    licenseData.name === "SAGE" ? "selected" : ""
                }>SAGE</option>
                <option value="Sketchup" ${
                    licenseData.name === "Sketchup" ? "selected" : ""
                }>Sketchup</option>
                <option value="Vision Solutions" ${
                    licenseData.name === "Vision Solutions" ? "selected" : ""
                }>Vision Solutions</option>
                <option value="V-Ray" ${
                    licenseData.name === "V-Ray" ? "selected" : ""
                }>V-Ray</option>
                <option value="Other" ${
                    licenseData.name === "Other" ? "selected" : ""
                }>Other</option>
                <option value="Sophos AV" ${
                    licenseData.name === "Sophos AV" ? "selected" : ""
                }>Sophos AV</option>
            </select>
            <input type="text" name="software_licenses[${i}][other_name]" class="form-control form-control-sm ms-2 license-name ${
            licenseData.name === "Other" ? "" : "d-none"
        }"
                   placeholder="Enter software name" value="${
                       licenseData.other_name || ""
                   }">
            <input type="number" name="software_licenses[${i}][qty]" class="form-control form-control-sm ms-2 license-qty ${
            licenseData.name ? "" : "d-none"
        }"
                   placeholder="Quantity" min="1" value="${
                       licenseData.qty || ""
                   }">
            <button type="button" class="btn btn-sm btn-danger ms-2 remove-license">-</button>
        `;

        // Append new entry
        licenseContainer.appendChild(newEntry);

        // Add event listeners to new elements
        newEntry
            .querySelector(".license-select")
            .addEventListener("change", function () {
                handleLicenseChange(this);
            });

        newEntry
            .querySelector(".license-name")
            .addEventListener("input", saveLicenses);
        newEntry
            .querySelector(".license-qty")
            .addEventListener("input", saveLicenses);

        newEntry
            .querySelector(".remove-license")
            .addEventListener("click", function () {
                newEntry.remove();
                saveLicenses(); // Save changes after removal
                loadLicenses(); // Refresh list to maintain order
            });

        index++;
        saveLicenses(); // Save newly added license
    }

    // Attach event listener to initial "+" button
    addLicenseBtn.addEventListener("click", function () {
        addNewLicenseEntry();
    });

     // Attach event listener to initial dropdown
     document.querySelector(".license-select").addEventListener("change", function () {
        handleLicenseChange(this);
    });


    // Load stored licenses on page load
    // loadLicenses();
});
