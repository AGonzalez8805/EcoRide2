<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="en-tete">
    <div class="container">
        <h1>Ajouter un véhicule</h1>
        <p>Ajoutez votre véhicule pour pouvoir proposer des trajets</p>
    </div>
</section>

<section class="container vehicule-card">
    <h2 class="card-title-dash">📋 Informations du véhicule</h2>
    <form method="POST" id="vehiculeForm" action="?controller=vehicule&action=store" novalidate>
        <!-- Informations de base -->
        <section class="row">
            <div class="col-md-6 form-group">
                <label for="marque">Marque <span class="required">*</span></label>
                <input type="text" id="marque" name="marque" class="form-control" placeholder="Peugeot, Renault, Toyota...">
                <div class="invalid-feedback"> Veuillez entrer une marque. </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="modele">Modèle <span class="required">*</span></label>
                    <input type="text" id="modele" name="modele" class="form-control" placeholder="308, Clio, Yaris...">
                    <div class="invalid-feedback"> Veuillez entrer un modele. </div>
                </div>
            </div>
        </section>

        <section class="row">
            <div class="col-md-6 form-group">
                <label for="couleur">Couleur <span class="required">*</span></label>
                <input type="text" id="couleur" name="couleur" class="form-control" placeholder="Rouge, Bleu, Vert...">
                <div class="invalid-feedback"> Veuillez entrer une couleur. </div>
            </div>
            <div class="col-md-6 form-group">
                <label for="nbPlaces">Nombre de places <span class="required">*</span></label>
                <input type="number" id="nbPlaces" name="nbPlaces" class="form-control" min="1" max="9" placeholder="5">
                <div class="invalid-feedback"> Veuillez entrer un nombre de places. </div>
            </div>
        </section>

        <!-- Immatriculation et date -->
        <section class="row">
            <div class="col-md-6 form-group">
                <label for="immatriculation">Plaque d'immatriculation <span class="required">*</span></label>
                <input type="text" id="immatriculation" name="immatriculation" class="form-control" placeholder="AB-123-CD" style="text-transform: uppercase;">
                <div class="invalid-feedback"> Veuillez entrer une plaque d'immatriculation. </div>
            </div>
            <div class="col-md-6 form-group">
                <label for="datePremierImmatriculation">Date de première immatriculation <span class="required">*</span></label>
                <input type="date" id="datePremierImmatriculation" name="datePremierImmatriculation" class="form-control">
                <div class="invalid-feedback"> Veuillez entrer une date de première immatriculation. </div>
            </div>
        </section>

        <!-- Type d'énergie -->
        <section class="row">
            <div class="col-md-6 form-group">
                <label for="energie">Type d'énergie <span class="required">*</span></label>
                <input type="text" id="energie" name="energie" class="form-control" placeholder="Hybride, électrique, essence...">
                <div class="invalid-feedback"> Veuillez entrer un type d'énergie. </div>
            </div>
        </section>

        <!-- Préférences -->
        <div class="form-group">
            <label>Préférences de voyage</label>
            <div class="preferences-grid">
                <div class="preference-item">
                    <input type="checkbox" id="fumeur" name="fumeur" value="1">
                    <label for="fumeur">Fumeur accepté</label>
                </div>
                <div class="preference-item">
                    <input type="checkbox" id="animaux" name="animaux" value="1">
                    <label for="animaux">Animaux acceptés</label>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="button-group">
            <button type="submit" class="btn-dashboard">
                Ajouter le véhicule
            </button>
            <a href="/?controller=user&action=dashboardChauffeur" class="btn-secondary">
                Retour au tableau de bord
            </a>
        </div>
    </form>
    <div id="vehiculeFormError" class="alert alert-danger d-none"></div>

</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>