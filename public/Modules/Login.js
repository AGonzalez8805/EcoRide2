export class Login {
  constructor() {
    // Affiche un message dans la console lorsque la classe est instanciée
    console.log("Login class instanciée");

    // Récupère le formulaire de connexion par son ID
    this.form = document.getElementById("loginForm");

    // Si le formulaire n'existe pas dans le DOM, on arrête l'initialisation
    if (!this.form) return;

    // Récupère les champs email, mot de passe et le div d'erreur
    this.inputMail = document.getElementById("email");
    this.inputPassword = document.getElementById("password");
    this.errorDiv = document.getElementById("loginError");

    // Initialise les écouteurs d'événements
    this.init();
  }

  init() {
    // Ajoute un écouteur sur l'email pour valider à chaque saisie
    this.inputMail.addEventListener("input", () => {
      this.validateEmail(this.inputMail);
      this.hideLoginError(); // Masque l'erreur si présente
    });

    // Ajoute un écouteur sur le mot de passe pour valider à chaque saisie
    this.inputPassword.addEventListener("input", () => {
      this.validatePassword(this.inputPassword);
      this.hideLoginError(); // Masque l'erreur si présente
    });

    // Intercepte la soumission du formulaire
    this.form.addEventListener("submit", (e) => {
      e.preventDefault(); // Empêche le rechargement de la page
      console.log("Submit intercepté");
      this.handleLogin(); // Gère la logique de connexion
    });
  }

  // Valide le champ email avec une expression régulière
  validateEmail(field) {
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
    // Ajoute ou enlève les classes CSS selon la validité
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  // Vérifie que le champ mot de passe n'est pas vide
  validatePassword(field) {
    const isValid = field.value.trim().length > 0;
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  // Masque le message d'erreur de connexion
  hideLoginError() {
    if (this.errorDiv) {
      this.errorDiv.textContent = "";
      this.errorDiv.classList.add("d-none");
    }
  }

  // Affiche un message d'erreur de connexion
  showLoginError(message) {
    if (this.errorDiv) {
      this.errorDiv.textContent = message;
      this.errorDiv.classList.remove("d-none");
    }
  }

  // Gère la logique de connexion
  async handleLogin() {

    console.log("handleLogin appelée");

    // Vérifie la validité des champs
    const isMailValid = this.validateEmail(this.inputMail);
    const isPasswordValid = this.validatePassword(this.inputPassword);

    // Si l'un des champs est invalide, on affiche une erreur
    if (!isMailValid || !isPasswordValid) {
      this.showLoginError(
        "Merci de corriger les champs avant de vous connecter."
      );
      return;
    }

    // Prépare les données à envoyer au serveur
    const data = {
      email: this.inputMail.value,
      password: this.inputPassword.value,
    };

    try {
      // Envoie les données via `fetch` à l'URL du contrôleur
      const response = await fetch("/?controller=auth&action=handleLogin", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(data),
      });

      let result;
      try {
        result = await response.json();
      } catch (err) {
        console.error("Erreur de parsing JSON :", err);
        this.showLoginError("La réponse du serveur est invalide.");
        return;
      }

      // Si le login est un succès, redirection
      if (result.success) {
        window.location.href = result.redirect || "";
      } else {
        // Affiche le message d'erreur retourné par le serveur
        this.showLoginError(result.message || "Erreur d'identifiants.");
      }
    } catch (error) {
      // En cas d'erreur réseau ou serveur
      console.error("Erreur lors de la connexion :", error);
      this.showLoginError("Une erreur technique est survenue.");
    }
  }
}
