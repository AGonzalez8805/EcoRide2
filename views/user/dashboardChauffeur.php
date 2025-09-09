<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="en-tete">
    <div class="container">
        <img src="/photos/<?= htmlspecialchars($user->getPhoto()) ?>" alt="Profil" class="profile-photo-header">
        <div class="user-info">
            <strong>
                <h1>Bonjour <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getName()) ?></h1>
            </strong>
            <h2 id="welcome-message">Bienvenue sur ton espace chauffeur</h2>
            <span class="user-credits">Crédit : <?= htmlspecialchars($user->getCredit()) ?></span>
        </div>
</section>

<div class="dashboard-container">
    <!-- Boutons principaux -->
    <div class="simple-card">
        <h3 class="card-title-dash"> 🛠️ Mes actions</h3>
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
    <!-- Véhicules -->
    <div class="simple-card">
        <h3 class="card-title-dash">🚗 Mes véhicules</h3>
        <?php if (!empty($vehicules)): ?>
            <div class="vehicle-list">
                <?php foreach ($vehicules as $vehicule): ?>
                    <div class="vehicle-box">
                        <strong><?= htmlspecialchars($vehicule->getMarque()) ?> <?= htmlspecialchars($vehicule->getModele()) ?></strong>
                        <div class="vehicle-info">
                            <span>🔢 <?= htmlspecialchars($vehicule->getImmatriculation()) ?></span>
                            <span>👥 <?= $vehicule->getNbPlaces() ?> places</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-vehicle">
                Vous n'avez pas encore de véhicule enregistré
            </div>
        <?php endif; ?>
    </div>

    <!-- Mes trajets aujourd'hui -->
    <div class="simple-card">
        <h3 class="card-title-dash"> 🗺️ Mes trajets aujourd'hui</h3>
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
        <h3 class="card-title-dash"> 📊 Mes chiffres</h3>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= htmlspecialchars($trajetsCeMois) ?></div>
                <div class="stat-label">Trajets ce mois</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= htmlspecialchars($noteMoyenne) ?></div>
                <div class="stat-label">Ma note</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= htmlspecialchars($nbPassagers) ?></div>
                <div class="stat-label">Passagers transportés</div>
            </div>
        </div>
    </div>

    <!-- Derniers avis -->
    <div class="simple-card">
        <h2 class="card-title-dash">⭐ Derniers avis</h2>
        <?php if (!empty($avisValides)): ?>
            <?php foreach ($avisValides as $avis): ?>
                <div class="testimonial-card">
                    <p class="testimonial-text">
                        <?= htmlspecialchars($avis->getCommentaire() ?? '') ?>
                    </p>
                    <div class="testimonial-author"><?= htmlspecialchars($avis->getPseudo() ?? '') ?></div>
                    <?php if (!empty($avis->getNote())): ?>
                        <div class="testimonial-role">Note : <?= htmlspecialchars($avis->getNote()) ?>/5</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun avis pour le moment.</p>
        <?php endif; ?>
    </div>
    <!-- Liens utiles -->
    <div class="simple-card">
        <h2 class="card-title-dash"> 🔗 Liens utiles</h2>
        <div class="row g-2">
            <div class="col-md-6 mb-3">
                <a href="/?controller=user&action=profil" class="btn-profil w-100">👤 Modifier mon profil</a>
            </div>
            <div class="col-md-6">
                <a href="/?controller=user&action=history" class="btn-outline w-100"> 📜 Mon historique</a>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>