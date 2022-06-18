var login = document.getElementById("pills-login");
var register = document.getElementById("pills-register");
var loginbtn = document.querySelector(".loginbtn");
var registerbtn = document.querySelector(".registerbtn");
loginbtn.addEventListener("click", () => {
 login.style.display = "block";
 register.style.display = "none";
});
registerbtn.addEventListener("click", () => {
 register.style.display = "block";
 login.style.display = "none";
});

var body = document.querySelector("body");
body.addEventListener("click", () => {
 var error = document.getElementById("error");
 error.remove();
});
