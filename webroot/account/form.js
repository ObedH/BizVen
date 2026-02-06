document.getElementById('signup-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    // Validate and submit Step 1 (you can add more validation logic here)
    const username = e.target[0].value;
    const email = e.target[1].value;
    const password = e.target[2].value;

    if (username && email && password) {
        // Hide Step 1 form
        document.getElementById('signup-form').style.display = 'none';
        
        // Show Step 2 form (you can add your Step 2 form elements here)
        const step2Form = document.createElement('form');
        step2Form.innerHTML = `
            <label>Confirm Email:</label><input type="email">
            <button type="submit">Confirm</button>
        `;
        
        // Add the Step 2 form to the DOM
        document.querySelector('.auth-forms').appendChild(step2Form);
    } else {
        alert('Please fill out all fields');
    }
});
