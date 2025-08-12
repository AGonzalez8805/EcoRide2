export class AdminDashboard {
  constructor() {
    console.log("Admin instancié");

    // Récupération des éléments du DOM pour le formulaire de création d'employé
    this.employeeForm = document.getElementById("employee-form");
    this.employeeMessage = document.getElementById("employee-message");

    // Récupération des conteneurs utilisateurs et employés pour gérer les listes
    this.userList = document.getElementById("user-list");
    this.employeeList = document.getElementById("employee-list");

    // Initialisation des événements et chargement des données
    this.init();
  }

  init() {
    // Si le formulaire de création d'employé existe, on ajoute un écouteur d'événement pour la soumission
    if (this.employeeForm) {
      this.employeeForm.addEventListener("submit", (e) => {
        e.preventDefault(); // Empêche la soumission classique du formulaire
        this.handleCreateEmployee(); // Appelle la fonction pour gérer la création d'employé
      });
    }

    // Gestion des boutons pour les utilisateurs dans la liste
    if (this.userList) {
      // Pour chaque bouton dans la liste des utilisateurs, on attache un événement click
      this.userList.querySelectorAll("button").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const email = e.target.getAttribute("data-email"); // Récupère l'email associé au bouton
          const action = e.target.getAttribute("data-action"); // Action à effectuer (toggle)
          this.toggleUserStatus(email, action); // Appelle la méthode pour changer le statut utilisateur
        });
      });
    }

    // Si la liste des employés est présente, on charge les employés
    if (this.employeeList) {
      this.loadEmployees();
    }

    // Si la liste des utilisateurs est présente, on charge les utilisateurs
    if (this.userList) {
      this.loadUsers();
    }
  }

  /**
   * Gère la création d'un employé via le formulaire
   */
  async handleCreateEmployee() {
    // Récupération des valeurs des champs
    const email = document.getElementById("emp-email").value.trim();
    const pseudo = document.getElementById("emp-pseudo").value.trim();
    const password = document.getElementById("emp-password").value;

    // Validation simple : tous les champs sont obligatoires
    if (!email || !pseudo || !password) {
      this.showEmployeeMessage("Tous les champs sont obligatoires.", false);
      return;
    }

    // Préparation des données à envoyer en POST
    const formData = new URLSearchParams();
    formData.append("email", email);
    formData.append("pseudo", pseudo);
    formData.append("password", password);

    try {
      // Envoi de la requête fetch au serveur
      const response = await fetch("/?controller=admin&action=createEmploye", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData.toString(),
      });

      const text = await response.text();
      const isSuccess = text.includes("succès"); // Vérifie si le texte de retour contient "succès"

      // Affiche un message de succès ou d'erreur selon la réponse
      this.showEmployeeMessage(
        isSuccess ? text : "Erreur : " + text,
        isSuccess
      );

      // Si succès, réinitialise le formulaire
      if (isSuccess) {
        this.employeeForm.reset();
      }
    } catch (error) {
      console.error("Erreur lors de la création :", error);
      this.showEmployeeMessage("Erreur technique", false);
    }
  }

  /**
   * Affiche un message sous le formulaire de création employé
   * @param {string} message - Message à afficher
   * @param {boolean} isSuccess - true si message de succès, false sinon
   */
  showEmployeeMessage(message, isSuccess = true) {
    if (this.employeeMessage) {
      this.employeeMessage.textContent = message;
      // Ajoute ou enlève les classes CSS pour couleur verte ou rouge
      this.employeeMessage.classList.toggle("text-success", isSuccess);
      this.employeeMessage.classList.toggle("text-danger", !isSuccess);
    }
  }

  /**
   * Active ou suspend un utilisateur donné
   * @param {string} email - Email de l'utilisateur
   * @param {string} action - Action à effectuer (ici toujours "toggle")
   */
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
      // Si succès, recharge la page pour afficher les changements
      if (result === "OK") window.location.reload();
      else alert("Erreur : " + result);
    } catch (err) {
      console.error("Erreur suspension utilisateur :", err);
    }
  }

  /**
   * Active ou suspend un employé donné
   * @param {string} email - Email de l'employé
   * @param {string} action - Action à effectuer (toggle)
   */
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

  /**
   * Charge la liste des employés depuis le serveur et met à jour le DOM
   */
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
      container.innerHTML = ""; // Vide la liste avant ajout

      // Pour chaque employé, on crée un élément HTML avec son statut et bouton d'action
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
          <div class="user-name">${emp.pseudo}</div>
          <div class="user-email">${emp.email}</div>
        </div>
        <span class="user-status ${statusClass}">${status}</span>
        <button class="btn btn-${emp.isSuspended ? "success" : "danger"
          }" data-email="${emp.email}" data-action="toggle">${buttonText}</button>
      `;
        container.appendChild(userItem);
      });

      // Réattache les événements aux boutons créés dynamiquement
      container.querySelectorAll("button").forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const email = e.target.getAttribute("data-email");
          const action = e.target.getAttribute("data-action");
          this.toggleEmployeeStatus(email, action);
        });
      });
    } catch (error) {
      console.error(error);
    }
  }

  /**
   * Charge la liste des utilisateurs depuis le serveur et met à jour le DOM
   */
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
      container.innerHTML = ""; // Vide la liste avant ajout

      // Pour chaque utilisateur, création d'un élément HTML avec statut et bouton
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
          <div class="user-firstName">${user.firstName}</div>
          <div class="user-email">${user.email}</div>
        </div>
        <span class="user-status ${statusClass}">${status}</span>
        <button class="btn btn-${user.isSuspended ? "success" : "danger"}"
          data-email="${user.email}" data-action="toggle">${buttonText}</button>
      `;

        container.appendChild(userItem);
      });

      // Ajout des événements sur les boutons nouvellement ajoutés
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
