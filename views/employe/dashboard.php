<?php require_once APP_ROOT . '/views/header.php'; ?>

<div class="en-tete">
    <div class="container">
        <h1>Espace Employé</h1>
        <div class="user-info">
            <p> Connecté en tant que : <strong><?= htmlspecialchars($_SESSION['pseudo'] ?? 'Employé') ?></strong></p>
        </div>
    </div>
</div>

<section class="admin-stat">
    <div class="container-fluid">
        <div class="dashboard-grid">

            <!-- Statistiques rapides -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="card-title">Statistiques du jour</h3>
                </div>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" id="avisEnAttente"><?= $stats['avisEnAttente'] ?></div>
                        <div class="stat-label">Avis en attente</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="incidentsOuverts"><?= $stats['incidentsOuverts'] ?></div>
                        <div class="stat-label">Incidents ouverts</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="avisTraites"><?= $stats['avisTraites'] ?></div>
                        <div class="stat-label">Avis traités aujourd'hui</div>
                    </div>
                </div>
            </div>

            <!-- Validation des avis -->
            <div class="card" style="grid-column: span 2;">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="card-title">Validation des avis</h3>
                </div>

                <div class="avis-validation-container">
                    <!-- Filtres -->
                    <div class="filters-row mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <select class="form-control" id="filterStatus">
                                    <option value="">Tous les statuts</option>
                                    <option value="en_attente">En attente</option>
                                    <option value="valide">Validé</option>
                                    <option value="refuse">Refusé</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" id="filterNote">
                                    <option value="">Toutes les notes</option>
                                    <option value="5">5 étoiles</option>
                                    <option value="4">4 étoiles</option>
                                    <option value="3">3 étoiles</option>
                                    <option value="2">2 étoiles</option>
                                    <option value="1">1 étoile</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" id="filterDate"
                                    value="<?= date('Y-m-d') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Liste des avis -->
                    <div class="avis-list" id="avisList">
                        <?php if (empty($avisEnAttente)): ?>
                            <p>Aucun avis en attente.</p>
                        <?php else: ?>
                            <?php foreach ($avisEnAttente as $avis): ?>
                                <div class="avis-item pending" data-id="<?= $avis['id'] ?>">
                                    <div class="avis-header">
                                        <div class="avis-info">
                                            <div class="chauffeur-name">
                                                <i class="fas fa-user me-2"></i>
                                                <strong><?= htmlspecialchars($avis['pseudo']) ?></strong>
                                                <span class="badge status-pending ms-2">En attente</span>
                                            </div>
                                            <div class="avis-meta">
                                                <span class="date">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?= $avis['created_at'] ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="avis-rating">
                                            <div class="stars">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <span class="star <?= $i <= $avis['note'] ? 'active' : '' ?>">★</span>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="rating-number"><?= $avis['note'] ?>/5</span>
                                        </div>
                                    </div>

                                    <div class="avis-content">
                                        <div class="auteur">
                                            <i class="fas fa-user-circle me-2"></i>
                                            Par : <strong><?= htmlspecialchars($avis['email']) ?></strong>
                                        </div>
                                        <div class="commentaire">
                                            "<?= htmlspecialchars($avis['commentaire']) ?>"
                                        </div>
                                    </div>

                                    <div class="avis-actions">
                                        <a class="btn-dashboard btn-valider" href="/?controller=employe&action=valider&id=<?= $avis['id'] ?>">
                                            <i class="fas fa-check me-1"></i>Valider
                                        </a>
                                        <a class="btn-dashboard btn-refuser" href="/?controller=employe&action=refuser&id=<?= $avis['id'] ?>">
                                            <i class="fas fa-times me-1"></i>Refuser
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestion des incidents -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3 class="card-title">Incidents signalés</h3>
            </div>

            <div class="incidents-container">
                <div class="incident-item urgent" data-id="1">
                    <div class="incident-header">
                        <div class="incident-status">
                            <span class="badge status-urgent">Urgent</span>
                            <span class="incident-id">#INC-001</span>
                        </div>
                        <div class="incident-date">
                            <i class="fas fa-clock me-1"></i>
                            Il y a 2h
                        </div>
                    </div>

                    <div class="incident-details">
                        <div class="trajet-info">
                            <i class="fas fa-route me-2"></i>
                            <strong>Lyon → Genève</strong>
                            <span class="ms-2 text-muted">15/08/2024 - 09:00</span>
                        </div>

                        <div class="participants">
                            <div class="chauffeur">
                                <i class="fas fa-steering-wheel me-1"></i>
                                Chauffeur: <strong>Pierre L.</strong>
                                <span class="email">(pierre.l@email.com)</span>
                            </div>
                            <div class="passagers">
                                <i class="fas fa-users me-1"></i>
                                Passagers: <strong>Marie K., Thomas B.</strong>
                            </div>
                        </div>

                        <div class="incident-description">
                            <i class="fas fa-comment-alt me-2"></i>
                            <em>"Le conducteur ne s'est pas présenté au point de rendez-vous.
                                Aucune réponse aux messages."</em>
                        </div>
                    </div>

                    <div class="incident-actions">
                        <button class="btn-dashboard" onclick="traiterIncident(1)">
                            <i class="fas fa-cog me-1"></i>Traiter
                        </button>
                        <button class="btn-dashboard btn-contact" onclick="contacterParties(1)">
                            <i class="fas fa-envelope me-1"></i>Contacter
                        </button>
                    </div>
                </div>

                <div class="incident-item normal" data-id="2">
                    <div class="incident-header">
                        <div class="incident-status">
                            <span class="badge status-normal">Normal</span>
                            <span class="incident-id">#INC-002</span>
                        </div>
                        <div class="incident-date">
                            <i class="fas fa-clock me-1"></i>
                            Il y a 5h
                        </div>
                    </div>

                    <div class="incident-details">
                        <div class="trajet-info">
                            <i class="fas fa-route me-2"></i>
                            <strong>Toulouse → Montpellier</strong>
                            <span class="ms-2 text-muted">14/08/2024 - 18:30</span>
                        </div>

                        <div class="participants">
                            <div class="chauffeur">
                                <i class="fas fa-steering-wheel me-1"></i>
                                Chauffeur: <strong>Julie M.</strong>
                                <span class="email">(julie.m@email.com)</span>
                            </div>
                            <div class="passagers">
                                <i class="fas fa-users me-1"></i>
                                Passager: <strong>Antoine R.</strong>
                            </div>
                        </div>

                        <div class="incident-description">
                            <i class="fas fa-comment-alt me-2"></i>
                            <em>"Désaccord sur le prix final du trajet. Le chauffeur a demandé
                                un supplément pour l'essence non prévu initialement."</em>
                        </div>
                    </div>

                    <div class="incident-actions">
                        <button class="btn-dashboard" onclick="traiterIncident(2)">
                            <i class="fas fa-cog me-1"></i>Traiter
                        </button>
                        <button class="btn-dashboard btn-contact" onclick="contacterParties(2)">
                            <i class="fas fa-envelope me-1"></i>Contacter
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<?php require_once APP_ROOT . '/views/footer.php'; ?>