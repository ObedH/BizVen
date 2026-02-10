document.addEventListener("DOMContentLoaded", ()=> {
	const loginForm = document.getElementById("login-form");
	const signupForm = document.getElementById("signup-form");
	document.getElementById("login-switch").addEventListener('click', ()=> {
		loginForm.classList.add("show");
		signupForm.classList.remove("show");
	});
	document.getElementById("signup-switch").addEventListener('click', ()=> {
		loginForm.classList.remove("show");
		signupForm.classList.add("show");
	});
});
