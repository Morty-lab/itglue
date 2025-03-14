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
  x[currentTab].style.display = "none";
  currentTab = currentTab + n;
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
        texts[i].classList.remove("fw-bold");
        texts[i].classList.add("text-secondary");
    }
    texts[n].classList.add("fw-bold"); // Highlight current step
}
