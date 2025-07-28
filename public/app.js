import { Registration } from "/Modules/Registration.js";
import { Login } from "/Modules/Login.js";

document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("registrationForm")) {
    new Registration();
  }

  if (document.getElementById("loginForm")) {
    new Login();
  }
});
