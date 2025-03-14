
document.addEventListener("DOMContentLoaded", function () {
    const licenseContainer = document.getElementById("license-container");

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
    }

    // Function to add new license entry
    let index = 0;
    function addNewLicenseEntry() {
        index++;
        const newEntry = document.createElement("div");
        newEntry.classList.add("d-flex", "align-items-center", "mb-2", "license-entry");

        newEntry.innerHTML = `
            <select name="software_licenses[${index}][name]" class="form-select form-select-sm license-select">
                <option selected value="">Please select software licenses used</option>
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
                <option value="Other">Other</option>
                <option value="Sophos AV">Sophos AV</option>
            </select>
            <input type="text" name="software_licenses[${index}][other_name]" class="form-control form-control-sm ms-2 license-name d-none" placeholder="Enter software name">
            <input type="number" name="software_licenses[${index}][qty]" class="form-control form-control-sm ms-2 license-qty d-none" placeholder="Quantity" min="1">
            <button type="button" class="btn btn-sm btn-danger ms-2 remove-license">-</button>
        `;

        // Append new entry
        licenseContainer.appendChild(newEntry);

        // Add event listeners to new elements
        newEntry.querySelector(".license-select").addEventListener("change", function () {
            handleLicenseChange(this);
        });

        newEntry.querySelector(".remove-license").addEventListener("click", function () {
            newEntry.remove();
        });
    }

    // Attach event listener to initial dropdown
    document.querySelector(".license-select").addEventListener("change", function () {
        handleLicenseChange(this);
    });

    // Attach event listener to initial "+" button
    document.querySelector(".add-license").addEventListener("click", function () {
        addNewLicenseEntry();
        console.log(index);
    });


});

