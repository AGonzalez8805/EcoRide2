export class Vehicule {
    constructor() {
        console.log("Vehicule class instanciée");

        this.form = document.getElementById("vehiculeForm");
        if (!this.form) return;

        // Champs du formulaire
        this.marque = document.getElementById("marque");
        this.modele = document.getElementById("modele");
        this.couleur = document.getElementById("couleur");
        this.nbPlaces = document.getElementById("nbPlaces");
        this.immatriculation = document.getElementById("immatriculation");
        this.datePremierImmatriculation = document.getElementById("datePremierImmatriculation");
        this.energie = document.getElementById("energie");

        // Div pour afficher les erreurs
        this.errorDiv = document.getElementById("vehiculeFormError");

        this.init();
    }

    init() {
        // Validation à la saisie sur certains champs
        this.marque.addEventListener("input", () => this.validateRequiredField(this.marque));
        this.modele.addEventListener("input", () => this.validateRequiredField(this.modele));
        this.couleur.addEventListener("input", () => this.validateRequiredField(this.couleur));
        this.nbPlaces.addEventListener("input", () => this.validateNumberField(this.nbPlaces, 1, 9));
        this.immatriculation.addEventListener("input", () => this.validateImmatriculation(this.immatriculation));
        this.datePremierImmatriculation.addEventListener("input", () => this.validateDate(this.datePremierImmatriculation));
        this.energie.addEventListener("input", () => this.validateRequiredField(this.energie));

        // Soumission du formulaire
        this.form.addEventListener("submit", (e) => {
            e.preventDefault();
            this.handleSubmit();
        });
    }

    // Validation générique des champs obligatoires (non vides)
    validateRequiredField(field) {
        const isValid = field.value.trim() !== "";
        field.classList.toggle("is-valid", isValid);
        field.classList.toggle("is-invalid", !isValid);
        return isValid;
    }

    // Validation spécifique pour le nombre de places
    validateNumberField(field, min, max) {
        const value = parseInt(field.value, 10);
        const isValid = !isNaN(value) && value >= min && value <= max;
        field.classList.toggle("is-valid", isValid);
        field.classList.toggle("is-invalid", !isValid);
        return isValid;
    }

    // Validation simple d’immatriculation (format français basique)
    validateImmatriculation(field) {
        // Exemple: AB-123-CD (2 lettres, 3 chiffres, 2 lettres)
        const regex = /^[A-Z]{2}-\d{3}-[A-Z]{2}$/;
        const isValid = regex.test(field.value.toUpperCase());
        field.classList.toggle("is-valid", isValid);
        field.classList.toggle("is-invalid", !isValid);
        return isValid;
    }

    // Validation date : non vide et pas dans le futur
    validateDate(field) {
        const value = field.value;
        if (!value) {
            field.classList.add("is-invalid");
            field.classList.remove("is-valid");
            return false;
        }
        const date = new Date(value);
        const now = new Date();
        const isValid = date <= now;
        field.classList.toggle("is-valid", isValid);
        field.classList.toggle("is-invalid", !isValid);
        return isValid;
    }

    hideError() {
        if (this.errorDiv) {
            this.errorDiv.textContent = "";
            this.errorDiv.classList.add("d-none");
        }
    }

    showError(message) {
        if (this.errorDiv) {
            this.errorDiv.textContent = message;
            this.errorDiv.classList.remove("d-none");
        }
    }

    async handleSubmit() {
        this.hideError();

        // Validation des champs
        const validMarque = this.validateRequiredField(this.marque);
        const validModele = this.validateRequiredField(this.modele);
        const validCouleur = this.validateRequiredField(this.couleur);
        const validNbPlaces = this.validateNumberField(this.nbPlaces, 1, 9);
        const validImmatriculation = this.validateImmatriculation(this.immatriculation);
        const validDate = this.validateDate(this.datePremierImmatriculation);
        const validEnergie = this.validateRequiredField(this.energie);

        if (
            !validMarque ||
            !validModele ||
            !validCouleur ||
            !validNbPlaces ||
            !validImmatriculation ||
            !validDate ||
            !validEnergie
        ) {
            this.showError("Merci de corriger les champs avant de soumettre le formulaire.");
            return;
        }

        // Préparer les données
        const formData = new FormData(this.form);

        try {
            const response = await fetch(this.form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            const text = await response.text();
            console.log("Réponse brute du serveur :", text);
            let result;
            try {
                result = JSON.parse(text);
            } catch {
                console.error("Erreur de parsing JSON :", text);
                this.showError("Réponse du serveur invalide.");
                return;
            }

            if (result.success) {
                window.location.href = "/?controller=covoiturage&action=create";
            } else {
                if (result.errors) {
                    // Affiche les erreurs sur chaque champ
                    Object.entries(result.errors).forEach(([key, message]) => {
                        const field = document.getElementById(key);
                        if (field) {
                            field.classList.add("is-invalid");

                            const feedback = field.parentElement.querySelector(".invalid-feedback");
                            if (feedback) {
                                feedback.textContent = message;
                                feedback.style.display = "block";
                            }
                        }
                    });
                    this.showError("Veuillez corriger les erreurs du formulaire.");
                } else {
                    this.showError(result.message || "Erreur lors de l'ajout du véhicule.");
                }
            }
        } catch (error) {
            console.error("Erreur requête:", error);
            this.showError("Une erreur technique est survenue.");
        }
    }
}
