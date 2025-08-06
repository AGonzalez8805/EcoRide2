<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="en-tete">
    <div class="container">
        <h1>Ajouter un véhicule</h1>
        <p>Ajoutez votre véhicule pour pouvoir proposer des trajets</p>
    </div>
</section>

<div class="container">
    <div id="vehicleMessage" class="message"></div>

    <div class="vehicle-card">
        <h2 class="card-title-dash">📋 Informations du véhicule</h2>

        <form method="POST" action="?controller=vehicule&action=store<?php if (isset($_GET['from'])) echo '&from=' . $_GET['from']; ?>">

            <!-- Informations de base -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="marque">Marque <span class="required">*</span></label>
                        <input type="text" id="marque" name="marque" class="form-control" required
                            placeholder="Ex: Peugeot, Renault, Toyota...">
                        <div class="info-text">Indiquez la marque de votre véhicule</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="modele">Modèle <span class="required">*</span></label>
                        <input type="text" id="modele" name="modele" class="form-control" required
                            placeholder="Ex: 308, Clio, Yaris...">
                        <div class="info-text">Précisez le modèle exact</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="couleur">Couleur <span class="required">*</span></label>
                        <select id="couleur" name="couleur" class="form-control" required>
                            <option value="">Choisir une couleur</option>
                            <option value="blanc">Blanc</option>
                            <option value="noir">Noir</option>
                            <option value="gris">Gris</option>
                            <option value="bleu">Bleu</option>
                            <option value="rouge">Rouge</option>
                            <option value="vert">Vert</option>
                            <option value="jaune">Jaune</option>
                            <option value="orange">Orange</option>
                            <option value="marron">Marron</option>
                            <option value="violet">Violet</option>
                            <option value="beige">Beige</option>
                            <option value="argent">Argent</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nbPlaces">Nombre de places <span class="required">*</span></label>
                        <input type="number" id="nbPlaces" name="nbPlaces" class="form-control"
                            min="2" max="9" required placeholder="Ex: 5">
                        <div class="info-text">Places totales du véhicule (conducteur inclus)</div>
                    </div>
                </div>
            </div>

            <!-- Immatriculation et date -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="immatriculation">Plaque d'immatriculation <span class="required">*</span></label>
                        <input type="text" id="immatriculation" name="immatriculation" class="form-control"
                            required placeholder="Ex: AB-123-CD" style="text-transform: uppercase;">
                        <div class="info-text">Format français (XX-XXX-XX)</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="datePremierImmatriculation">Date de première immatriculation <span class="required">*</span></label>
                        <input type="date" id="datePremierImmatriculation" name="datePremierImmatriculation" class="form-control" required>
                        <div class="info-text">Date indiquée sur la carte grise</div>
                    </div>
                </div>
            </div>

            <!-- Type d'énergie -->
            <div class="form-group">
                <label>Type d'énergie <span class="required">*</span></label>
                <div class="energy-grid">
                    <div class="energy-option">
                        <input type="radio" id="essence" name="energie" value="essence" required>
                        <label for="essence" class="energy-label">
                            ⛽ Essence
                        </label>
                    </div>
                    <div class="energy-option">
                        <input type="radio" id="diesel" name="energie" value="diesel" required>
                        <label for="diesel" class="energy-label">
                            🚗 Diesel
                        </label>
                    </div>
                    <div class="energy-option">
                        <input type="radio" id="electrique" name="energie" value="electrique" required>
                        <label for="electrique" class="energy-label">
                            🔋 Électrique
                            <span class="eco-badge">ÉCO</span>
                        </label>
                    </div>
                    <div class="energy-option">
                        <input type="radio" id="hybride" name="energie" value="hybride" required>
                        <label for="hybride" class="energy-label">
                            🌱 Hybride
                            <span class="eco-badge">ÉCO</span>
                        </label>
                    </div>
                    <div class="energy-option">
                        <input type="radio" id="gpl" name="energie" value="gpl" required>
                        <label for="gpl" class="energy-label">
                            💨 GPL
                        </label>
                    </div>
                </div>
            </div>

            <!-- Préférences -->
            <div class="form-group">
                <label>Préférences de voyage</label>
                <div class="preferences-grid">
                    <div class="preference-item">
                        <input type="checkbox" id="fumeur" name="preferences[]" value="fumeur_accepte">
                        <label for="fumeur">🚭 Fumeur accepté</label>
                    </div>
                    <div class="preference-item">
                        <input type="checkbox" id="animaux" name="preferences[]" value="animaux_acceptes">
                        <label for="animaux">🐕 Animaux acceptés</label>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="button-group">
                <button type="submit" class="btn-dashboard">
                    ✅ Ajouter le véhicule
                </button>
                <a href="/?controller=user&action=dashboardChauffeur" class="btn-secondary">
                    ↩️ Retour au tableau de bord
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>