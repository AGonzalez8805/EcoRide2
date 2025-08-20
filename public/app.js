// app.js

console.log("app.js chargé ✅");
import { Registration } from "./Modules/Registration.js";
import { Login } from "./Modules/Login.js";
import { AdminDashboard } from "./Modules/AdminDashboard.js";
import { Contact } from "./Modules/Contact.js";
import { ChauffeurTrajet } from "./Modules/ChauffeurTrajet.js";
import { Vehicule } from "./Modules/Vehicule.js";
import { Profil } from "./Modules/Profil.js";
import { Avis } from "./Modules/Avis.js";

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

  if (document.getElementById("vehiculeForm")) {
    new Vehicule();
  }

  if (document.getElementById("profilForm")) {
    new Profil();
  }

  if (document.getElementById("avisForm")) {
    new Avis();
  }

});