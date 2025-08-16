export class Profil {
    constructor() {
        // Déplacez les sélecteurs ici.
        this.pseudoDisplayId = "pseudoDisplayContainer";
        this.pseudoFormId = "pseudoForm";
        this.emailDisplayId = "emailDisplayContainer";
        this.emailFormId = "emailForm";
        this.mailInputId = "mailInput";

        // Appelez init() pour initialiser les boutons et les écouteurs.
        this.init();
    }

    init() {
        console.log("Init Profil");
        console.log("Edit pseudo btn:", document.getElementById("editPseudoBtn"));
        // Recherche des boutons.
        // Cette étape est exécutée après le chargement du DOM,
        // garantissant que les éléments existent.
        this.editPseudoBtn = document.getElementById("editPseudoBtn");
        this.cancelPseudoBtn = document.getElementById("cancelPseudoBtn");
        this.editMailBtn = document.getElementById("editMailBtn");
        this.cancelMailBtn = document.getElementById("cancelMailBtn");

        // Attachement des écouteurs d'événements.
        if (this.editPseudoBtn) {
            this.editPseudoBtn.addEventListener("click", () => this.toggleEdit(this.pseudoDisplayId, this.pseudoFormId, true));
        }
        if (this.cancelPseudoBtn) {
            this.cancelPseudoBtn.addEventListener("click", () => this.toggleEdit(this.pseudoDisplayId, this.pseudoFormId, false));
        }
        if (this.editMailBtn) {
            this.editMailBtn.addEventListener("click", () => this.toggleEdit(this.emailDisplayId, this.emailFormId, true, this.mailInputId));
        }
        if (this.cancelMailBtn) {
            this.cancelMailBtn.addEventListener("click", () => this.toggleEdit(this.emailDisplayId, this.emailFormId, false));
        }
    }

    // ... le reste de la méthode toggleEdit() est inchangé
    toggleEdit(displayId, formId, showForm = true, focusId = null) {
        const display = document.getElementById(displayId);
        const form = document.getElementById(formId);
        if (!display || !form) return;

        display.style.display = showForm ? "none" : "";
        form.style.display = showForm ? "" : "none";

        if (focusId && showForm) {
            const field = document.getElementById(focusId);
            field?.focus();
        }
    }
}