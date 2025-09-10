<?php require_once APP_ROOT . '/views/header.php'; ?>

<?php
$jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$mois = [1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

if (session_status() === PHP_SESSION_NONE) session_start();

$participationRepo = new \App\Repository\ParticipationRepository();
?>

<section class="container">
    <?php if (empty($trajets)) : ?>
        <p>Aucun covoiturage trouvé pour les critères indiqués.</p>
    <?php else : ?>
        <?php foreach ($trajets as $trajet) : ?>
            <div class="card-search">
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Départ :</strong> <?= htmlspecialchars($trajet->getLieuDepart()) ?>
                        le <?= !empty($trajet->getDateDepart()) ? date('d/m/Y', strtotime($trajet->getDateDepart())) : 'Date inconnue' ?>
                        à <?= !empty($trajet->getHeureDepart()) ? date('H:i', strtotime($trajet->getHeureDepart())) : '--h--' ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Arrivée :</strong> <?= htmlspecialchars($trajet->getLieuArrivee()) ?>
                        le <?= !empty($trajet->getDateArrivee()) ? date('d/m/Y', strtotime($trajet->getDateArrivee())) : 'Date inconnue' ?>
                        à <?= !empty($trajet->getHeureArrivee()) ? date('H:i', strtotime($trajet->getHeureArrivee())) : '--h--' ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Places restantes :</strong> <?= $trajet->getNbPlace() ?> |
                        <strong>Prix :</strong> <?= $trajet->getPrixPersonne() ?> €
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
                        <div class="driver-name">
                            <?= htmlspecialchars($trajet->getChauffeurPrenom() . ' ' . $trajet->getChauffeurNom()) ?>
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

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($participationRepo->userHasParticipation($_SESSION['user_id'], $trajet->getId())): ?>
                                <form method="POST" action="/?controller=participer&action=annuler">
                                    <input type="hidden" name="id_covoiturage" value="<?= $trajet->getId() ?>">
                                    <button type="submit" class="btn-book-simple cancel">Annuler ma participation</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="/?controller=participer&action=participer">
                                    <input type="hidden" name="id_covoiturage" value="<?= $trajet->getId() ?>">
                                    <input type="number" name="nb_place" min="1" max="<?= $trajet->getNbPlace() ?>" value="1">
                                    <button type="submit" class="btn-book-simple">Participer</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="/?controller=auth&action=login" class="btn-book-simple">Se connecter pour participer</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>