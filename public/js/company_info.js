let index = 0;

document.addEventListener("DOMContentLoaded", function () {
    console.log("Loading stored branches...");
    // loadBranches(); // Load branches from local storage on page load
});

document.getElementById("addBranchBtn").addEventListener("click", function () {
    addBranch();
});

function addBranch(branchData = {}, save = true) {
    let branchContainer = document.getElementById("branchesContainer");

    let branchDiv = document.createElement("div");
    branchDiv.classList.add("mb-3", "border", "p-3", "rounded");
    branchDiv.dataset.index = index; // Store index as a dataset attribute

    branchDiv.innerHTML = `
        <label class="form-control-sm ps-0">Branch Information</label>
        <div class="d-flex justify-content-between gap-2">
            <input class="form-control form-control-sm branch-input" type="text" placeholder="Branch Address" name="branches[${index}][address]" value="${branchData.address || ''}" required>
            <input class="form-control form-control-sm branch-input" type="tel" placeholder="Phone Number" name="branches[${index}][phone_number]" value="${branchData.phone_number || ''}" required>
        </div>
        <div class="d-flex justify-content-between gap-2 mt-2">
            <input class="form-control form-control-sm branch-input" type="tel" placeholder="FAX" name="branches[${index}][fax]" value="${branchData.fax || ''}" required>
            <input class="form-control form-control-sm branch-input" type="text" placeholder="Website" name="branches[${index}][website]" value="${branchData.website || ''}" required>
        </div>
        <div class="d-flex justify-content-between gap-2 mt-2">
            <input class="form-control form-control-sm branch-input" type="time" aria-label="Opening Time" name="branches[${index}][opening_time]" value="${branchData.opening_time || ''}" required>
            <input class="form-control form-control-sm branch-input" type="time" aria-label="Closing Time" name="branches[${index}][closing_time]" value="${branchData.closing_time || ''}" required>
        </div>
        <button type="button" class="btn btn-outline-danger rounded-0 btn-sm mt-2 removeBranch">Remove Branch</button>
    `;

    branchContainer.appendChild(branchDiv);

    // Attach input listeners to update local storage
    branchDiv.querySelectorAll(".branch-input").forEach(input => {
        input.addEventListener("input", function () {
            updateBranchData(branchDiv.dataset.index, input.name.match(/\[([a-z_]+)\]$/)[1], input.value);
        });
    });

    // Remove branch event
    branchDiv.querySelector(".removeBranch").addEventListener("click", function () {
        removeBranch(branchDiv.dataset.index);
        branchDiv.remove();
    });

    if (save) {
        saveBranchData(index, branchData);
    }

    index++;
}

// Save or update a single branch in local storage
function saveBranchData(branchIndex, branchData) {
    let branches = JSON.parse(localStorage.getItem("branchesData")) || [];
    branches[branchIndex] = branchData;
    localStorage.setItem("branchesData", JSON.stringify(branches));
    console.log("Saved branches:", branches);
}

// Update a branch's data in local storage dynamically
function updateBranchData(branchIndex, key, value) {
    let branches = JSON.parse(localStorage.getItem("branchesData")) || [];
    if (!branches[branchIndex]) {
        branches[branchIndex] = {};
    }
    branches[branchIndex][key] = value;
    localStorage.setItem("branchesData", JSON.stringify(branches));
    console.log(`Updated branch ${branchIndex}:`, branches[branchIndex]);
}

// Remove a branch from local storage
function removeBranch(branchIndex) {
    let branches = JSON.parse(localStorage.getItem("branchesData")) || [];
    branches.splice(branchIndex, 1);
    localStorage.setItem("branchesData", JSON.stringify(branches));
    console.log(`Removed branch ${branchIndex}, updated branches:`, branches);
}

// Load branches from local storage
function loadBranches() {
    try {
        let savedBranches = localStorage.getItem("branchesData");
        if (savedBranches) {
            let branches = JSON.parse(savedBranches);
            branches.forEach(branch => addBranch(branch, false));
            index = branches.length; // Update index based on loaded branches
        }
    } catch (error) {
        console.error("Error loading branches:", error);
    }
}

function sendBranchesToServer() {
    let branches = JSON.parse(localStorage.getItem("branchesData")) || [];

    if (branches.length === 0) {
        console.warn("No branches to send.");
        return;
    }

    axios.post("/onboarding-form/store", {
        branches: branches
    })
    .then(response => {
        console.log("Data successfully sent!", response.data);
        alert("Branches have been saved to the server!");
        // Optionally, clear local storage after successful submission
        localStorage.removeItem("branchesData");
    })
    .catch(error => {
        console.error("Error sending data:", error);
        alert("Failed to save branches. Check the console for details.");
    });
}

