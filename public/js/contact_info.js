function addEmployee(employeeData = {}) {
    let employeeContainer = document.getElementById("employeeContainer");
    let index = employeeContainer.children.length;

    if (!employeeData || typeof employeeData !== 'object') {
        employeeData = {}; // Ensure employeeData is an object
    }

    let newEmployeeFields = document.createElement("div");
    newEmployeeFields.classList.add("employee-fields", "mt-3");

    newEmployeeFields.innerHTML = `
        <p class="fw-semibold">Employee Account ${index + 1}</p>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="first_name_${index}">First Name</label>
                <input class="form-control form-control-sm" type="text" id="first_name_${index}" name="employees[${index}][first_name]" value="${employeeData.first_name || ''}" required>
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="last_name_${index}">Last Name</label>
                <input class="form-control form-control-sm" type="text" id="last_name_${index}" name="employees[${index}][last_name]" value="${employeeData.last_name || ''}" required>
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="title_${index}">Title</label>
                <input class="form-control form-control-sm" type="text" id="title_${index}" name="employees[${index}][title]" value="${employeeData.title || ''}" required>
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="email_${index}">Email</label>
                <input class="form-control form-control-sm" type="email" id="email_${index}" name="employees[${index}][email]" value="${employeeData.email || ''}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-control-sm ps-0" for="working_location_${index}">Working Location</label>
            <input class="form-control form-control-sm" type="text" id="working_location_${index}" name="employees[${index}][working_location]" value="${employeeData.working_location || ''}" required>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="mobile_number_${index}">Mobile Number</label>
                <input class="form-control form-control-sm" type="tel" id="mobile_number_${index}" name="employees[${index}][mobile_number]" value="${employeeData.mobile_number || ''}" required>
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="work_phone_${index}">Work Phone</label>
                <input class="form-control form-control-sm" type="tel" id="work_phone_${index}" name="employees[${index}][work_phone]" value="${employeeData.work_phone || ''}" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-control-sm ps-0" for="notes_${index}">Notes</label>
            <textarea class="form-control form-control-sm" rows="3" id="notes_${index}" name="employees[${index}][notes]">${employeeData.notes || ''}</textarea>
        </div>
        <button type="button" class="btn btn-outline-danger rounded-0 btn-sm remove-employee">Remove</button>
    `;

    employeeContainer.appendChild(newEmployeeFields);

    // Add event listener to remove button
    newEmployeeFields.querySelector(".remove-employee").addEventListener("click", function () {
        newEmployeeFields.remove();
        saveEmployees(); // Update storage on remove
    });

    // Add change listeners to update localStorage when input fields are modified
    newEmployeeFields.querySelectorAll("input, textarea").forEach(input => {
        input.addEventListener("input", saveEmployees);
    });

    saveEmployees(); // Save after adding
}

// Save employees to localStorage
function saveEmployees() {
    let employeeContainer = document.getElementById("employeeContainer");
    let employees = [];

    employeeContainer.querySelectorAll(".employee-fields").forEach((employeeDiv, index) => {
        let employeeData = {};
        employeeDiv.querySelectorAll("input, textarea").forEach(input => {
            let keyMatch = input.name.match(/\[([a-z_]+)\]$/);
            if (keyMatch) {
                employeeData[keyMatch[1]] = input.value;
            }
        });
        employees.push(employeeData);
    });

    localStorage.setItem("employeesData", JSON.stringify(employees));
}

// Load employees from localStorage
function loadEmployees() {
    try {
        let employeeContainer = document.getElementById("employeeContainer");
        employeeContainer.innerHTML = ""; // Clear existing employees to prevent duplication

        let savedEmployees = localStorage.getItem("employeesData");
        if (savedEmployees) {
            let employees = JSON.parse(savedEmployees);
            employees.forEach(employee => {
                if (employee && typeof employee === 'object') {
                    addEmployee(employee);
                }
            });
        }
    } catch (error) {
        console.error("Error loading employees:", error);
    }
}


// Load employees on page load
document.addEventListener("DOMContentLoaded", function () {
    // loadEmployees();
});

// Add new employee button listener
document.getElementById("addEmployeeBtn").addEventListener("click", function () {
    addEmployee();
});
