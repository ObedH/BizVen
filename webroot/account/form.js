let stepNumber = 1;
function nextStep() {
    document.getElementById("step-" + stepNumber).hidden = true;
    stepNumber ++;
    document.getElementById("step-" + stepNumber).hidden = false;
}
