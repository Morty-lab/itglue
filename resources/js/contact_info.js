document.getElementById("addEmployeeBtn").addEventListener("click", function() {
    let employeeContainer = document.getElementById("employeeContainer");
    let index = employeeContainer.children.length;

    let newEmployeeFields = document.createElement("div");
    newEmployeeFields.classList.add("employee-fields", "mt-3");

    newEmployeeFields.innerHTML = `
        <p class="fw-semibold">Employee Account ${index + 1}</p>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="first_name_${index}">First Name</label>
                <input class="form-control form-control-sm" type="text" id="first_name_${index}" name="employees[${index}][first_name]" required>
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="last_name_${index}">Last Name</label>
                <input class="form-control form-control-sm" type="text" id="last_name_${index}" name="employees[${index}][last_name]" required>
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="title_${index}">Title</label>
                <input class="form-control form-control-sm" type="text" id="title_${index}" name="employees[${index}][title]" required>
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="email_${index}">Email</label>
                <input class="form-control form-control-sm" type="email" id="email_${index}" name="employees[${index}][email]" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-control-sm ps-0" for="working_location_${index}">Working Location</label>
            <input class="form-control form-control-sm" type="text" id="working_location_${index}" name="employees[${index}][working_location]" required>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="mobile_number_${index}">Mobile Number</label>
                <input class="form-control form-control-sm" type="tel" id="mobile_number_${index}" name="employees[${index}][mobile_number]" required>
            </div>
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0" for="work_phone_${index}">Work Phone</label>
                <input class="form-control form-control-sm" type="tel" id="work_phone_${index}" name="employees[${index}][work_phone]" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-control-sm ps-0" for="notes_${index}">Notes</label>
            <textarea class="form-control form-control-sm" rows="3" id="notes_${index}" name="employees[${index}][notes]" ></textarea>
        </div>
        <button type="button" class="btn btn-outline-danger rounded-0 btn-sm remove-employee">Remove</button>
    `;

    // Append the new employee fields to the container
    employeeContainer.appendChild(newEmployeeFields);

    // Attach event listener to remove button
    newEmployeeFields.querySelector(".remove-employee").addEventListener("click", function() {
        employeeContainer.removeChild(newEmployeeFields);
    });

    console.log(index);
});

