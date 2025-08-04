<?php require_once APP_ROOT . '/views/header.php'; ?>


<!-- Header utilisateur -->
<div class="user-header">
    <div class="container">
        <h1>Bienvenue Jean Dupont !</h1>
        <div class="user-credits">💰 Crédits: <strong>25</strong></div>
    </div>
</div>

<div class="container mt-4">
    <!-- Mon profil -->
    <div class="simple-card">
        <h2 class="card-title">👤 Mon profil</h2>

        <h5>Je suis :</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="role-box" data-role="passager">
                    <h6>🚗 Passager</h6>
                    <p>Je cherche des trajets</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="role-box" data-role="chauffeur">
                    <h6>🚙 Chauffeur</h6>
                    <p>Je propose des trajets</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="role-box selected" data-role="both">
                    <h6>🚗🚙 Les deux</h6>
                    <p>Passager et chauffeur</p>
                </div>
            </div>
        </div>

        <!-- Section véhicules -->
        <div id="vehicleSection">
            <h5 class="mt-4">Mes véhicules</h5>
            <div class="list-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Renault Zoé Blanche</strong><br>
                        <small>4 places • AB-123-CD • 2022 • ⚡ Électrique</small>
                    </div>
                    <button class="btn-outline btn">Modifier</button>
                </div>
            </div>
            <button class="btn-green btn" id="addVehicleBtn">+ Ajouter un véhicule</button>

            <!-- Formulaire d'ajout véhicule -->
            <div id="addVehicleForm" class="hidden mt-3">
                <h6>Nouveau véhicule</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Plaque</label>
                        <input type="text" class="form-control" placeholder="AB-123-CD">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date immatriculation</label>
                        <input type="date" class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Marque</label>
                        <input type="text" class="form-control" placeholder="Renault">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Modèle</label>
                        <input type="text" class="form-control" placeholder="Zoé">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Couleur</label>
                        <input type="text" class="form-control" placeholder="Blanche">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Places</label>
                        <select class="form-control">
                            <option>2</option>
                            <option>4</option>
                            <option>5</option>
                            <option>7</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Énergie</label>
                        <select class="form-control">
                            <option>Essence</option>
                            <option>Diesel</option>
                            <option>Électrique</option>
                            <option>Hybride</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check d-inline-block me-3">
                        <input class="form-check-input" type="checkbox" id="noSmoking">
                        <label class="form-check-label" for="noSmoking">Non-fumeur</label>
                    </div>
                    <div class="form-check d-inline-block">
                        <input class="form-check-input" type="checkbox" id="pets">
                        <label class="form-check-label" for="pets">Animaux OK</label>
                    </div>
                </div>
                <button class="btn-green btn me-2">Enregistrer</button>
                <button class="btn-outline btn" id="cancelVehicle">Annuler</button>
            </div>
        </div>
    </div>

    <!-- Proposer un trajet -->
    <div class="simple-card">
        <h2 class="card-title">🛣️ Proposer un trajet</h2>
        <form>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Départ</label>
                    <input type="text" class="form-control" placeholder="Paris">
                </div>
                <div class="col-md-6 mb-3">
                    <label>Arrivée</label>
                    <input type="text" class="form-control" placeholder="Lyon">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>Date</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Heure</label>
                    <input type="time" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Prix (crédits)</label>
                    <input type="number" class="form-control" placeholder="15">
                </div>
            </div>
            <div class="mb-3">
                <label>Véhicule</label>
                <select class="form-control">
                    <option>Renault Zoé Blanche (4 places)</option>
                </select>
            </div>
            <button type="submit" class="btn-green btn">Publier le trajet</button>
        </form>
    </div>

    <!-- Mes trajets -->
    <div class="simple-card">
        <h2 class="card-title">📋 Mes trajets</h2>

        <div class="mb-3">
            <button class="btn-outline btn active" data-filter="all">Tous</button>
            <button class="btn-outline btn ms-2" data-filter="driver">Chauffeur</button>
            <button class="btn-outline btn ms-2" data-filter="passenger">Passager</button>
        </div>

        <ul class="simple-list">
            <li class="list-item" data-type="driver">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Paris → Lyon</strong><br>
                        <small>15 Août 2024 - 14h30 • Chauffeur • 3/4 places</small>
                    </div>
                    <div>
                        <span class="status status-active">En cours</span>
                        <button class="btn-outline btn btn-sm ms-2">Gérer</button>
                    </div>
                </div>
            </li>

            <li class="list-item" data-type="passenger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Lyon → Marseille</strong><br>
                        <small>12 Août 2024 - 09h00 • Passager • Marie D.</small>
                    </div>
                    <div>
                        <span class="status status-completed">Terminé</span>
                        <button class="btn-outline btn btn-sm ms-2">Noter</button>
                    </div>
                </div>
            </li>

            <li class="list-item" data-type="driver">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Marseille → Nice</strong><br>
                        <small>10 Août 2024 - 16h00 • Chauffeur • Annulé</small>
                    </div>
                    <div>
                        <span class="status status-cancelled">Annulé</span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>


<?php require_once APP_ROOT . '/views/footer.php'; ?>