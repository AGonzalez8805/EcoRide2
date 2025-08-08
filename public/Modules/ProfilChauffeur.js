export class ProfilChauffeur {
    constructor() {
        // Boutons modifier / annuler pour pseudo
        this.editPseudoBtn = document.querySelector("#pseudoDisplayContainer button[title='Modifier le pseudo']");
        this.cancelPseudoBtn = document.querySelector("#pseudoForm button[title='Annuler'], #pseudoForm button.btn-small:nth-child(2)");

        // Boutons modifier / annuler pour mail
        this.editMailBtn = document.querySelector("#emailDisplayContainer button[title='Modifier l\'email']");
        this.cancelMailBtn = document.querySelector("#emailForm button[title='Annuler'], #emailForm button.btn-small:nth-child(2)");

        // IDs utiles
        this.pseudoDisplayId = "pseudoDisplayContainer";
        this.pseudoFormId = "pseudoForm";

        this.emailDisplayId = "emailDisplayContainer";
        this.emailFormId = "emailForm";

        this.mailInputId = "mailInput";

        this.init();
    }

    init() {
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
