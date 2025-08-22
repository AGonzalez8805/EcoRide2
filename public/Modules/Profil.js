export class Profil {
    constructor() {
        this.pseudoForm = document.getElementById("pseudoForm");
        this.pseudoInput = document.getElementById("pseudoInput");
        this.pseudoDisplay = document.getElementById("pseudoDisplayContainer");

        this.emailForm = document.getElementById("emailForm");
        this.emailInput = document.getElementById("mailInput");
        this.emailDisplay = document.getElementById("emailDisplayContainer");

        this.photoForm = document.getElementById("photoForm");
        this.photoInput = document.getElementById("photoInput");
        this.photoImg = document.querySelector(".profile-photo-header");
        this.photoContainer = document.querySelector('.header-photo-left');

        this.init();
    }

    init() {
        // Pseudo
        document.getElementById("editPseudoBtn")?.addEventListener("click", () =>
            this.toggleEdit(this.pseudoDisplay, this.pseudoForm, true, this.pseudoInput)
        );
        document.getElementById("cancelPseudoBtn")?.addEventListener("click", () =>
            this.toggleEdit(this.pseudoDisplay, this.pseudoForm, false)
        );

        // Email
        document.getElementById("editMailBtn")?.addEventListener("click", () =>
            this.toggleEdit(this.emailDisplay, this.emailForm, true, this.emailInput)
        );
        document.getElementById("cancelMailBtn")?.addEventListener("click", () =>
            this.toggleEdit(this.emailDisplay, this.emailForm, false)
        );

        // Photo
        if (this.photoInput) {
            this.photoInput.addEventListener("change", () => this.handlePhoto());
        }
    }


    toggleEdit(displayEl, formEl, showForm = true, focusEl = null) {
        displayEl.style.display = showForm ? "none" : "";
        formEl.style.display = showForm ? "" : "none";
        if (focusEl) focusEl.focus();
    }

    async handlePhoto() {
        if (!this.photoInput.files.length) return;

        const file = this.photoInput.files[0];
        if (!file.type.startsWith("image/")) return alert("Veuillez sélectionner une image.");
        if (file.size > 5 * 1024 * 1024) return alert("Image trop lourde (max 5 Mo).");

        // Créer <img> si elle n'existe pas encore
        if (!this.photoImg) {
            const img = document.createElement('img');
            img.className = 'profile-photo-header';
            img.alt = 'Profil';
            this.photoContainer.innerHTML = ''; // enlever la div 👤
            this.photoContainer.appendChild(img);
            this.photoImg = img;
        }

        // Prévisualisation côté client
        const reader = new FileReader();
        reader.onload = (e) => this.photoImg.src = e.target.result;
        reader.readAsDataURL(file);

        // Upload
        const formData = new FormData(this.photoForm);
        formData.append("field", "photo");
        try {
            const response = await fetch(this.photoForm.action, {
                method: this.photoForm.method,
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            });

            const data = await response.json();
            if (data.success && data.photo) {
                // Forcer le refresh pour éviter le cache
                this.photoImg.src = data.photo + "?t=" + new Date().getTime();
            } else {
                alert("Impossible de mettre à jour la photo.");
            }
        } catch (err) {
            console.error(err);
            alert("Erreur réseau lors de l'envoi de la photo.");
        }
    }
}
