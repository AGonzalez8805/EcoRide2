// app.js
import { Registration } from "./Modules/Registration.js";
import { Login } from "./Modules/Login.js";
import { AdminDashboard } from "./Modules/AdminDashboard.js";
import { Contact } from "./Modules/Contact.js";
import { ChauffeurTrajet } from "./Modules/ChauffeurTrajet.js";

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

  if (document.getElementById("contactForm")) {
    new Contact();
  }

  if (document.getElementById("trajetForm")) {
    new ChauffeurTrajet();
  }
});
