document.getElementById("credentialType").addEventListener("change", function() {
    let inputs = document.querySelectorAll(".web-document-input");
    if (this.value) {
        inputs.forEach(input => input.removeAttribute("disabled"));
    } else {
        inputs.forEach(input => input.setAttribute("disabled", "true"));
    }
});
