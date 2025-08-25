export class EmployeDashboard {
    constructor() {
        this.filterStatus = document.getElementById("filterStatus");
        this.avisList = document.getElementById("avisList");
        this.init();
    }

    init() {
        if (!this.filterStatus || !this.avisList) return;
        this.filterStatus.addEventListener("change", () => this.fetchAvis());
        this.fetchAvis(); // charge tous les avis au démarrage
    }

    async fetchAvis() {
        const statut = this.filterStatus.value;
        try {
            const res = await fetch(`/?controller=employe&action=getAvis&statut=${statut}`);
            const text = await res.text(); // récupère le texte
            let avisArray = [];
            try {
                avisArray = JSON.parse(text); // parse JSON
            } catch {
                console.error("Réponse invalide :", text);
                this.avisList.innerHTML = "<p>Erreur lors du chargement des avis</p>";
                return;
            }

            if (!avisArray.length) {
                this.avisList.innerHTML = "<p>Aucun avis.</p>";
                return;
            }

            this.avisList.innerHTML = avisArray.map(avis => `
                <div class="avis-item" data-id="${avis.id}">
                    <div class="avis-header">
                        <strong>${avis.pseudo}</strong>
                        <span class="badge">${avis.statut}</span>
                        <div>Note: ${avis.note}/5</div>
                        <div>${avis.commentaire}</div>
                        ${avis.statut === 'en_attente' ? `
                            <a href="/?controller=employe&action=valider&id=${avis.id}">Valider</a>
                            <a href="/?controller=employe&action=refuser&id=${avis.id}">Refuser</a>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        } catch (err) {
            console.error(err);
            this.avisList.innerHTML = "<p>Erreur réseau</p>";
        }
    }
}
