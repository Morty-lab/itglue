document.getElementById("addEmployeeBtn").addEventListener("click", function() {
    let employeeContainer = document.getElementById("employeeContainer");
    
    let newEmployeeFields = document.createElement("div");
    newEmployeeFields.classList.add("employee-fields", "mt-3");
    
    newEmployeeFields.innerHTML = `
        <p class="fw-semibold">Employee Account</p>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">First Name</label>
                <input class="form-control form-control-sm" type="text">
            </div> 
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Last Name</label>
                <input class="form-control form-control-sm" type="text">
            </div>
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Title</label>
                <input class="form-control form-control-sm" type="text">
            </div> 
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Email</label>
                <input class="form-control form-control-sm" type="email">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-control-sm ps-0">Working Location</label>
            <input class="form-control form-control-sm" type="text">
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Mobile Number</label>
                <input class="form-control form-control-sm" type="tel">
            </div> 
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Work Phone</label>
                <input class="form-control form-control-sm" type="tel">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-control-sm ps-0">Notes</label>
            <textarea class="form-control form-control-sm" rows="3"></textarea>
        </div>
        <button type="button" class="btn btn-outline-danger rounded-0 btn-sm remove-employee">Remove</button>
    `;

    // Append the new employee fields to the container
    employeeContainer.appendChild(newEmployeeFields);

    // Attach event listener to remove button
    newEmployeeFields.querySelector(".remove-employee").addEventListener("click", function() {
        employeeContainer.removeChild(newEmployeeFields);
    });
});