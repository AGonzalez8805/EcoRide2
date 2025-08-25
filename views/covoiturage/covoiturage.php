<?php require_once APP_ROOT . '/views/header.php'; ?>
<!-- En-tête de la page -->
<section class="en-tete">
    <div class="container">
        <h1>Trouvez votre covoiturage</h1>
        <p class="user-info">
            Voyagez écologique, économique et convivial
        </p>
    </div>
</section>

<section class="covoiturage-container">
    <!-- Sidebar de recherche et filtres -->
    <aside class="search-sidebar">
        <h2 class="sidebar-title">Rechercher</h2>

        <form id="search-covoiturage" class="search-form-simple">
            <div class="form-group-simple">
                <label for="departure-simple">Ville de départ</label>
                <input type="text" class="form-control-simple" name="departure" id="departure-simple" placeholder="Ex: Paris" value="Paris">
            </div>

            <div class="form-group-simple">
                <label for="arrival-simple">Ville d'arrivée</label>
                <input type="text" class="form-control-simple" name="arrival" id="arrival-simple" placeholder="Ex: Lyon" value="Lyon">
            </div>

            <div class="form-group-simple">
                <label for="date-simple">Date de départ</label>
                <input type="date" class="form-control-simple" name="date" id="date-simple" value="2025-08-05">
            </div>

            <button type="submit" class="btn-search">
                Rechercher
            </button>
        </form>

        <div class="filters-section-sidebar">
            <h3 class="filters-title-sidebar">🎛️ Filtres</h3>

            <div class="filter-item">
                <label>Prix maximum</label>
                <div class="range-container">
                    <input type="range" class="range-input" min="0" max="100" value="50" id="price-range">
                    <div class="range-value" id="price-display">50€</div>
                </div>
            </div>

            <div class="filter-item">
                <label>Durée maximum</label>
                <select class="form-control-simple" id="duration-filter">
                    <option value="">Toutes</option>
                    <option value="2">2h maximum</option>
                    <option value="4">4h maximum</option>
                    <option value="6">6h maximum</option>
                </select>
            </div>

            <div class="filter-item">
                <label>Note minimum</label>
                <select class="form-control-simple" id="rating-filter">
                    <option value="">Toutes</option>
                    <option value="4">4+ étoiles</option>
                    <option value="4.5">4.5+ étoiles</option>
                </select>
            </div>

            <div class="filter-item">
                <div class="checkbox-container">
                    <input type="checkbox" id="eco-only">
                    <label for="eco-only">Véhicules électriques uniquement</label>
                </div>
            </div>
        </div>
    </aside>

    <!-- Zone des résultats -->
    <section class="results-area">
        <div class="results-header-simple">
            <div class="results-count-simple">
                <i class="fas fa-map-marker-alt"></i> 6 trajets trouvés
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
            <!-- Trajet -->
            <?php foreach ($trajets as $trajet): ?>
                <section class="trip-card-simple">
                    <div class="trip-main-info">
                        <div>
                            <div class="trip-route-simple">
                                <?= htmlspecialchars($trajet['lieuDepart'] ?? '') ?> → <?= htmlspecialchars($trajet['lieuArrivee'] ?? '') ?>
                            </div>
                            <div class="trip-date">
                                <?= !empty($trajet['heureDepart']) ? date('l j F Y', strtotime($trajet['heureDepart'])) : 'Date inconnue' ?>
                            </div>
                            <div class="trip-hours">
                                <?= !empty($trajet['heureDepart']) ? date('H\hi', strtotime($trajet['heureDepart'])) : '--h--' ?> →
                                <?= !empty($trajet['heureArrivee']) ? date('H\hi', strtotime($trajet['heureArrivee'])) : '--h--' ?>
                            </div>
                        </div>
                        <div class="trip-price-simple">
                            <?= htmlspecialchars($trajet['prixPersonne'] ?? '') ?>€
                            <div class="price-per-person">par personne</div>
                        </div>
                    </div>
                    <div class="trip-details-grid">
                        <div class="detail-item">
                            <i class="fas fa-users detail-icon"></i>
                            <span><?= htmlspecialchars($trajet['nbPlace'] ?? '') ?> places libres</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-car detail-icon"></i>
                            <span><?= htmlspecialchars($trajet['marque']) ?> <?= htmlspecialchars($trajet['modele'] ?? '') ?></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-smoking<?= $trajet['fumeur'] ? '' : '-ban' ?> detail-icon"></i>
                            <span><?= $trajet['fumeur'] ? 'Fumeur' : 'Non-fumeur' ?></span>
                        </div>
                    </div>
                    <div class="driver-section">
                        <div class="driver-info-simple">
                            <div class="driver-avatar-simple">
                                <?= strtoupper(substr($trajet['chauffeur_prenom'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="driver-name"><?= $trajet['chauffeur_prenom'] ?> <?= $trajet['chauffeur_nom'] ?></div>
                            </div>
                        </div>
                        <div class="trip-actions-simple">
                            <a href="#" class="btn-detail-simple">Détails</a>
                            <form method="POST" action="/participations/reserver">
                                <input type="hidden" name="id_covoiturage" value="<?= $trajet['id'] ?>">
                                <button type="submit" class="btn-book-simple">Réserver</button>
                            </form>
                        </div>

                    </div>
                </section>
            <?php endforeach; ?>
        </div>

        <!-- Message d'absence de résultats (caché par défaut) -->
        <section class="no-results-simple" id="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>Aucun trajet trouvé</h3>
            <p>Essayez de modifier vos critères de recherche ou changez la date.</p>
            <button class="btn-search-simple" onclick="resetFilters()">Réinitialiser les filtres</button>
        </section>
    </section>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>