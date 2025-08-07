export class ChauffeurTrajet {
    constructor() {
        this.trajetForm = document.getElementById("trajetForm");
        this.trajetMessage = document.getElementById("trajetMessage");

        this.departInputD = document.getElementById("lieuDepart");
        this.departInputA = document.getElementById("lieuArrivee");
        this.dateInputD = document.getElementById("dateDepart");
        this.dateInputA = document.getElementById("dateArrivee");
        this.heureInputD = document.getElementById("heureDepart");
        this.heureInputA = document.getElementById("heureArrivee");
        this.prixInput = document.getElementById("prixPersonne");

        this.datalistDepart = document.getElementById("liste-depart");
        this.datalistDestination = document.getElementById("liste-destination");


        this.apiKey = null; // sera récupérée dynamiquement
        this.departCoord = null;
        this.destinationCoord = null;

        this.init();
    }

    async init() {
        await this.loadApiKey();
        this.setupEventListeners();
    }

    async loadApiKey() {
        try {
            const res = await fetch("/?controller=covoiturage&action=getApiKey", {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });
            const data = await res.json();
            this.apiKey = data.key;
        } catch (error) {
            console.error("Erreur lors du chargement de la clé API :", error);
        }
    }

    setupEventListeners() {
        this.departInputD.addEventListener("input", () => {
            this.fetchVilles(this.departInputD.value, this.datalistDepart, "depart");
        });

        this.departInputA.addEventListener("input", () => {
            this.fetchVilles(this.departInputA.value, this.datalistDestination, "arrivee");
        });

        this.trajetForm.addEventListener("submit", (e) => {
            e.preventDefault();
            this.submitForm();
        });
        const vehiculeSelect = document.getElementById("vehicule");

        if (vehiculeSelect) {
            console.log("✅ Écouteur de changement ajouté sur #vehicule");
            vehiculeSelect.addEventListener("change", (e) => {
                const selectedValue = e.target.value;
                console.log("Sélection dans le menu véhicule :", selectedValue);

                if (selectedValue === "nouveau") {
                    setTimeout(() => {
                        // Petit délai pour éviter blocage par certains navigateurs
                        window.location.href = "/?controller=vehicule&action=create";
                    }, 100);
                }
            });
        } else {
            console.warn("Élément #vehicule non trouvé dans le DOM.");
        }

    }

    async fetchVilles(query, targetDatalist, type) {
        if (query.length < 2) return;

        try {
            const res = await fetch(
                `https://geo.api.gouv.fr/communes?nom=${query}&fields=nom,centre&boost=population&limit=5`
            );
            const villes = await res.json();

            targetDatalist.innerHTML = "";

            villes.forEach((ville) => {
                const option = document.createElement("option");
                option.value = ville.nom;
                option.dataset.lat = ville.centre.coordinates[1];
                option.dataset.lon = ville.centre.coordinates[0];
                targetDatalist.appendChild(option);
            });

            // Enregistre les coordonnées
            const first = villes[0];
            if (first && first.centre && first.centre.coordinates) {
                const coord = {
                    lat: first.centre.coordinates[1],
                    lon: first.centre.coordinates[0],
                };

                if (type === "depart") this.departCoord = coord;
                else this.destinationCoord = coord;

                this.tryEstimateDistance();
            }
        } catch (error) {
            console.error("Erreur autocomplétion:", error);
        }
    }

    async tryEstimateDistance() {
        if (!this.departCoord || !this.destinationCoord) return;

        const body = {
            coordinates: [
                [this.departCoord.lon, this.departCoord.lat],
                [this.destinationCoord.lon, this.destinationCoord.lat],
            ],
        };

        try {
            console.log("Coordonnées envoyées à OpenRouteService :", body);
            const response = await fetch("/?controller=covoiturage&action=proxyRoute", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(body),
            });

            const data = await response.json();
            console.log("Reponse complète d'OpenRouteService :", data);


            if (!data || !data.features || !Array.isArray(data.features) || !data.features[0]) {
                console.error("Réponse inattendue d'OpenRouteService :", data);
                this.trajetMessage.innerHTML = `<span class="text-danger">Impossible de calculer le trajet (données incomplètes).</span>`;
                return;
            }

            const summary = data.features[0].properties.summary;
            const distanceKm = (summary.distance / 1000).toFixed(1);
            const durationMin = Math.round(summary.duration / 60);

            // Exemple : 0.1 crédit par km, minimum 2 crédits
            let estimatedCredit = Math.max(2, Math.ceil(distanceKm * 0.1));

            // Remplit automatiquement le champ "prix" avec les crédits
            this.prixInput.value = estimatedCredit;

            // Affiche estimation
            this.trajetMessage.innerHTML = `
            Distance : <b>${distanceKm} km</b> – 
            Durée : <b>${durationMin} min</b> – 
            Prix suggéré : <b>${estimatedCredit} crédits</b>
            `;
        } catch (error) {
            console.error("Erreur OpenRouteService:", error);
        }
    }

    async submitForm() {
        const formData = new URLSearchParams(new FormData(this.trajetForm)).toString();

        try {
            const res = await fetch("/?controller=covoiturage&action=store", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            });

            const text = await res.text();

            // Essaye de parser en JSON
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error("Réponse serveur non JSON :", text);
                this.trajetMessage.innerHTML = `<span class="text-danger">Erreur serveur inattendue. Voir console.</span>`;
                return;
            }

            if (data.success) {
                this.trajetMessage.innerHTML = "<span class='text-success'>Trajet créé avec succès !</span>";
                this.trajetForm.reset();
            } else {
                this.trajetMessage.innerHTML = "<span class='text-danger'>Erreur : " + data.message + "</span>";
            }
        } catch (error) {
            console.error("Erreur envoi formulaire:", error);
            this.trajetMessage.innerHTML = `<span class="text-danger">Erreur lors de l'envoi du formulaire.</span>`;
        }
    }

}
