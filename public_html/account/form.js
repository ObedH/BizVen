let stepNumber = 1;
function nextStep(e) {
    e.preventDefault();
    document.getElementById("step-" + stepNumber).hidden = true;
    stepNumber ++;
    document.getElementById("step-" + stepNumber).hidden = false;

	document.querySelectorAll("#step-1 input").forEach(input => {
		input.required = false;
	});
}

function handleRoleSelection() {
    // Get the selected role
    const selectedRole = document.querySelector('input[name="role"]:checked');
    const certificationField = document.getElementById('certification-field');

    // Show the certification field if "Sheikh" is selected
    if (selectedRole && selectedRole.id === 'sheikh') {
        certificationField.hidden = false;
    } else {
        certificationField.hidden = true;
    }
}
