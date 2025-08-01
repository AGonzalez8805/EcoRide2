// app.js
import { Registration } from "./Modules/Registration.js";
import { Login } from "./Modules/Login.js";
import { AdminDashboard } from "./Modules/AdminDashboard.js";

document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("registrationForm")) {
    new Registration();
  }

  if (document.getElementById("loginForm")) {
    new Login();
  }

  if (document.querySelector(".admin-stat")) {
    new AdminDashboard();
  }
});
