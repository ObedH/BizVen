document.getElementById("signup-form").addEventListener("submit", function(event) {
  event.preventDefault();
  const form = event.target;
  const username = form.querySelector('input[name="username"]').value;
  const email = form.querySelector('input[name="email"]').value;
  const password = form.querySelector('input[name="password"]').value;
  const role = form.querySelector('input[name="role"]:checked') ? form.querySelector('input[name="role"]:checked').id : null;
  const certification = form.querySelector('input[name="certification"]').files[0];

  if(!username || !email || !password || !role) {
    alert("Please fill out all the required fields.");
    return;
  }

  const formData = new FormData();
  formData.append("username", username);
  formData.append("email", email);
  formData.append("password", password);
  formData.append("role", role);

  if(certification) {
    formData.append("certification", certification);
  }

  console.log("Form Data:", formData);

  fetch('signup.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if(data.success) {
      alert("Registration Successful!");
    }
    else {
      alert("There was an error.");
    }
  })
  .catch(error => {
    console.error("Error during form submission:", error);
    alert("An error occured");
  });
});
  
}
