export class AdminDashboard {
  constructor() {
    console.log("Admin instancié");

    // Formulaire de création d'employé
    this.employeeForm = document.getElementById("employee-form");
    this.employeeMessage = document.getElementById("employee-message");

    // Boutons de suspension
    this.userList = document.getElementById("user-list");
    this.employeeList = document.getElementById("employee-list");

    this.init();
  }

  init() {
    if (this.employeeForm) {
      this.employeeForm.addEventListener("submit", (e) => {
        e.preventDefault();
        this.handleCreateEmployee();
      });
    }

    // Gestion des utilisateurs
    if (this.userList) {
      this.userList.querySelectorAll("button").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const email = e.target.getAttribute("data-email");
          const action = e.target.getAttribute("data-action");
          this.toggleUserStatus(email, action);
        });
      });
    }

    // Gestion des employés
    if (this.employeeList) {
      this.employeeList.querySelectorAll("button").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const email = e.target.getAttribute("data-email");
          const action = e.target.getAttribute("data-action");
          this.toggleEmployeeStatus(email, action);
        });
      });
    }
  }

  async handleCreateEmployee() {
    const email = document.getElementById("emp-email").value.trim();
    const pseudo = document.getElementById("emp-pseudo").value.trim();
    const password = document.getElementById("emp-password").value;

    if (!email || !pseudo || !password) {
      this.showEmployeeMessage("Tous les champs sont obligatoires.", false);
      return;
    }

    const formData = new URLSearchParams();
    formData.append("email", email);
    formData.append("pseudo", pseudo);
    formData.append("password", password);

    try {
      const response = await fetch("/?controller=admin&action=createEmploye", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData.toString(),
      });

      const text = await response.text();
      const isSuccess = text.includes("succès");

      this.showEmployeeMessage(
        isSuccess ? text : "Erreur : " + text,
        isSuccess
      );

      // ✅ Réinitialiser le formulaire si succès
      if (isSuccess) {
        this.employeeForm.reset();
      }
    } catch (error) {
      console.error("Erreur lors de la création :", error);
      this.showEmployeeMessage("Erreur technique", false);
    }
  }

  showEmployeeMessage(message, isSuccess = true) {
    if (this.employeeMessage) {
      this.employeeMessage.textContent = message;
      this.employeeMessage.classList.toggle("text-success", isSuccess);
      this.employeeMessage.classList.toggle("text-danger", !isSuccess);
    }
  }

  async toggleUserStatus(email, action) {
    const formData = new URLSearchParams();
    formData.append("email", email);

    try {
      const response = await fetch(
        "/?controller=admin&action=toggleUserStatus",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: formData.toString(),
        }
      );

      const result = await response.text();
      if (result === "OK") window.location.reload();
      else alert("Erreur : " + result);
    } catch (err) {
      console.error("Erreur suspension utilisateur :", err);
    }
  }

  async toggleEmployeeStatus(email, action) {
    const formData = new URLSearchParams();
    formData.append("email", email);

    try {
      const response = await fetch(
        "/?controller=admin&action=toggleEmployeStatus",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: formData.toString(),
        }
      );

      const result = await response.text();
      if (result === "OK") window.location.reload();
      else alert("Erreur : " + result);
    } catch (err) {
      console.error("Erreur suspension employé :", err);
    }
  }
}
