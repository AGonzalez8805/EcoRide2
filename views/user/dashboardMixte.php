<?php require_once APP_ROOT . '/views/header.php'; ?>
<div id="dashboardMixte">
    <section class="en-tete">
        <div class="container">
            <img src="/photos/<?= htmlspecialchars($user->getPhoto()) ?>" alt="Profil" class="profile-photo-header">
            <div class="user-info">
                <strong>
                    <h1>Bonjour <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getName()) ?></h1>
                </strong>
                <h2 id="welcome-message">Bienvenue dans votre espace mixte</h2>
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
                    <div class="stat-number"><?= count($participationDuJour) ?></div>
                    <div class="stat-label">Trajets aujourd'hui</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= count($historiqueTrajets ?? []) ?></div>
                    <div class="stat-label">Trajets passés</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= array_sum(array_map(fn($t) => $t->getPrixPersonne(), $participationDuJour)) ?>€</div>
                    <div class="stat-label">Crédits dépensés</div>
                </div>
            </div>

            <!-- Mes prochains trajets -->
            <div class="simple-card">
                <h2 class="card-title-dash">🚗 Mes prochains trajets</h2>
                <div id="upcoming-trips">
                    <?php if (!empty($participationDuJour)): ?>
                        <?php foreach ($participationDuJour as $trajet): ?>
                            <div class="trip-item">
                                <div class="trip-info">
                                    <div class="trip-route"><?= htmlspecialchars($trajet->getLieuDepart() . ' → ' . $trajet->getLieuArrivee()) ?></div>
                                    <div class="status status-reserved">Réservé</div>
                                </div>
                                <div class="trip-details">
                                    <span class="trip-date">📅 <?= (new DateTime($trajet->getDateDepart() . ' ' . $trajet->getHeureDepart()))->format('l d F Y à H\hi') ?></span>
                                    <span>👤 Conducteur : <?= htmlspecialchars($trajet->getChauffeurPrenom() . ' ' . $trajet->getChauffeurNom()) ?></span>
                                    <span>💰 <?= htmlspecialchars($trajet->getPrixPersonne()) ?>€</span>
                                </div>
                                <div class="mt-3">
                                    <button class="btn-outline" onclick="cancelTrip(<?= $trajet->getId() ?>)">Annuler</button>
                                    <button class="btn-dashboard" onclick="contactDriver(<?= $trajet->getId() ?>)">Contacter</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun trajet disponible.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historique des trajets passager -->
            <div class="simple-card">
                <h2 class="card-title-dash">📋 Historique de mes trajets</h2>
                <div id="trip-history">
                    <?php if (!empty($historiqueTrajets)): ?>
                        <?php foreach ($historiqueTrajets as $trajet): ?>
                            <div class="trip-item">
                                <div class="trip-info">
                                    <div class="trip-route"><?= htmlspecialchars($trajet->getLieuDepart() . ' → ' . $trajet->getLieuArrivee()) ?></div>
                                    <div class="status <?= $trajet->getStatut() === 'termine' ? 'status-completed' : 'status-cancelled' ?>">
                                        <?= ucfirst($trajet->getStatut()) ?>
                                    </div>
                                </div>
                                <div class="trip-details">
                                    <span class="trip-date">📅 <?= htmlspecialchars($trajet->getDateDepart()) ?></span>
                                    <span>👤 Conducteur : <?= htmlspecialchars($trajet->getChauffeurPrenom() . ' ' . $trajet->getChauffeurNom()) ?></span>
                                    <span>💰 <?= htmlspecialchars($trajet->getPrixPersonne()) ?>€</span>
                                    <?php if ($trajet->getNote()): ?>
                                        <span>⭐ Note donnée : <?= htmlspecialchars($trajet->getNote()) ?>/5</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucun trajet historique disponible.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Avis  -->
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
            <!-- Actions rapides Passager -->
            <div class="simple-card">
                <h2 class="card-title-dash"> 🔗 Liens utiles</h2>
                <div class="row" style="margin: 15px;">
                    <div class="col-md-4 mb-3">
                        <a href="/?controller=user&action=profil" class="btn-profil w-100">
                            👤 Modifier mon profil
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/?controller=avis&action=avis" class="btn btn-outline w-100">
                            ⭐ Déposer un avis</a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="/?controller=pages&action=help" class="btn btn-outline w-100">
                            ❓ Aide
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENU CHAUFFEUR -->
        <div id="chauffeur-content" class="mode-content hidden">
            <!-- Actions principales Chauffeur -->
            <div class="simple-card">
                <h3 class="card-title-dash"> 🛠️ Mes actions</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="/?controller=covoiturage&action=create" class="btn-profil w-100">
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
                <h3 class="card-title-dash"> 🔗 Liens utiles</h3>
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
    </div>
</div>


<?php require_once APP_ROOT . '/views/footer.php'; ?>