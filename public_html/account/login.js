document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("login-form");
    form.addEventListener("submit", submitForm);
});

// Form Submission Handling
function submitForm(event) {
    event.preventDefault(); // Prevent the form from submitting traditionally
    console.log("Login form submitted");

    // Gather the data from the form
    const form = event.target;
    const username = form.querySelector('input[name="username"]').value;
    const password = form.querySelector('input[name="password"]').value;

    // Basic form validation
    if (!username || !password) {
        alert("Please enter both username and password.");
        return;
    }

    // Create a FormData object to handle form fields
    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    // Send the data to the server using Fetch API
    fetch('/account/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())  // Parse response as text (could be JSON)
    .then(data => {
        // Show response from PHP script
        if (data.includes("Login successful")) {
            alert("Login successful!");
            window.location.href = "/dashboard"; // Redirect to the user's dashboard or homepage
        } else {
            alert("Error: " + data); // Show error if something went wrong
        }
    })
    .catch(error => {
        console.error("Error during form submission:", error);
        alert("An error occurred while submitting the form.");
    });
}
