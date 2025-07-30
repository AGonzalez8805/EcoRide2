export class Login {
  constructor() {
    console.log("Login class instanciée");
    this.form = document.getElementById("loginForm");

    if (!this.form) return;

    this.inputMail = document.getElementById("email");
    this.inputPassword = document.getElementById("password");
    this.errorDiv = document.getElementById("loginError");

    this.init();
  }

  init() {
    this.inputMail.addEventListener("input", () => {
      this.validateEmail(this.inputMail);
      this.hideLoginError();
    });

    this.inputPassword.addEventListener("input", () => {
      this.validatePassword(this.inputPassword);
      this.hideLoginError();
    });

    this.form.addEventListener("submit", (e) => {
      e.preventDefault();
      console.log("Submit intercepté");
      this.handleLogin();
    });
  }

  validateEmail(field) {
    const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  validatePassword(field) {
    const isValid = field.value.trim().length > 0;
    field.classList.toggle("is-valid", isValid);
    field.classList.toggle("is-invalid", !isValid);
    return isValid;
  }

  hideLoginError() {
    if (this.errorDiv) {
      this.errorDiv.textContent = "";
      this.errorDiv.classList.add("d-none");
    }
  }

  showLoginError(message) {
    if (this.errorDiv) {
      this.errorDiv.textContent = message;
      this.errorDiv.classList.remove("d-none");
    }
  }

  async handleLogin() {
    console.log("handleLogin appelée");
    const isMailValid = this.validateEmail(this.inputMail);
    const isPasswordValid = this.validatePassword(this.inputPassword);

    if (!isMailValid || !isPasswordValid) {
      this.showLoginError(
        "Merci de corriger les champs avant de vous connecter."
      );
      return;
    }

    const data = {
      email: this.inputMail.value,
      password: this.inputPassword.value,
    };

    try {
      const response = await fetch("/?controller=auth&action=handleLogin", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(data),
      });

      const text = await response.text();

      let result;
      try {
        result = JSON.parse(text);
      } catch (err) {
        console.error("Erreur de parsing JSON :", err);
        this.showLoginError("La réponse du serveur est invalide.");
        return;
      }

      if (result.success) {
        window.location.href = result.redirect || "/";
      } else {
        this.showLoginError(result.message || "Erreur d'identifiants.");
      }
    } catch (error) {
      console.error("Erreur lors de la connexion :", error);
      this.showLoginError("Une erreur technique est survenue.");
    }
  }
}
