export class Avis {
    constructor() {
        this.form = document.getElementById("avisForm");
        this.inputPseudo = document.getElementById("pseudo");
        this.inputMail = document.getElementById('email');
        this.textareaCommentaire = document.getElementById("commentaire");
        this.charCounter = document.getElementById("charCounter");
        this.inputRating = document.getElementById("rating");
        this.ratingText = document.getElementById("ratingText");
        this.stars = document.querySelectorAll("#starRating .star");
        this.successMessage = document.getElementById("successMessage");

        this.init();
    }

    init() {
        //Validation en temps réel
        this.inputPseudo.addEventListener("input", () =>
            this.validateField(this.inputPseudo)
        );
        this.inputMail.addEventListener("input", () =>
            this.validateEmail(this.inputMail)
        );
        this.textareaCommentaire.addEventListener("input", () =>
            this.updateCharCounter()
        );

        //Note
        this.stars.forEach(star => {
            star.addEventListener("click", () =>
                this.setRating(star.dataset.rating));
        });

        //Soumission du formaulaire
        this.form.addEventListener("submit", (e) =>
            this.handleSubmit(e));
    }

    validateField(field) {
        const value = field.value.trim();
        if (value === "") {
            field.classList.remove("is-valid");
            field.classList.add("is-invalid");
            return false;
        } else {
            field.classList.remove("is-invalid");
            field.classList.add("is-valid");
            return true;
        }
    }

    validateEmail(field) {
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value);
        field.classList.toggle("is-valid", isValid);
        field.classList.toggle("is-invalid", !isValid);
        return isValid;
    }

    updateCharCounter() {
        const length = this.textareaCommentaire.value.length;
        this.charCounter.textContent = `${length}/500 caractères`;
    }

    setRating(rating) {
        this.inputRating.value = rating;
        this.stars.forEach(star => {
            star.classList.toggle("selected", star.dataset.rating <= rating);
        });
        this.ratingText.textContent = `Vous avez donné ${rating} étoile${rating > 1 ? 's' : ''}`;
    }

    async handleSubmit(e) {
        e.preventDefault();

        const isValidPseudo = this.validateField(this.inputPseudo);
        const isValidMail = this.validateEmail(this.inputMail);
        const isValidCommentaire = this.textareaCommentaire.value.trim().length >= 10;
        if (!isValidCommentaire) {
            this.textareaCommentaire.classList.add("is-invalid");
        } else {
            this.textareaCommentaire.classList.remove("is-invalid");
            this.textareaCommentaire.classList.add("is-valid");
        }

        if (!this.inputRating.value) {
            alert("Veuillez donner une note avant de soumettre.");
        }

        if (!isValidPseudo || !isValidMail || !isValidCommentaire || !this.inputRating.value) return;

        // Préparer les données
        const data = {
            pseudo: this.inputPseudo.value,
            email: this.inputMail.value,
            rating: this.inputRating.value,
            commentaire: this.textareaCommentaire.value
        };

        try {
            // Exemple d'envoi AJAX vers le backend
            const response = await fetch("/?controller=avis&action=submit", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            if (response.ok && result.success) {
                this.successMessage.style.display = "block";
                this.form.reset();
                document.querySelectorAll(".is-valid").forEach(el => el.classList.remove("is-valid"));
                this.updateCharCounter();
                this.ratingText.textContent = "Cliquez pour noter votre expérience";
                this.stars.forEach(star => star.classList.remove("selected"));
            } else {
                alert(result.message || "Erreur lors de l'envoi de votre avis.");
            }
        } catch (err) {
            console.error("Erreur réseau :", err);
            alert("Erreur réseau, veuillez réessayer.");
        }
    }
}

