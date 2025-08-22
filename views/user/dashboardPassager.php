<?php require_once APP_ROOT . '/views/header.php'; ?>

<div class="en-tete">
    <h1>Bonjour <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getName()) ?></h1>
    <p>Bienvenue dans votre espace passager</p>
    <div class="user-credits">
        💰 Crédits disponibles : <strong><?= htmlspecialchars($user->getCredit()) ?></strong>
    </div>
</div>

<div class="dashboard-container">
    <!-- Statistiques rapides -->
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
                    <button class="btn-outline" onclick="cancelTrip(1)">Annuler</button>
                    <button class="btn-dashboard" onclick="contactDriver(1)">Contacter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des trajets -->
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

            <div class="trip-item">
                <div class="trip-info">
                    <div class="trip-route">Nice → Monaco</div>
                    <div class="status status-cancelled">Annulé</div>
                </div>
                <div class="trip-details">
                    <span class="trip-date">📅 Mercredi 20 juillet 2025</span>
                    <span>👤 Conducteur : Thomas</span>
                    <span>💰 8€ (remboursé)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes avis -->
    <div class="simple-card">
        <h2 class="card-title-dash">⭐ Mes avis</h2>
        <?php if (!empty($mesAvis)): ?>
            <ul>
                <?php foreach ($mesAvis as $avis): ?>
                    <li>
                        <strong><?= htmlspecialchars($avis->getPseudo() ?? '') ?></strong> :
                        <?= htmlspecialchars($avis->getCommentaire() ?? '') ?>
                        <em>(<?= $avis->getDatePublication() ? $avis->getDatePublication()->format('Y-m-d H:i:s') : '' ?>)</em>
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



    <!-- Actions rapides -->
    <div class="simple-card">
        <h2 class="card-title">⚡ Actions rapides</h2>
        <div class="row" style="margin: 15px;">
            <div class="col-md-4 mb-3">
                <a href="/?controller=user&action=profil" class="btn-profil w-100">
                    👤 Modifier mon profil
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <a href="/?controller=pages&action=help" class="btn btn-outline w-100">
                    ❓ Aide
                </a>
            </div>
        </div>
    </div>
</div>



<?php require_once APP_ROOT . '/views/footer.php'; ?>