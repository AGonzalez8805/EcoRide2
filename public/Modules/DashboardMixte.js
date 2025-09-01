export class DashboardMixte {
    constructor() {
        console.log("Mixte instancié");
        // Bouton toggle
        this.toggleBtn = document.getElementById("mode-toggle");

        // Labels
        this.passagerLabel = document.getElementById("passager-label");
        this.chauffeurLabel = document.getElementById("chauffeur-label");

        // Badge + texte
        this.modeIndicator = document.getElementById("mode-indicator");
        this.modeText = document.getElementById("mode-text");

        // Contenus
        this.passagerContent = document.getElementById("passager-content");
        this.chauffeurContent = document.getElementById("chauffeur-content");

        // Mode par défaut
        this.currentMode = "passager";

        this.init();
    }

    init() {
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener("click", () => this.toggleMode());
        }
    }

    toggleMode() {
        this.currentMode =
            this.currentMode === "passager" ? "chauffeur" : "passager";

        if (this.currentMode === "passager") {
            this.showPassager();
        } else {
            this.showChauffeur();
        }
    }

    showPassager() {
        this.passagerLabel.classList.add("active");
        this.chauffeurLabel.classList.remove("active");

        this.passagerContent.classList.remove("hidden");
        this.chauffeurContent.classList.add("hidden");

        this.modeIndicator.className = "mode-badge passager";
        this.modeText.textContent = "👤 Mode Passager";

        // Déplacer le slider
        this.toggleBtn.classList.remove("active");
    }

    showChauffeur() {
        this.chauffeurLabel.classList.add("active");
        this.passagerLabel.classList.remove("active");

        this.chauffeurContent.classList.remove("hidden");
        this.passagerContent.classList.add("hidden");

        this.modeIndicator.className = "mode-badge chauffeur";
        this.modeText.textContent = "🚗 Mode Chauffeur";

        // Déplacer le slider
        this.toggleBtn.classList.add("active");
    }

}
