const html = document.getElementById("htmlPage");
const checkbox = document.getElementById("checkbox");
checkbox.addEventListener("change", () => {
    if (checkbox.checked) {
        html.setAttribute("data-bs-theme", "dark");
    } else {
        html.setAttribute("data-bs-theme", "light");
    }
});

var currentTab = 0;
showTab(currentTab);

function showTab(n) {
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  fixStepIndicator(n)
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    var inputs = x[currentTab].querySelectorAll("input[required], textarea[required]");

    // Validate required fields
    var isValid = true;
    inputs.forEach(function(input) {
        if (input.value.trim() === "") {
            input.classList.add("is-invalid"); // Add Bootstrap invalid class for styling
            isValid = false;
        } else {
            input.classList.remove("is-invalid"); // Remove invalid class if corrected
        }
    });

    if (!isValid) {
        alert("Please fill in all required fields before proceeding.");
        return false;
    }

    // Proceed to the next tab if validation passes
    x[currentTab].style.display = "none";
    currentTab += n;
    if (currentTab >= x.length) {
        document.getElementById("regForm").submit();
        return false;
    }
    showTab(currentTab);
}


function fixStepIndicator(n) {
    var i, steps = document.getElementsByClassName("step-icon");
    for (i = 0; i < steps.length; i++) {
        steps[i].classList.remove("active", "finish");
    }
    steps[n].classList.add("active");
    for (i = 0; i < n; i++) {
        steps[i].classList.add("finish");
    }

    fixStepIndicatorText(n); // Call the function to update step text
}

function fixStepIndicatorText(n) {
    var i, texts = document.getElementsByClassName("step-text");
    for (i = 0; i < texts.length; i++) {
        texts[i].classList.remove("fw-bold", "text-uppercase");
        texts[i].classList.add("text-secondary");
    }
    texts[n].classList.add("fw-bold", "text-uppercase"); // Highlight current step
}
