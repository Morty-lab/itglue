document.getElementById("credentialType").addEventListener("change", function() {
    let inputs = document.querySelectorAll(".credential-type-input");
    let nameInput = inputs[0]; // The first input in the form is the "Name" field
    let selectedOption = this.options[this.selectedIndex].text;

    if (this.value) {
        inputs.forEach(input => input.removeAttribute("disabled"));
        nameInput.value = selectedOption; // Set the name input value
    } else {
        inputs.forEach(input => {
            input.setAttribute("disabled", "true");
            input.value = ""; // Clear input values when no credential type is selected
        });
    }
});
