let stepNumber = 1;
function nextStep(e) {
    e.preventDefault();
    document.getElementById("step-" + stepNumber).hidden = true;
    stepNumber ++;
    document.getElementById("step-" + stepNumber).hidden = false;
}
