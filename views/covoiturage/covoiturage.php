<?php require_once APP_ROOT . '/views/header.php'; ?>
<!-- En-tête de la page -->
<div class="en-tete">
    <h1><i class="fas fa-car"></i> Trouvez votre covoiturage</h1>
    <p>Voyagez écologique, économique et convivial</p>
</div>

<section class="covoiturage-container">
    <!-- Sidebar de recherche et filtres -->
    <aside class="search-sidebar">
        <h2 class="sidebar-title">🚗 Rechercher</h2>

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

            <button type="submit" class="btn-search-simple">
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
            <!-- Trajet 1 -->
            <section class="trip-card-simple">
                <div class="trip-main-info">
                    <div>
                        <div class="trip-route-simple">Paris → Lyon</div>
                        <div class="trip-time">📅 Lundi 5 août • 14h30 → 18h15</div>
                    </div>
                    <div class="trip-price-simple">
                        25€
                        <div class="price-per-person">par personne</div>
                    </div>
                </div>

                <div class="trip-details-grid">
                    <div class="detail-item">
                        <i class="fas fa-users detail-icon"></i>
                        <span>2 places libres</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-car detail-icon"></i>
                        <span>Peugeot 308</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock detail-icon"></i>
                        <span>3h45</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-smoking-ban detail-icon"></i>
                        <span>Non-fumeur</span>
                    </div>
                </div>

                <div class="driver-section">
                    <div class="driver-info-simple">
                        <div class="driver-avatar-simple">JP</div>
                        <div>
                            <div class="driver-name">Jean-Pierre</div>
                            <div class="driver-rating-simple">
                                <span class="stars-simple">★★★★★</span>
                                <span>4.8 (24 avis)</span>
                            </div>
                        </div>
                    </div>
                    <div class="trip-actions-simple">
                        <a href="#" class="btn-detail-simple">Détails</a>
                        <button class="btn-book-simple" onclick="bookTripSimple(1)">Réserver</button>
                    </div>
                </div>
            </section>

            <!-- Trajet 2 - Électrique -->
            <section class="trip-card-simple">
                <div class="trip-main-info">
                    <div>
                        <div class="trip-route-simple">
                            Paris → Lyon
                            <span class="eco-badge-simple">🌱 Électrique</span>
                        </div>
                        <div class="trip-time">📅 Lundi 5 août • 16h00 → 19h30</div>
                    </div>
                    <div class="trip-price-simple">
                        30€
                        <div class="price-per-person">par personne</div>
                    </div>
                </div>

                <div class="trip-details-grid">
                    <div class="detail-item">
                        <i class="fas fa-users detail-icon"></i>
                        <span>1 place libre</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-bolt detail-icon"></i>
                        <span>Tesla Model 3</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock detail-icon"></i>
                        <span>3h30</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-paw detail-icon"></i>
                        <span>Animaux OK</span>
                    </div>
                </div>

                <div class="driver-section">
                    <div class="driver-info-simple">
                        <div class="driver-avatar-simple">S</div>
                        <div>
                            <div class="driver-name">Sophie</div>
                            <div class="driver-rating-simple">
                                <span class="stars-simple">★★★★★</span>
                                <span>4.9 (18 avis)</span>
                            </div>
                        </div>
                    </div>
                    <div class="trip-actions-simple">
                        <a href="#" class="btn-detail-simple">Détails</a>
                        <button class="btn-book-simple" onclick="bookTripSimple(2)">Réserver</button>
                    </div>
                </div>
            </section>

            <!-- Trajet 3 -->
            <section class="trip-card-simple">
                <div class="trip-main-info">
                    <div>
                        <div class="trip-route-simple">Paris → Lyon</div>
                        <div class="trip-time">📅 Lundi 5 août • 20h00 → 23h45</div>
                    </div>
                    <div class="trip-price-simple">
                        20€
                        <div class="price-per-person">par personne</div>
                    </div>
                </div>

                <div class="trip-details-grid">
                    <div class="detail-item">
                        <i class="fas fa-users detail-icon"></i>
                        <span>3 places libres</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-car detail-icon"></i>
                        <span>Renault Clio</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock detail-icon"></i>
                        <span>3h45</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-music detail-icon"></i>
                        <span>Musique partagée</span>
                    </div>
                </div>

                <div class="driver-section">
                    <div class="driver-info-simple">
                        <div class="driver-avatar-simple">M</div>
                        <div>
                            <div class="driver-name">Michel</div>
                            <div class="driver-rating-simple">
                                <span class="stars-simple">★★★★☆</span>
                                <span>4.2 (31 avis)</span>
                            </div>
                        </div>
                    </div>
                    <div class="trip-actions-simple">
                        <a href="#" class="btn-detail-simple">Détails</a>
                        <button class="btn-book-simple" onclick="bookTripSimple(3)">Réserver</button>
                    </div>
                </div>
            </section>

            <!-- Trajet 4 - Complet -->
            <section class="trip-card-simple trip-full">
                <div class="trip-main-info">
                    <div>
                        <div class="trip-route-simple">
                            Paris → Lyon
                            <span class="full-badge">Complet</span>
                        </div>
                        <div class="trip-time">📅 Lundi 5 août • 10h00 → 13h30</div>
                    </div>
                    <div class="trip-price-simple">
                        22€
                        <div class="price-per-person">par personne</div>
                    </div>
                </div>

                <div class="trip-details-grid">
                    <div class="detail-item">
                        <i class="fas fa-users detail-icon"></i>
                        <span style="color: #dc3545; font-weight: bold;">0 place libre</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-car detail-icon"></i>
                        <span>VW Golf</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-clock detail-icon"></i>
                        <span>3h30</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-coffee detail-icon"></i>
                        <span>Pause café</span>
                    </div>
                </div>

                <div class="driver-section">
                    <div class="driver-info-simple">
                        <div class="driver-avatar-simple">C</div>
                        <div>
                            <div class="driver-name">Caroline</div>
                            <div class="driver-rating-simple">
                                <span class="stars-simple">★★★★★</span>
                                <span>4.7 (15 avis)</span>
                            </div>
                        </div>
                    </div>
                    <div class="trip-actions-simple">
                        <a href="#" class="btn-detail-simple">Détails</a>
                        <button class="btn-book-simple" disabled>Complet</button>
                    </div>
                </div>
            </section>
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