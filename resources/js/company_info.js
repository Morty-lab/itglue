let index = 0;

document.getElementById('addBranchBtn').addEventListener('click', function () {
    let branchContainer = document.getElementById('branchesContainer');

    let branchDiv = document.createElement('div');
    branchDiv.classList.add('mb-3', 'border', 'p-3', 'rounded');

    branchDiv.innerHTML = `
        <label class="form-control-sm ps-0">Branch Information</label>
        <div class="d-flex justify-content-between gap-2">
            <input class="form-control form-control-sm" type="text" placeholder="Branch Address" name="branches[${index}][address]" required>
            <input class="form-control form-control-sm" type="tel" placeholder="Phone Number" name="branches[${index}][phone_number]" required>
        </div>
        <div class="d-flex justify-content-between gap-2 mt-2">
            <input class="form-control form-control-sm" type="tel" placeholder="FAX" name="branches[${index}][fax]" required>
            <input class="form-control form-control-sm" type="url" placeholder="Website" name="branches[${index}][website]" required>
        </div>
        <div class="d-flex justify-content-between gap-2 mt-2">
            <input class="form-control form-control-sm" type="time" aria-label="Opening Time" name="branches[${index}][opening_time]" required>
            <input class="form-control form-control-sm" type="time" aria-label="Closing Time" name="branches[${index}][closing_time]" required>
        </div>
        <button type="button" class="btn btn-outline-danger rounded-0 btn-sm mt-2 removeBranch">Remove Branch</button>
    `;

    branchContainer.appendChild(branchDiv);

    // Add event listener to remove branch
    branchDiv.querySelector('.removeBranch').addEventListener('click', function () {
        branchDiv.remove();
    });

    index++;

    console.log(index);
});

