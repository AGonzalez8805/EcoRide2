<?php require_once APP_ROOT . '/views/header.php'; ?>
<?php
// Valeurs par défaut si le contrôleur n'a pas passé ces variables
$participationDuJour = $participationDuJour ?? [];
$historiqueTrajets   = $historiqueTrajets   ?? [];
$mesAvis             = $mesAvis             ?? [];
?>

<section class="en-tete">
    <div class="container">
        <img src="/photos/<?= htmlspecialchars($user->getPhoto()) ?>" alt="Profil" class="profile-photo-header">
        <p class="user-info">
            <strong>
                <h1>Bonjour <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getName()) ?></h1>
            </strong>
        <h2>Bienvenue dans votre espace passager</h2>
        <span class="user-credits">Crédit : <?= htmlspecialchars($user->getCredit()) ?></span>
        </p>
    </div>
</section>

<div class="dashboard-container">
    <!-- Statistiques rapides -->
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

    <!-- Historique des trajets -->
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

    <!-- Mes avis -->
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

    <!-- Actions rapides -->
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

<?php require_once APP_ROOT . '/views/footer.php'; ?>