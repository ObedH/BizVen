document.addEventListener("DOMContentLoaded", () => {
	const form = document.getElementById("signup-form");
	form.addEventListener("submit", submitSignupForm);
});




// Form Submission Handling
function submitSignupForm(event) {
    event.preventDefault(); // Prevent the form from submitting traditionally
	console.log("Form submitted");
    
    // Gather the data from the form
    const form = event.target;
    const username = form.querySelector('input[name="username"]').value;
    const email = form.querySelector('input[name="email"]').value;
    const password = form.querySelector('input[name="password"]').value;
    const role = form.querySelector('input[name="role"]:checked') ? form.querySelector('input[name="role"]:checked').value : null;
    const certification = form.querySelector('input[name="certification"]').files[0];

    // Basic form validation
    if (!username || !email || !password || !role) {
        alert("Please fill out all the required fields.");
        return;
    }

    // Create a FormData object to handle both form fields and the file upload
    const formData = new FormData();
    formData.append("username", username);
    formData.append("email", email);
    formData.append("password", password);
    formData.append("role", role);

    // If a certification file is provided, append it to FormData
    if (certification) {
        formData.append("certification", certification);
    }

    // Send the data to the server using Fetch API
    fetch('/account/signup.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Parse response as text (could be JSON)
    .then(data => {
        // Show response from PHP script
        if (data.includes("User registered successfully")) {
            alert("Registration successful!");
            window.location.href = "./"; // Redirect to the login page or home page
        } else {
            alert("Error: " + data); // Show error if something went wrong
        }
    })
    .catch(error => {
        console.error("Error during form submission:", error);
        alert("An error occurred while submitting the form.");
    });
}
