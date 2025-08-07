<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="en-tete">
    <div class="container">
        <h1>Mon espace chauffeur</h1>
        <p class="user-info">
            Bonjour <strong>Marc</strong>
            <span class="user-credits">Solde : 35 crédits</span>
        </p>
    </div>
</section>

<div class="dashboard-container">
    <!-- Boutons principaux -->
    <div class="simple-card">
        <h3 class="card-title-dash">Mes actions</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <a href="/?controller=covoiturage&action=create" class=" btn-profil w-100">
                    Créer un trajet
                </a>
            </div>
            <div class="col-md-6">
                <a href="/?controller=vehicule&action=create" class="btn-outline w-100">
                    Ajouter un véhicule
                </a>
            </div>
        </div>
    </div>

    <!-- Mes trajets aujourd'hui -->
    <div class="simple-card">
        <h3 class="card-title-dash">Mes trajets aujourd'hui</h3>

        <?php if (!empty($trajetsDuJour)): ?>
            <?php foreach ($trajetsDuJour as $trajet): ?>
                <div class="trip-item">
                    <div class="trip-info">
                        <div class="trip-route">
                            <?= htmlspecialchars($trajet->getLieuDepart()) ?> → <?= htmlspecialchars($trajet->getLieuArrivee()) ?>
                        </div>
                        <div class="trip-date">
                            Aujourd'hui à <?= date('H:i', strtotime($trajet->getHeureDepart())) ?>
                        </div>
                    </div>
                    <div class="trip-details">
                        <span><?= $trajet->getNbPlace() ?> places</span>
                        <span class="status"><?= ucfirst($trajet->getStatut()) ?></span>

                        <?php if ($trajet->getStatut() === 'en cours'): ?>
                            <form method="post" action="/?controller=trajet&action=finish">
                                <input type="hidden" name="trajet_id" value="<?= $trajet->getId() ?>">
                                <button class="btn-profil btn-sm">Terminer</button>
                            </form>
                        <?php elseif ($trajet->getStatut() === 'programmé'): ?>
                            <form method="post" action="/?controller=trajet&action=start">
                                <input type="hidden" name="trajet_id" value="<?= $trajet->getId() ?>">
                                <button class="btn-outline btn-sm">Démarrer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun trajet prévu aujourd'hui.</p>
        <?php endif; ?>
    </div>


    <!-- Statistiques simples -->
    <div class="simple-card">
        <h3 class="card-title-dash">Mes chiffres</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">8</div>
                <div class="stat-label">Trajets ce mois</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">4.5</div>
                <div class="stat-label">Ma note</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">25</div>
                <div class="stat-label">Passagers transportés</div>
            </div>
        </div>
    </div>

    <!-- Derniers avis -->
    <div class="simple-card">
        <h3 class="card-title-dash">Derniers avis</h3>

        <div class="avis-item">
            <div class="d-flex justify-content-between">
                <strong>Marie</strong>
                <span>⭐⭐⭐⭐⭐</span>
            </div>
            <p>"Très bon chauffeur, ponctuel !"</p>
            <small class="text-muted">Il y a 2 jours</small>
        </div>

        <div class="avis-item">
            <div class="d-flex justify-content-between">
                <strong>Pierre</strong>
                <span>⭐⭐⭐⭐</span>
            </div>
            <p>"Trajet agréable et voiture propre."</p>
            <small class="text-muted">Il y a 5 jours</small>
        </div>

        <div class="text-center mt-3">
            <a href="/?controller=user&action=reviews" class="btn-outline">Voir tous mes avis</a>
        </div>
    </div>

    <!-- Liens utiles -->
    <div class="simple-card">
        <h3 class="card-title-dash">Liens utiles</h3>
        <div class="row g-2">
            <div class="col-md-6">
                <a href="/?controller=user&action=history" class="btn-outline w-100">Mon historique</a>
            </div>
            <div class="col-md-6">
                <a href="/?controller=user&action=profile" class="btn-outline w-100">Mon profil</a>
            </div>
        </div>
    </div>
</div>


<?php require_once APP_ROOT . '/views/footer.php'; ?>