<?php require_once APP_ROOT . '/views/header.php'; ?>
<div id="dashboardMixte">
    <section class="en-tete">
        <div class="container">
            <img src="/photos/<?= htmlspecialchars($user->getPhoto()) ?>" alt="Profil" class="profile-photo-header">
            <div class="user-info">
                <strong>
                    <h1>Bonjour <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getName()) ?></h1>
                </strong>
                <p id="welcome-message">Bienvenue dans votre espace mixte</p>
                <span class="user-credits">Crédit : <?= htmlspecialchars($user->getCredit()) ?></span>
            </div>

            <!-- Toggle Mode -->
            <div class="mode-toggle-container">
                <span id="passager-label" class="mode-label active">👤 Passager</span>
                <button id="mode-toggle" class="toggle-btn">
                    <span class="toggle-slider"></span>
                </button>
                <span id="chauffeur-label" class="mode-label">🚗 Chauffeur</span>
            </div>
        </div>
    </section>

    <div class="dashboard-container">
        <!-- Mode Indicator -->
        <div class="simple-card">
            <div id="mode-indicator" class="mode-badge passager">
                <span id="mode-text">👤 Mode Passager</span>
            </div>
        </div>

        <!-- CONTENU PASSAGER -->
        <div id="passager-content" class="mode-content">
            <!-- Statistiques Passager -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Trajets effectués</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">2</div>
                    <div class="stat-label">Trajets à venir</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">142€</div>
                    <div class="stat-label">Économies réalisées</div>
                </div>
            </div>

            <!-- Mes prochains trajets -->
            <div class="simple-card">
                <h2 class="card-title-dash">🚗 Mes prochains trajets</h2>
                <div id="upcoming-trips">
                    <div class="trip-item">
                        <div class="trip-info">
                            <div class="trip-route">Paris → Lyon</div>
                            <div class="status status-reserved">Réservé</div>
                        </div>
                        <div class="trip-details">
                            <span class="trip-date">📅 Vendredi 8 août 2025 à 14h30</span>
                            <span>👤 Conducteur : Jean-Pierre</span>
                            <span>💰 15€</span>
                        </div>
                        <div class="mt-3">
                            <button class="btn-outline" onclick="cancelTrip(1)">Annuler</button>
                            <button class="btn-dashboard" onclick="contactDriver(1)">Contacter</button>
                        </div>
                    </div>
                    <div class="trip-item">
                        <div class="trip-info">
                            <div class="trip-route">Lyon → Marseille</div>
                            <div class="status status-reserved">Réservé</div>
                        </div>
                        <div class="trip-details">
                            <span class="trip-date">📅 Dimanche 10 août 2025 à 9h00</span>
                            <span>👤 Conducteur : Sophie</span>
                            <span>💰 20€</span>
                        </div>
                        <div class="mt-3">
                            <button class="btn-outline" onclick="cancelTrip(2)">Annuler</button>
                            <button class="btn-dashboard" onclick="contactDriver(2)">Contacter</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des trajets passager -->
            <div class="simple-card">
                <h2 class="card-title-dash">📋 Historique de mes trajets</h2>
                <div id="trip-history">
                    <div class="trip-item">
                        <div class="trip-info">
                            <div class="trip-route">Lille → Paris</div>
                            <div class="status status-completed">Terminé</div>
                        </div>
                        <div class="trip-details">
                            <span class="trip-date">📅 Lundi 28 juillet 2025</span>
                            <span>👤 Conducteur : Michel</span>
                            <span>💰 12€</span>
                            <span>⭐ Note donnée : 5/5</span>
                        </div>
                    </div>

                    <div class="trip-item">
                        <div class="trip-info">
                            <div class="trip-route">Paris → Bordeaux</div>
                            <div class="status status-completed">Terminé</div>
                        </div>
                        <div class="trip-details">
                            <span class="trip-date">📅 Vendredi 25 juillet 2025</span>
                            <span>👤 Conducteur : Caroline</span>
                            <span>💰 25€</span>
                            <span>⭐ Note donnée : 4/5</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides Passager -->
            <div class="simple-card">
                <h2 class="card-title">⚡ Actions rapides</h2>
                <div class="row" style="margin: 15px;">
                    <div class="col-md-4 mb-3">
                        <a href="/?controller=covoiturage&action=resultats" class="btn-profil w-100">
                            🔍 Rechercher un trajet
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/?controller=user&action=profil" class="btn-profil w-100">
                            👤 Modifier mon profil
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/?controller=avis&action=avis" class="btn btn-outline w-100">
                            ⭐ Déposer un avis
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENU CHAUFFEUR -->
        <div id="chauffeur-content" class="mode-content hidden">
            <!-- Actions principales Chauffeur -->
            <div class="simple-card">
                <h3 class="card-title-dash">Mes actions</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="/?controller=covoiturage&action=create" class="btn-profil w-100">
                            🚗 Créer un trajet
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/?controller=vehicule&action=create" class="btn-outline w-100">
                            🚙 Ajouter un véhicule
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistiques Chauffeur -->
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

            <!-- Véhicules -->
            <div class="info-card">
                <h3>🚗 Mes véhicules</h3>
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

            <!-- Derniers avis -->
            <div class="simple-card">
                <h2 class="card-title-dash">⭐ Derniers avis reçus</h2>
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

            <!-- Liens utiles Chauffeur -->
            <div class="simple-card">
                <h3 class="card-title-dash">Liens utiles</h3>
                <div class="row g-2">
                    <div class="col-md-6">
                        <a href="/?controller=user&action=history" class="btn-outline w-100">Mon historique</a>
                    </div>
                    <div class="col-md-6">
                        <a href="/?controller=user&action=profil" class="btn-outline w-100">Mon profil</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Avis communs (visibles dans les deux modes) -->
        <div class="simple-card">
            <h2 class="card-title-dash">⭐ Mes avis</h2>
            <?php if (!empty($mesAvis)): ?>
                <ul>
                    <?php foreach ($mesAvis as $avis): ?>
                        <li>
                            <strong><?= htmlspecialchars($avis->getPseudo() ?? '') ?></strong> :
                            <?= htmlspecialchars($avis->getCommentaire() ?? '') ?>
                            <em>(<?= $avis->getDatePublication() ? $avis->getDatePublication()->toDateTime()->format('Y-m-d H:i:s') : '' ?>)</em>
                            - Note : <?= htmlspecialchars($avis->getNote() ?? '') ?>
                            - Statut :
                            <?php if ($avis->getStatut() === 'valide'): ?>
                                ✅ Validé
                            <?php elseif ($avis->getStatut() === 'en_attente'): ?>
                                ⏳ En attente
                            <?php else: ?>
                                ❌ <?= htmlspecialchars($avis->getStatut() ?? 'inconnu') ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Vous n'avez encore posté aucun avis.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php require_once APP_ROOT . '/views/footer.php'; ?>