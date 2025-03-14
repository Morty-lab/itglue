function checkCredentialType(index) {
    console.log(`Checking credential type for index ${index}`);
    const selectedValue = document.getElementById(
        `credentialType-${index}`
    ).value;

    if (selectedValue) {
        // Enable only the inputs for the current index
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_name]"]`
        ).disabled = false;
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_url]"]`
        ).disabled = false;
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_username]"]`
        ).disabled = false;
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_password]"]`
        ).disabled = false;
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_mfa]"]`
        ).disabled = false;
    } else {
        // Disable only the inputs for the current index
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_name]"]`
        ).disabled = true;
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_url]"]`
        ).disabled = true;
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_username]"]`
        ).disabled = true;
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_password]"]`
        ).disabled = true;
        document.querySelector(
            `.web-document-input[name="webpage_document[${index}][credential_mfa]"]`
        ).disabled = true;
    }
}
