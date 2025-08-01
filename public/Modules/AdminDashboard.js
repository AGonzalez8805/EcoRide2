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

    if (this.employeeList) {
      this.loadEmployees();
    }

    if (this.userList) {
      this.loadUsers();
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

  async loadEmployees() {
    try {
      const response = await fetch(
        "/?controller=admin&action=listEmployesJson"
      );
      if (!response.ok) throw new Error("Erreur chargement employés");

      const employees = await response.json();
      const container = document.getElementById("employee-list");
      if (!container) {
        console.error("Element employee-list non trouvé dans le DOM");
        return;
      }
      container.innerHTML = ""; // vide avant d’ajouter

      employees.forEach((emp) => {
        const status = emp.isSuspended ? "Suspendu" : "Actif";
        const statusClass = emp.isSuspended
          ? "status-inactive"
          : "status-active";
        const buttonText = emp.isSuspended ? "Réactiver" : "Suspendre";

        const userItem = document.createElement("div");
        userItem.classList.add("user-item");
        userItem.innerHTML = `
        <div class="user-info-item">
          <div class="user-name">${emp.pseudo} (Employé)</div>
          <div class="user-email">${emp.email}</div>
        </div>
        <span class="user-status ${statusClass}">${status}</span>
        <button class="btn btn-${
          emp.isSuspended ? "success" : "danger"
        }" data-email="${emp.email}" data-action="toggle">${buttonText}</button>

      `;
        container.appendChild(userItem);
      });

      // Réattacher les événements sur les boutons
      container.querySelectorAll("button").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const email = e.target.getAttribute("data-email");
          const action = e.target.getAttribute("data-action");
          this.toggleEmployeeStatus(email, action); // ta méthode existante
        });
      });
    } catch (error) {
      console.error(error);
    }
  }

  async loadUsers() {
    try {
      const response = await fetch("/?controller=admin&action=listUsersJson");
      if (!response.ok) throw new Error("Erreur chargement utilisateurs");

      const users = await response.json();
      const container = document.getElementById("user-list");
      if (!container) {
        console.error("Element user-list non trouvé");
        return;
      }
      container.innerHTML = "";

      users.forEach((user) => {
        const status = user.isSuspended ? "Suspendu" : "Actif";
        const statusClass = user.isSuspended
          ? "status-suspended"
          : "status-active";
        const buttonText = user.isSuspended ? "Réactiver" : "Suspendre";

        const userItem = document.createElement("div");
        userItem.classList.add("user-item");
        userItem.innerHTML = `
        <div class="user-info-item">
          <div class="user-name">${user.name}</div>
          <div class="user-email">${user.email}</div>
        </div>
        <span class="user-status ${statusClass}">${status}</span>
        <button class="btn btn-${user.isSuspended ? "success" : "danger"}"
          data-email="${user.email}" data-action="toggle">${buttonText}</button>
      `;

        container.appendChild(userItem);
      });

      container.querySelectorAll("button").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const email = e.target.getAttribute("data-email");
          const action = e.target.getAttribute("data-action");
          this.toggleUserStatus(email, action);
        });
      });
    } catch (err) {
      console.error("Erreur lors du chargement des utilisateurs :", err);
    }
  }
}
