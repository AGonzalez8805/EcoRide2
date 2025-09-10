<?php require_once APP_ROOT . '/views/header.php'; ?>
<?php
$jours = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
$mois = [1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
?>
<section class="en-tete">
    <div class="container">
        <h1>Trouvez votre covoiturage</h1>
        <p class="user-info">Voyagez écologique, économique et convivial</p>
    </div>
</section>

<section class="covoiturage-container">
    <aside class="search-sidebar">
        <h2 class="sidebar-title">Rechercher</h2>
        <form id="search-covoiturage" class="search-form-simple" method="get" action="/?controller=covoiturage&action=resultats">
            <input type="hidden" name="controller" value="covoiturage">
            <input type="hidden" name="action" value="resultats">
            <div class="form-group-simple">
                <label for="departure-simple">Ville de départ</label>
                <input type="text" class="form-control-simple" name="depart" id="departure-simple" placeholder="Ex: Paris">
            </div>
            <div class="form-group-simple">
                <label for="arrival-simple">Ville d'arrivée</label>
                <input type="text" class="form-control-simple" name="arrivee" id="arrival-simple" placeholder="Ex: Lyon">
            </div>
            <div class="form-group-simple">
                <label for="date-simple">Date de départ</label>
                <input type="date" class="form-control-simple" name="date" id="date-simple">
            </div>
            <button type="submit" class="btn-search">Rechercher</button>
        </form>
    </aside>

    <section class="results-area">
        <div class="results-header-simple">
            <div class="results-count-simple">
                <i class="fas fa-map-marker-alt"></i>
                <?= count($trajets) ?> trajet<?= count($trajets) > 1 ? 's' : '' ?> trouvé<?= count($trajets) > 1 ? 's' : '' ?>
            </div>
            <div class="sort-container">
                <label for="sort-by">Trier par:</label>
                <select class="sort-select-simple" id="sort-by">
                    <option value="price">Prix croissant</option>
                    <option value="time">Heure de départ</option>
                    <option value="rating">Meilleure note</option>
                    <option value="duration">Durée</option>
                </select>
            </div>
        </div>

        <div id="trips-list">
            <?php foreach ($trajets as $trajet): ?>
                <section class="trip-card-simple">
                    <div class="trip-main-info">
                        <div>
                            <div class="trip-route-simple">
                                <?= htmlspecialchars($trajet->getLieuDepart()) ?> → <?= htmlspecialchars($trajet->getLieuArrivee()) ?>
                            </div>
                            <div class="trip-date">
                                <?php if (!empty($trajet->getHeureDepart())):
                                    $datetime = $trajet->getDateDepart() . ' ' . $trajet->getHeureDepart();
                                    $timestamp = strtotime($datetime);
                                ?>
                                    <?= $jours[date('w', $timestamp)] ?> <?= date('d', $timestamp) ?> <?= $mois[date('n', $timestamp)] ?> <?= date('Y', $timestamp) ?>
                                <?php else: ?>
                                    Date inconnue
                                <?php endif; ?>
                            </div>
                            <div class="trip-hours">
                                <?= !empty($trajet->getHeureDepart()) ? date('H:i', strtotime($trajet->getHeureDepart())) : '--h--' ?> →
                                <?= !empty($trajet->getHeureArrivee()) ? date('H:i', strtotime($trajet->getHeureArrivee())) : '--h--' ?>
                            </div>
                        </div>
                        <div class="trip-price-simple">
                            <?= htmlspecialchars($trajet->getPrixPersonne()) ?>€
                            <div class="price-per-person">par personne</div>
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

                        <div class="trip-actions-simple">
                            <a href="#" class="btn-detail-simple">Détails</a>
                            <form method="POST" action="/?controller=participer&action=participer">
                                <input type="hidden" name="id_covoiturage" value="<?= $trajet->getId() ?>">
                                <button type="submit" class="btn-book-simple">Participer</button>
                            </form>
                        </div>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>

        <section class="no-results-simple" id="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>Aucun trajet trouvé</h3>
            <p>Essayez de modifier vos critères de recherche ou changez la date.</p>
            <button class="btn-search-simple" onclick="resetFilters()">Réinitialiser les filtres</button>
        </section>
    </section>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>