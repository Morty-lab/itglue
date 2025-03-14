document.getElementById('addBranchBtn').addEventListener('click', function () {
    let branchContainer = document.getElementById('branchesContainer');

    let branchDiv = document.createElement('div');
    branchDiv.classList.add('mb-3', 'border', 'p-3', 'rounded');

    branchDiv.innerHTML = `
        <label class="form-control-sm ps-0">Branch Information</label>
        <div class="d-flex justify-content-between gap-2">
            <input class="form-control form-control-sm" type="text" placeholder="Branch Address">
            <input class="form-control form-control-sm" type="tel" placeholder="Phone Number">
        </div>
        <div class="d-flex justify-content-between gap-2 mt-2 mb-2">
            <input class="form-control form-control-sm" type="tel" placeholder="FAX">
            <input class="form-control form-control-sm" type="url" placeholder="Website">
        </div>
        <div class="d-flex justify-content-between gap-2">
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Office Hours (Opening Time)</label>
                <input class="form-control form-control-sm" type="time">
            </div> 
            <div class="mb-3 w-100">
                <label class="form-control-sm ps-0">Office Hours (Closing Time)</label>
                <input class="form-control form-control-sm" type="time">
            </div>
        </div>
        <button type="button" class="btn btn-outline-danger rounded-0 btn-sm mt-2 removeBranch">Remove Branch</button>
    `;

    branchContainer.appendChild(branchDiv);

    // Add event listener to remove branch
    branchDiv.querySelector('.removeBranch').addEventListener('click', function () {
        branchDiv.remove();
    });
});