<?php require_once APP_ROOT . '/views/header.php'; ?>

<?php
$jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$mois = [1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
?>

<section class="en-tete">
    <div class="container">
        <h1>Résultats</h1>
    </div>
</section>

<section class="container">
    <?php if (empty($trajets)) : ?>
        <p>Aucun covoiturage trouvé pour les critères indiqués.</p>
    <?php else : ?>
        <?php foreach ($trajets as $trajet) : ?>
            <div class="card-search">
                <ul class="list-group">
                    <li class="list-group-item">
                        <p>
                            <strong>Départ :</strong> <?= htmlspecialchars($trajet->getLieuDepart()) ?>
                            le <?= !empty($trajet->getDateDepart()) ? date('d/m/Y', strtotime($trajet->getDateDepart())) : 'Date inconnue' ?>
                            à <?= !empty($trajet->getHeureDepart()) ? date('H:i', strtotime($trajet->getHeureDepart())) : '--h--' ?>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p>
                            <strong>Arrivée :</strong> <?= htmlspecialchars($trajet->getLieuArrivee()) ?>
                            le <?= !empty($trajet->getDateArrivee()) ? date('d/m/Y', strtotime($trajet->getDateArrivee())) : 'Date inconnue' ?>
                            à <?= !empty($trajet->getHeureArrivee()) ? date('H:i', strtotime($trajet->getHeureArrivee())) : '--h--' ?>
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p>
                            <strong>Places restantes :</strong> <?= $trajet->getNbPlace() ?> |
                            <strong>Prix :</strong> <?= $trajet->getPrixPersonne() ?> crédits
                        </p>
                    </li>
                    <li class="list-group-item">
                        <p><strong>Statut :</strong> <?= htmlspecialchars($trajet->getStatut()) ?></p>
                    </li>
                </ul>

                <div class="driver-section">
                    <div class="driver-info-simple">
                        <div class="driver-avatar-simple">
                            <?php if (!empty($trajet->getChauffeurPhoto())): ?>
                                <img src="/photos/<?= htmlspecialchars($trajet->getChauffeurPhoto()) ?>" class="profile-photo-header" alt="Profil <?= htmlspecialchars($trajet->getChauffeurPrenom()) ?>">
                            <?php else: ?>
                                <?= strtoupper(substr($trajet->getChauffeurPrenom(), 0, 1)) ?>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="driver-name">
                                <?= htmlspecialchars($trajet->getChauffeurPrenom() . ' ' . $trajet->getChauffeurNom()) ?>
                            </div>
                        </div>
                    </div>

                    <div class="trip-details-grid">
                        <div class="detail-item">
                            <i class="fas fa-users detail-icon"></i>
                            <span><?= htmlspecialchars($trajet->getNbPlace()) ?> places libres</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-car detail-icon"></i>
                            <span><?= htmlspecialchars($trajet->getVehicule()->getMarque()) ?> <?= htmlspecialchars($trajet->getVehicule()->getModele()) ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-smoking<?= $trajet->getVehicule()->isFumeur() ? '' : '-ban' ?> detail-icon"></i>
                            <span><?= $trajet->getVehicule()->isFumeur() ? 'Fumeur' : 'Non-fumeur' ?></span>
                        </div>
                    </div>

                    <div class="trip-actions-simple">
                        <a href="#" class="btn-detail-simple">Détails</a>
                        <form method="POST" action="/participations/reserver">
                            <input type="hidden" name="id_covoiturage" value="<?= $trajet->getId() ?>">
                            <button type="submit" class="btn-book-simple">Participer</button>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>