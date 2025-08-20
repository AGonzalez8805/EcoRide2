export class Contact {
    constructor() {
        this.form = document.getElementById("contactForm");
        this.inputName = document.getElementById("name");
        this.inputFirstName = document.getElementById("firstName");
        this.inputMail = document.getElementById("email");
        this.inputPhone = document.getElementById("phone");
        this.inputSubject = document.getElementById("subject");
        this.inputMessage = document.getElementById("message");

        this.init();
    }

    init() {
        this.inputName.addEventListener("input", () =>
            this.validateField(this.inputName)
        );
        this.inputFirstName.addEventListener("input", () =>
            this.validateField(this.inputFirstName)
        );

        // Validation de l'email au format
        this.inputMail.addEventListener("input", () =>
            this.validateEmail(this.inputMail)
        );

        this.inputPhone.addEventListener("input", () =>
            this.validateField(this.inputPhone)
        );

        this.inputSubject.addEventListener("input", () =>
            this.validateField(this.inputSubject)
        );

        this.inputMessage.addEventListener("input", () =>
            this.validateField(this.inputMessage)
        );

        this.form.addEventListener("submit", (e) => {
            e.preventDefault();
            this.validateForm();
        });
    }

    validateField(field) {
        const value = field.value.trim();
        // Spécifique au champ téléphone
        if (field === this.inputPhone) {
            const phoneRegex = /^[0-9\s-]{10,}$/;
            const isValid = phoneRegex.test(value);
            field.classList.toggle("is-valid", isValid);
            field.classList.toggle("is-invalid", !isValid);
            return isValid;
        }
        // Validation générique pour les autres champs
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

    async validateForm() {
        const isValidName = this.validateField(this.inputName);
        const isValidFirstName = this.validateField(this.inputFirstName);
        const isValidMail = this.validateEmail(this.inputMail);
        const isValidPhone = this.validateField(this.inputPhone);
        const isValidSubject = this.validateField(this.inputSubject);
        const isValidMessage = this.validateField(this.inputMessage);

        if (
            !isValidName ||
            !isValidFirstName ||
            !isValidMail ||
            !isValidPhone ||
            !isValidSubject ||
            !isValidMessage
        ) {
            return;
        }

        const data = {
            name: this.inputName.value,
            firstName: this.inputFirstName.value,
            email: this.inputMail.value,
            phone: this.inputPhone.value,
            subject: this.inputSubject.value,
            message: this.inputMessage.value,
        };

        try {
            console.log("Envoi du message...", data);
            const response = await fetch("/?controller=page&action=sendMessage", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();
            if (response.ok && result.success) {
                alert(result.message || "Message envoyé avec succès !");
                this.form.reset();
                document.querySelectorAll(".is-valid").forEach(el => el.classList.remove("is-valid"));
            } else {
                alert(result.message || "Erreur lors de l'envoi du message.");
            }

        } catch (err) {
            console.error("Erreur réseau :", err);
            alert("Erreur réseau, veuillez réessayer.");
        }
    }
}
