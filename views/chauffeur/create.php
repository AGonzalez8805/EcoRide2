<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="en-tete">
    <div class="container">
        <h1>Créer un nouveau trajet</h1>
        <p>Remplissez les informations ci-dessous pour proposer un nouveau trajet.</p>
    </div>
</section>

<div class="form-container container mt-4">
    <form method="POST" id="trajetForm" action="/?controller=chauffeur&action=store">
        <div class="row">
            <div class="mb-3 col">
                <label for="lieuDepart" class="form-label">Ville de départ</label>
                <input type="text" id="lieuDepart" name="lieuDepart" list="lieuDepart" class="form-control" required>
                <datalist id="liste-depart"></datalist>
            </div>

            <div class="mb-3 col">
                <label for="lieuArrivee" class="form-label">Ville d'arrivée</label>
                <input type="text" id="lieuArrivee" name="lieuArrivee" list="liste-destination" class="form-control" required>
                <datalist id="liste-destination"></datalist>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col">
                <label for="date_heure" class="form-label">Date de départ</label>
                <input type="date" class="form-control" id="dateDepart" name="dateDepart" required>
            </div>
            <div class="mb-3 col">
                <label for="dateArrivee" class="form-label">Date d'arrivée</label>
                <input type="date" class="form-control" id="dateArrivee" name="dateArrivee" required>
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col">
                <label for="heureDepart" class="form-label">heure de départ</label>
                <input type="time" class="form-control" id="heureDepart" name="heureDepart" required>
            </div>
            <div class="mb-3 col">
                <label for="heureArrivee" class="form-label">Heure d'arrivée</label>
                <input type="time" class="form-control" id="heureArrivee" name="heureArrivee" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="nbPlace" class="form-label">Places disponibles</label>
            <input type="number" class="form-control" id="nbPlace" name="nbPlace" min="1" required>
        </div>

        <div class="mb-3">
            <label for="prixPersonne" class="form-label">Prix par passager</label>
            <input type="number" class="form-control" id="prixPersonne" name="prixPersonne" step="0.5" min="2" required>
        </div>

        <div class="mb-3">
            <label for="vehicule" class="form-label">Véhicule</label>
            <select name="vehicule" id="vehicule" class="form-select" required>
                <option value="">-- Choisir un véhicule --</option>
                <option value="nouveau">+ Ajouter un nouveau véhicule</option>
                <?php foreach ($vehicules as $vehicule): ?>
                    <option value="<?= htmlspecialchars($vehicule->getId()) ?>">
                        <?= htmlspecialchars($vehicule->getNom()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Préférences personnalisées -->
        <div class="form-group">
            <label for="preferencesSupplementaires ">Préférences personnalisées</label>
            <textarea id="preferencesSupplementaires " name="preferencesSupplementaires " class="form-control"
                placeholder="Décrivez vos préférences particulières (optionnel)&#10;Ex: Pas de discussions téléphoniques, arrêts autorisés, etc."></textarea>
            <div class="info-text">Précisez vos règles de voyage (optionnel)</div>
        </div>

        <div class="alert alert-info">
            <small>Note : 2 crédits seront prélevés de votre solde pour la publication de ce trajet</small>
        </div>

        <button type="submit" class="btn-dashboard">Créer le trajet</button>
    </form>
    <div id="trajetMessage"></div>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>