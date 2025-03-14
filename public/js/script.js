var currentTab = 0;
showTab(currentTab);

document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM fully loaded and script running...");

    const form = document.getElementById("regForm");
    if (!form) {
        console.error("Form not found!");
        return;
    }

    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    if (!prevBtn || !nextBtn) {
        console.error("Navigation buttons not found!");
        return;
    }

    const formFields = form.elements;
    let currentTab = 0;
    showTab(currentTab);

    console.log("Loading stored form data...");
    // loadFormData();

    for (let field of formFields) {
        if (field.name) {
            field.addEventListener("input", saveFormData);
            field.addEventListener("change", saveFormData);
        }
    }



});

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    if (x.length === 0) {
        console.error("No tabs found!");
        return;
    }

    x[n].style.display = "block";
    document.getElementById("prevBtn").style.display =
        n === 0 ? "none" : "inline";
    // document.getElementById("nextBtn").innerHTML =
    //     n === x.length - 1 ? "Submit" : "Next";
    fixStepIndicator(n);
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    if (x.length === 0) {
        console.error("No tabs found!");
        return;
    }

    var inputs = x[currentTab].querySelectorAll(
        "input[required], textarea[required]"
    );
    var isValid = true;

    inputs.forEach(function (input) {
        if (input.value.trim() === "") {
            input.classList.add("is-invalid");
            isValid = false;
        } else {
            input.classList.remove("is-invalid");
        }
    });

    if (!isValid) {
        alert("Please fill in all required fields before proceeding.");
        return false;
    }

    x[currentTab].style.display = "none";
    currentTab += n;

    if (currentTab >= x.length) {
        // if (localStorage.getItem("branchesData") === null) {
        //     alert("Please add at least one branch before submitting.");
        //     currentTab = 0;
        //     showTab(currentTab);
        //     return false;
        // }
        // if (localStorage.getItem("employeesData") === null) {
        //     alert("Please add at least one employee before submitting.");
        //     currentTab = 0;
        //     showTab(currentTab);
        //     return false;
        // }
        // if (localStorage.getItem("devices") === null) {
        //     alert("Please add at least one physical device before submitting.");
        //     currentTab = 0;
        //     showTab(currentTab);
        //     return false;
        // }
        // if (localStorage.getItem("software_licenses") === null) {
        //     alert("Please add at least one software license before submitting.");
        //     currentTab = 0;
        //     showTab(currentTab);
        //     return false;
        // }
        // localStorage.clear();
        document.getElementById("regForm").submit();
        return false;
    }

    showTab(currentTab);
}

function fixStepIndicator(n) {
    var steps = document.getElementsByClassName("step-icon");
    if (steps.length === 0) {
        console.error("No step icons found!");
        return;
    }

    for (let i = 0; i < steps.length; i++) {
        steps[i].classList.remove("active", "finish");
    }

    steps[n].classList.add("active");
    for (let i = 0; i < n; i++) {
        steps[i].classList.add("finish");
    }

    fixStepIndicatorText(n);
}

function fixStepIndicatorText(n) {
    var texts = document.getElementsByClassName("step-text");
    if (texts.length === 0) {
        console.error("No step texts found!");
        return;
    }

    for (let i = 0; i < texts.length; i++) {
        texts[i].classList.remove("fw-bold", "text-uppercase");
        texts[i].classList.add("text-secondary");
    }

    texts[n].classList.add("fw-bold", "text-uppercase");
}

function saveFormData() {
    const form = document.getElementById("regForm");
    let formData = {};

    for (let field of form.elements) {
        if (field.name) {
            if (field.type === "checkbox" || field.type === "radio") {
                formData[field.name] = field.checked;
            } else {
                formData[field.name] = field.value;
            }
        }
    }

    localStorage.setItem("onboardingFormData", JSON.stringify(formData));
    console.log("Saved data:", JSON.stringify(formData));
}

function loadFormData() {
    try {
        const savedData = localStorage.getItem("onboardingFormData");
        if (savedData) {
            const formData = JSON.parse(savedData);
            const form = document.getElementById("regForm");

            for (let field of form.elements) {
                if (field.name && formData.hasOwnProperty(field.name)) {
                    if (field.type === "checkbox" || field.type === "radio") {
                        field.checked = formData[field.name];
                    } else {
                        field.value = formData[field.name];
                    }
                }
            }
        }
    } catch (error) {
        console.error("Error loading form data:", error);
    }
}
