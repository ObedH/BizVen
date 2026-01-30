document.getElementById("login-switch").addEventListener('click', ()=> {
	document.getElementById("login-form").hidden = false;
	document.getElementById("signup-form").hidden = true;
}
document.getElementById("signup-switch").addEventListener('click', ()=> {
	document.getElementById("signup-form").hidden = false;
	document.getElementById("login-form").hidden = true;
}
